<?php

namespace App\Services;
use Illuminate\Support\Facades\Log;
use Exception;

class FormasiClassifier
{
    protected $referenceDataset = [
        ['formasi' => '3-5-2',   'Pace' => 70, 'Shooting' => 68, 'Passing' => 73, 'Dribbling' => 72, 'Defending' => 72, 'Physical' => 70],
        ['formasi' => '3-4-3',   'Pace' => 70, 'Shooting' => 71, 'Passing' => 72, 'Dribbling' => 73, 'Defending' => 65, 'Physical' => 73],
        ['formasi' => '4-2-3-1', 'Pace' => 73, 'Shooting' => 70, 'Passing' => 73, 'Dribbling' => 70, 'Defending' => 69, 'Physical' => 72],
        ['formasi' => '5-4-1',   'Pace' => 70, 'Shooting' => 65, 'Passing' => 70, 'Dribbling' => 70, 'Defending' => 78, 'Physical' => 78],
        ['formasi' => '4-4-2',   'Pace' => 73, 'Shooting' => 70, 'Passing' => 72, 'Dribbling' => 73, 'Defending' => 67, 'Physical' => 70],
        ['formasi' => '4-3-3',   'Pace' => 74, 'Shooting' => 73, 'Passing' => 75, 'Dribbling' => 75, 'Defending' => 68, 'Physical' => 70],
    ];

protected $formationMap = [
    '4-3-3' => [
        'GK' => 1,
        // Bek
        'CB' => 2, 'LB' => 1, 'RB' => 1,
        // Gelandang (gabungkan CM, CDM, CAM, LM, RM)
        'CM' => 1, 'CDM' => 1, 'CAM' => 1,
        // Sayap & Penyerang
        'LW' => 1, 'RW' => 1, 'ST' => 1
    ],
    '4-4-2' => [
        'GK' => 1,
        'CB' => 2, 'LB' => 1, 'RB' => 1,
        'CM' => 2, 'LM' => 1, 'RM' => 1,
        'ST' => 2
    ],
    '4-2-3-1' => [
        'GK' => 1,
        'CB' => 2, 'LB' => 1, 'RB' => 1,
        'CDM' => 2,
        'CAM' => 1, 'LW' => 1, 'RW' => 1,
        'ST' => 1
    ],
    '3-5-2' => [
        'GK' => 1,
        'CB' => 3,
        'CM' => 3, 'LM' => 1, 'RM' => 1,
        'ST' => 2
    ],
    '3-4-3' => [
        'GK' => 1,
        'CB' => 3,
        'CM' => 2, 'LW' => 1, 'RW' => 1,        ['formasi' => '4-3-3',   'Pace' => 74, 'Shooting' => 73, 'Passing' => 75, 'Dribbling' => 75, 'Defending' => 68, 'Physical' => 70],

        'ST' => 3
    ],
    '5-4-1' => [
        'GK' => 1,
        'CB' => 3, 'LB' => 1, 'RB' => 1,
        'CM' => 2, 'LM' => 1, 'RM' => 1,
        'ST' => 1
    ],
];
   public function calculateAverages(array $players): array
{
    $total = [
        'Pace' => 0,
        'Shooting' => 0,
        'Passing' => 0,
        'Dribbling' => 0,
        'Defending' => 0,
        'Physical' => 0,
    ];

    $count = 0;

    foreach ($players as $p) {
        if (($p['Position'] ?? '') === 'GK') continue;

        foreach ($total as $key => $_) {
            if (!isset($p[$key])) continue 2;
            $total[$key] += $p[$key];
        }
        $count++;
    }

    if ($count === 0) {
        throw new Exception('Tidak ada pemain outfield valid.');
    }

    foreach ($total as $k => $v) {
        $total[$k] = round($v / $count, 2);
    }

    return $total;
}

public function classify(array $averages): array
{
    $scores = [];

    foreach ($this->formationMap as $formation => $rules) {
        $scores[] = [
            'formasi' => $formation,
            'score'   => $this->calculateScore($averages, $rules)
        ];
    }

    usort($scores, fn($a, $b) => $b['score'] <=> $a['score']);

    return $scores; // ← NUMERIC ARRAY
}

    protected function calculateScore(array $averages, array $rules): float
    {
        $score = 0;
        $attributeWeights = [
            'Pace' => 0.15,
            'Shooting' => 0.15,
            'Passing' => 0.20,
            'Dribbling' => 0.15,
            'Defending' => 0.20,
            'Physical' => 0.15,
        ];

        foreach ($attributeWeights as $attribute => $weight) {
            if (isset($averages[$attribute])) {
                $score += ($averages[$attribute] / 100) * $weight;
            }
        }

        return round($score * 100, 2);
    }

    protected function overall(array $player): float
    {
        return array_sum([
            $player['Pace'], $player['Shooting'], $player['Passing'],
            $player['Dribbling'], $player['Defending'], $player['Physical']
        ]) / 6;
    }

   public function selectStartingEleven(array $players, string $formation): array
{
    $map = $this->formationMap[$formation] ?? [];
    $starting = [];
    $alreadyPicked = []; // Untuk melacak pemain yang sudah terpilih agar tidak double

    Log::info("--- PROSES SELEKSI STARTING XI: Formasi $formation ---");

    // Definisi fallback posisi (Hirarki pencarian)
    $fallbacks = [
        'LW'  => ['LM','CAM','RW'],
        'RW'  => ['RM', 'CAM', 'LW'],
        'CM'  => ['CAM', 'CDM', 'LM', 'RM'],
        'CAM' => ['CM', 'LW', 'RW'],
        'CDM' => ['CM', 'CB'],
        'CB'  => ['CDM', 'LB', 'RB'],
        'LB'  => ['LWB', 'CB', 'RB'],
        'RB'  => ['RWB', 'CB', 'LB'],
        'LWB' => ['LB', 'CB'],
        'RWB' => ['RB', 'CB'],
        'LM'  => ['LW', 'LB'],
        'RM'  => ['RW', 'RB'],
        'ST'  => ['CF', 'LW', 'RW', 'CAM']
    ];

    foreach ($map as $position => $needed) {
        for ($i = 0; $i < $needed; $i++) {
            $found = false;
            
            // 1. Coba cari posisi utama + semua alternatifnya
            $searchPositions = array_merge([$position], $fallbacks[$position] ?? []);
            
            foreach ($searchPositions as $posToTry) {
                // Filter kandidat yang posisinya cocok DAN belum terpilih
                $candidates = array_filter($players, function($p) use ($posToTry, $alreadyPicked) {
                    return ($p['Position'] ?? '') === $posToTry && !in_array($p['name'], $alreadyPicked);
                });

                if (!empty($candidates)) {
                    // Urutkan berdasarkan OVR tertinggi
                    usort($candidates, fn($a, $b) => $this->overall($b) <=> $this->overall($a));
                    
                    $bestPlayer = $candidates[0];
                    $starting[] = $bestPlayer;
                    $alreadyPicked[] = $bestPlayer['name'];
                    
                    Log::debug(sprintf(
                        "Terpilih untuk slot [%s]: %s (Posisi Asli: %s, OVR: %.2f)",
                        $position,
                        $bestPlayer['name'],
                        $bestPlayer['Position'],
                        $this->overall($bestPlayer)
                    ));
                    
                    $found = true;
                    break; // Keluar dari loop searchPositions karena sudah dapat 1 pemain
                }
            }

            if (!$found) {
                Log::warning("Peringatan: Tidak ada pemain tersedia untuk posisi $position (termasuk fallback).");
            }
        }
    }

    return $starting;
}
 public function suggestSubstitutes(array $players, array $startingXI): array
{
    // Filter pemain yang tidak di starting XI dan bukan GK
    $bench = array_filter($players, function ($p) use ($startingXI) {
        // Cek berdasarkan nama, bukan referensi objek
        foreach ($startingXI as $starter) {
            if (($p['name'] ?? '') === ($starter['name'] ?? '')) {
                return false;
            }
        }
        return ($p['Position'] ?? '') !== 'GK';
    });

    // Urutkan berdasarkan overall yang dihitung, BUKAN dari kunci 'overall'
    usort($bench, function ($a, $b) {
        $overallA = $this->overall($a);
        $overallB = $this->overall($b);
        return $overallB <=> $overallA;
    });

    return array_slice($bench, 0, 5);
}
public function predictFormationWithML(array $averages): array
{
    $python = 'python';
    $script = base_path('python/predict_formation.py');

    $cmd = sprintf(
        '%s %s %s %s %s %s %s %s',
        $python,
        escapeshellarg($script),
        $averages['Pace'],
        $averages['Shooting'],
        $averages['Passing'],
        $averages['Dribbling'],
        $averages['Defending'],
        $averages['Physical']
    );

    $raw = shell_exec($cmd);

    Log::info('ML OUTPUT', ['output' => $raw]);

    $data = json_decode(trim($raw), true);

    if (!is_array($data)) {
        throw new Exception('Output ML bukan JSON valid');
    }

    // Validasi isi
    foreach ($data as $item) {
        if (!isset($item['formasi'], $item['probability'])) {
            throw new Exception('Struktur data ML salah');
        }
    }

    // Ambil TOP 3
    return array_slice($data, 0, 3);
}

}