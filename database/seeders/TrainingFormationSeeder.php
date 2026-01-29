<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainingFormationSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/finaltim15.csv');

        if (!file_exists($path)) {
            dd("CSV tidak ditemukan: " . $path);
        }

        $handle = fopen($path, 'r');
        $header = fgetcsv($handle); // baca header CSV

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);

            DB::table('training_formations')->insert([
                'pace_avg'       => (float) $data['PAC'],
                'shooting_avg'   => (float) $data['SHO'],
                'passing_avg'    => (float) $data['PAS'],
                'dribbling_avg'  => (float) $data['DRI'],
                'defending_avg'  => (float) $data['DEF'],
                'physical_avg'   => (float) $data['PHY'],
                'formation'      => trim($data['Formasi']),
                'source'         => 'baseline',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        fclose($handle);
    }
}
