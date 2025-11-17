<?php

namespace App\Http\Controllers;

use App\Services\FormasiClassifier;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class FormasiController extends Controller
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

            // Simpan sementara di session untuk download PDF
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