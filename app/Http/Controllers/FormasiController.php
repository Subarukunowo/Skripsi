<?php

namespace App\Http\Controllers;

use App\Services\FormasiClassifier;
use Illuminate\Http\Request;
use Exception;

class FormasiController extends Controller
{
    public function analyze(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls'
        ]);

        try {
            // Di sini kamu proses file (CSV/XLSX) → ubah ke array $players
            // Contoh dummy:
            $players = [
                [74, 73, 75, 75, 68, 70],
                [72, 68, 75, 72, 72, 72],
                // ... data lain
            ];

            $classifier = new FormasiClassifier();
            $averages = $classifier->calculateAverages($players);
            $recommendations = $classifier->classify($averages);

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
}