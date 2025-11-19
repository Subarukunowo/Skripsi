<?php

namespace App\Services;

use Exception;

class FormasiClassifier
{
    /**
     * Dataset referensi: rata-rata statistik per formasi
     */
    protected $referenceDataset = [
        ['formasi' => '4-3-3',   'Pace' => 74, 'Shooting' => 73, 'Passing' => 75, 'Dribbling' => 75, 'Defending' => 68, 'Physical' => 70],
        ['formasi' => '3-5-2',   'Pace' => 72, 'Shooting' => 68, 'Passing' => 75, 'Dribbling' => 72, 'Defending' => 72, 'Physical' => 72],
        ['formasi' => '3-4-3',   'Pace' => 73, 'Shooting' => 71, 'Passing' => 72, 'Dribbling' => 73, 'Defending' => 65, 'Physical' => 73],
        ['formasi' => '4-2-3-1', 'Pace' => 73, 'Shooting' => 70, 'Passing' => 73, 'Dribbling' => 70, 'Defending' => 69, 'Physical' => 70],
        ['formasi' => '5-4-1',   'Pace' => 70, 'Shooting' => 65, 'Passing' => 70, 'Dribbling' => 70, 'Defending' => 78, 'Physical' => 78],
    ];

    /**
     * Hitung rata-rata dari data pemain outpost
     * Input: $players = [[74, 73, 75, 75, 68, 70], ...]
     * Output: [73.2, 71.5, ...]
     */
    public function calculateAverages(array $players): array
    {
        if (empty($players)) {
            throw new Exception('Tidak ada data pemain outfield yang valid.');
        }

        $numAttrs = 6;
        $sums = array_fill(0, $numAttrs, 0);
        $count = count($players);

        foreach ($players as $player) {
            if (count($player) !== $numAttrs) {
                throw new Exception('Setiap pemain harus memiliki 6 atribut: PAC, SHO, PAS, DRI, DEF, PHY.');
            }
            foreach ($player as $i => $value) {
                if (!is_numeric($value)) {
                    throw new Exception("Nilai atribut ke-$i tidak valid: $value");
                }
                $sums[$i] += (float) $value;
            }
        }

        return array_map(fn($sum) => $sum / $count, $sums);
    }

    /**
     * Klasifikasi formasi menggunakan Gaussian Naive Bayes
     */
    public function classify(array $averages): array
    {
        if (count($averages) !== 6) {
            throw new Exception('Input rata-rata harus berisi 6 nilai.');
        }

        $attributes = ['Pace', 'Shooting', 'Passing', 'Dribbling', 'Defending', 'Physical'];
        $variance = 1.0; // sesuai asumsi proposal
        $results = [];

        foreach ($this->referenceDataset as $ref) {
            $likelihood = 1.0;
            foreach ($attributes as $i => $attr) {
                $x = $averages[$i];
                $mean = $ref[$attr];
                // Gaussian PDF
                $pdf = (1 / sqrt(2 * M_PI * $variance)) *
                       exp(-pow($x - $mean, 2) / (2 * $variance));
                $likelihood *= $pdf;
            }
            $results[] = [
                'formasi' => $ref['formasi'],
                'prob' => $likelihood,
            ];
        }

        // Normalisasi probabilitas
        $total = array_sum(array_column($results, 'prob'));
        if ($total > 0) {
            foreach ($results as &$r) {
                $r['prob'] = $r['prob'] / $total;
            }
        }

        // Urutkan descending
        usort($results, fn($a, $b) => $b['prob'] <=> $a['prob']);

        // Ambil 3 teratas
        return array_slice($results, 0, 3);
    }

    /**
     * Ambil dataset referensi
     */
    public function getReferenceDataset()
    {
        return $this->referenceDataset;
    }
}