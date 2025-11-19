<?php

namespace App\Http\Controllers;

use App\Services\FormasiClassifier;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Exception;

class FormasiController  
{
    public function index()
    {
        return view('beranda');
    }

    public function analyze(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls'
        ]);

        try {
            $file = $request->file('file');

            // Parsing file ke array
            $players = $this->parseFileToArray($file);

            $classifier = new FormasiClassifier();
            $averages = $classifier->calculateAverages($players);
            $recommendations = $classifier->classify($averages);

            // Simpan sementara di session untuk download PDF/Excel
            session(['recommendations' => $recommendations, 'averages' => $averages]);

            return response()->json([
                'success' => true,
                'recommendations' => $recommendations
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function downloadPDF()
    {
        $recommendations = session('recommendations');
        $averages = session('averages');

        if (!$recommendations || !$averages) {
            abort(404, 'Tidak ada data untuk diunduh.');
        }

        // Deskripsi formasi
        $descriptions = [
            '4-3-3' => 'Formasi 4-3-3 menekankan permainan ofensif dan kontrol bola di lini tengah, cocok untuk tim dengan pemain sayap cepat dan penyerang yang produktif.',
            '3-5-2' => 'Formasi 3-5-2 memberikan keunggulan di lini tengah dan sayap, ideal untuk tim yang ingin menguasai permainan dan menyerang dari sisi lapangan.',
            '3-4-3' => 'Formasi 3-4-3 sangat ofensif, dengan tiga penyerang dan empat gelandang, cocok untuk tim dengan intensitas tinggi.',
            '4-2-3-1' => 'Formasi 4-2-3-1 menyeimbangkan antara serangan dan pertahanan, dengan dua gelandang bertahan dan tiga pemain ofensif.',
            '5-4-1' => 'Formasi 5-4-1 sangat solid secara defensif, dengan lima bek dan empat gelandang, cocok untuk tim yang ingin bertahan kuat dan menyerang dari serangan balik.',
        ];

        $data = [
            'recommendations' => $recommendations,
            'averages' => $averages,
            'descriptions' => $descriptions,
        ];

        $pdf = Pdf::loadView('pdf.formation-report', $data);

        return $pdf->download('laporan-formasi-sepak-bola.pdf');
    }

       public function downloadExcel()
    {
        $recommendations = session('recommendations');
        $averages = session('averages');

        if (!$recommendations || !$averages) {
            abort(404, 'Tidak ada data untuk diunduh.');
        }

        // Ambil dataset dari FormasiClassifier
        $classifier = new FormasiClassifier();
        $referenceDataset = $classifier->getReferenceDataset();

        // Deskripsi formasi
        $descriptions = [
            '4-3-3' => 'Formasi 4-3-3 menekankan permainan ofensif dan kontrol bola di lini tengah, cocok untuk tim dengan pemain sayap cepat dan penyerang yang produktif.',
            '3-5-2' => 'Formasi 3-5-2 memberikan keunggulan di lini tengah dan sayap, ideal untuk tim yang ingin menguasai permainan dan menyerang dari sisi lapangan.',
            '3-4-3' => 'Formasi 3-4-3 sangat ofensif, dengan tiga penyerang dan empat gelandang, cocok untuk tim dengan intensitas tinggi.',
            '4-2-3-1' => 'Formasi 4-2-3-1 menyeimbangkan antara serangan dan pertahanan, dengan dua gelandang bertahan dan tiga pemain ofensif.',
            '5-4-1' => 'Formasi 5-4-1 sangat solid secara defensif, dengan lima bek dan empat gelandang, cocok untuk tim yang ingin bertahan kuat dan menyerang dari serangan balik.',
        ];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'LAPORAN ANALISIS KLASIFIKASI FORMASI SEPAK BOLA');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->mergeCells('A1:B1');

        $sheet->setCellValue('A2', 'Formasi Sepak Bola Berdasarkan Statistik Tim');
        $sheet->getStyle('A2')->getFont()->setSize(12);
        $sheet->mergeCells('A2:B2');

        $row = 4;

        // Formasi Terpilih
        $sheet->setCellValue('A' . $row, 'Formasi Terpilih');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('0ea5e9');
        $sheet->getStyle('A' . $row)->getFont()->getColor()->setRGB('FFFFFF');
        $row++;

        foreach ($recommendations as $index => $rec) {
            $sheet->setCellValue('A' . $row, $rec['formasi']);
            $sheet->setCellValue('B' . $row, $descriptions[$rec['formasi']] ?? 'Deskripsi tidak tersedia.');
            $row++;
        }

        $row++;

        // Info Box
        $sheet->setCellValue('A' . $row, 'Metode Klasifikasi: Gaussian Naive Bayes | Probabilitas Tertinggi: ' . number_format($recommendations[0]['prob'] * 100, 2) . '%');
        $sheet->getStyle('A' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('e0f2fe');
        $row++;

        $row++;

        // Rata-rata Statistik Tim
        $sheet->setCellValue('A' . $row, 'Rata-rata Statistik Tim');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('0ea5e9');
        $sheet->getStyle('A' . $row)->getFont()->getColor()->setRGB('FFFFFF');
        $row++;

        $sheet->setCellValue('A' . $row, 'Pace');
        $sheet->setCellValue('B' . $row, number_format($averages[0], 2));
        $row++;
        $sheet->setCellValue('A' . $row, 'Shooting');
        $sheet->setCellValue('B' . $row, number_format($averages[1], 2));
        $row++;
        $sheet->setCellValue('A' . $row, 'Passing');
        $sheet->setCellValue('B' . $row, number_format($averages[2], 2));
        $row++;
        $sheet->setCellValue('A' . $row, 'Dribbling');
        $sheet->setCellValue('B' . $row, number_format($averages[3], 2));
        $row++;
        $sheet->setCellValue('A' . $row, 'Defending');
        $sheet->setCellValue('B' . $row, number_format($averages[4], 2));
        $row++;
        $sheet->setCellValue('A' . $row, 'Physical');
        $sheet->setCellValue('B' . $row, number_format($averages[5], 2));
        $row++;

        $row++;

        // Referensi Formasi
        $sheet->setCellValue('A' . $row, 'Referensi Formasi dari Dataset');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('0ea5e9');
        $sheet->getStyle('A' . $row)->getFont()->getColor()->setRGB('FFFFFF');
        $row++;

        $sheet->setCellValue('A' . $row, 'No');
        $sheet->setCellValue('B' . $row, 'Formasi');
        $sheet->setCellValue('C' . $row, 'Pace');
        $sheet->setCellValue('D' . $row, 'Shooting');
        $sheet->setCellValue('E' . $row, 'Passing');
        $sheet->setCellValue('F' . $row, 'Dribbling');
        $sheet->setCellValue('G' . $row, 'Defending');
        $sheet->setCellValue('H' . $row, 'Physical');
        $sheet->getStyle('A' . $row . ':H' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':H' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('0ea5e9');
        $sheet->getStyle('A' . $row . ':H' . $row)->getFont()->getColor()->setRGB('FFFFFF');
        $row++;

        foreach ($referenceDataset as $index => $ref) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $ref['formasi']);
            $sheet->setCellValue('C' . $row, $ref['Pace']);
            $sheet->setCellValue('D' . $row, $ref['Shooting']);
            $sheet->setCellValue('E' . $row, $ref['Passing']);
            $sheet->setCellValue('F' . $row, $ref['Dribbling']);
            $sheet->setCellValue('G' . $row, $ref['Defending']);
            $sheet->setCellValue('H' . $row, $ref['Physical']);
            $row++;
        }

        $row++;

        // Footer
        $sheet->setCellValue('A' . $row, '&copy; 2025 Klasifikasi Formasi Sepak Bola. All Rights Reserved.');
        $sheet->setCellValue('A' . ($row + 1), 'Laporan digenerate pada: ' . date('d F Y, H:i:s'));
        $sheet->getStyle('A' . $row . ':B' . ($row + 1))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('e2e8f0');
        $sheet->getStyle('A' . $row . ':B' . ($row + 1))->getFont()->getColor()->setRGB('64748b');

        $writer = new Xlsx($spreadsheet);
        $fileName = 'laporan-formasi-sepak-bola.xlsx';

        ob_clean(); // Bersihkan output buffer
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    private function parseFileToArray($file)
    {
        $extension = $file->getClientOriginalExtension();

        if ($extension === 'csv') {
            $path = $file->getRealPath();
            $data = array_map('str_getcsv', file($path));
            $headers = array_shift($data);
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $data = $worksheet->toArray();
            $headers = array_shift($data);
        }

        $requiredCols = ['pac', 'sho', 'pas', 'dri', 'def', 'phy'];
        $indices = array_map(function ($col) use ($headers) {
            return array_search($col, array_map('strtolower', $headers));
        }, $requiredCols);

        if (in_array(false, $indices)) {
            throw new Exception('File harus berisi kolom: PAC, SHO, PAS, DRI, DEF, PHY.');
        }

        $posIndex = array_search('position', array_map('strtolower', $headers));
        $outfield = [];

        foreach ($data as $row) {
            if ($posIndex !== false && strtoupper(trim($row[$posIndex])) === 'GK') continue;

            $values = [];
            foreach ($indices as $idx) {
                $val = floatval($row[$idx]);
                if (is_nan($val)) {
                    $values = [];
                    break;
                }
                $values[] = $val;
            }
            if (count($values) === 6) {
                $outfield[] = $values;
            }
        }

        if (empty($outfield)) {
            throw new Exception('Tidak ada data pemain outfield yang valid.');
        }

        return $outfield;
    }
    
}