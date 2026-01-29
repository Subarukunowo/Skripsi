<?php

namespace App\Http\Controllers;

use App\Services\FormasiClassifier;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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
        // 1️⃣ Parse file pemain
        $players = $this->parseFileToArray($request->file('file'));

        $classifier = new FormasiClassifier();

        // 2️⃣ Hitung rata-rata atribut tim
        $averages = $classifier->calculateAverages($players);

        // 3️⃣ ML → TOP 3 rekomendasi (ARRAY)
        $recommendations = $classifier->predictFormationWithML($averages);

        if (empty($recommendations)) {
            throw new Exception('ML tidak mengembalikan rekomendasi');
        }

        // 4️⃣ Ambil rekomendasi TERBAIK
        $bestFormation = $recommendations[0]['formasi'];
        $confidence    = $recommendations[0]['probability'];

        // 5️⃣ Simpan data training (pakai BEST saja)
        $this->storeTrainingData($averages, [
            'formasi'     => $bestFormation,
            'probability' => $confidence
        ]);

        // 6️⃣ (Opsional) retrain model
        $this->retrainModel();

        // 7️⃣ Starting XI & Cadangan berdasarkan BEST formation
        $startingXI  = $classifier->selectStartingEleven($players, $bestFormation);
        $substitutes = $classifier->suggestSubstitutes($players, $startingXI);

        // 8️⃣ Simpan ke session
        session([
            'recommendations' => $recommendations,
            'startingXI'      => $startingXI,
            'substitutes'     => $substitutes,
            'averages'        => $averages,
            'bestFormation'   => $bestFormation,
        ]);

        // 9️⃣ Response JSON ke frontend
        return response()->json([
            'success'         => true,
            'formation'       => $bestFormation,
            'confidence'      => $confidence,
            'recommendations' => $recommendations,
            'startingXI'      => $startingXI,
            'substitutes'     => $substitutes,
        ]);

    } catch (Exception $e) {
        Log::error('Analisis Formasi Gagal: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 400);
    }
}


    private function storeTrainingData(array $averages, array $result): void
    {
        DB::table('training_formations')->insert([
            'pace_avg'       => $averages['Pace'],
            'shooting_avg'   => $averages['Shooting'],
            'passing_avg'    => $averages['Passing'],
            'dribbling_avg'  => $averages['Dribbling'],
            'defending_avg'  => $averages['Defending'],
            'physical_avg'   => $averages['Physical'],

            'formation'      => (string) $result['formasi'],
            'confidence'     => $result['probability'] ?? 0,
            'source'         => 'user',

            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    private function parseFileToArray($file): array
    {
        $ext = strtolower($file->getClientOriginalExtension());

        if ($ext === 'csv') {
            $rows = array_map('str_getcsv', file($file->getRealPath()));
        } else {
            $reader = new XlsxReader();
            $spreadsheet = $reader->load($file->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray();
        }

        if (count($rows) < 2) {
            throw new Exception('File tidak berisi data pemain.');
        }

        $headers = array_map(fn($h) => strtolower(trim($h)), array_shift($rows));
        $required = ['name','position','pace','shooting','passing','dribbling','defending','physical'];

        foreach ($required as $col) {
            if (!in_array($col, $headers)) {
                throw new Exception("Kolom '$col' tidak ditemukan.");
            }
        }

        $idx = array_flip($headers);
        $players = [];

        foreach ($rows as $row) {
            if (empty(array_filter($row))) continue;

            $players[] = [
                'name'      => $row[$idx['name']] ?? 'Pemain',
                'Position'  => strtoupper(trim($row[$idx['position']] ?? 'CM')),
                'Pace'      => (float) $row[$idx['pace']],
                'Shooting'  => (float) $row[$idx['shooting']],
                'Passing'   => (float) $row[$idx['passing']],
                'Dribbling' => (float) $row[$idx['dribbling']],
                'Defending' => (float) $row[$idx['defending']],
                'Physical'  => (float) $row[$idx['physical']],
            ];
        }

        return $players;
    }

    public function downloadPDF()
    {
        $data = session()->only([
            'recommendations',
            'averages',
            'startingXI',
            'substitutes',
            'bestFormation'
        ]);

        if (empty($data['recommendations'])) {
            return redirect()->back()
                ->with('error', 'Silakan lakukan analisis terlebih dahulu.');
        }

        return Pdf::loadView('pdf.formation-report', $data)
            ->download('laporan-formasi.pdf');
    }

    private function retrainModel(): void
    {
        $python = 'python';
        $script = base_path('python/train_model.py');

        exec("$python $script", $output, $code);

        if ($code !== 0) {
            Log::error('Retrain model gagal', $output);
        }
    }
}
