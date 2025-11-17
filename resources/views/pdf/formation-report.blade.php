<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Analisis Klasifikasi Formasi Sepak Bola</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #1e293b;
            line-height: 1.6;
            padding: 20px;
            margin: 0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #0ea5e9;
        }
        
        .header h1 {
            font-size: 20px;
            color: #0ea5e9;
            margin: 0;
            font-weight: bold;
        }
        
        .header .subtitle {
            font-size: 14px;
            color: #64748b;
            font-weight: normal;
            margin-top: 5px;
        }
        
        .section {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #0ea5e9;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .formation-box {
            background-color: #f8fafc;
            padding: 12px;
            margin-bottom: 15px;
            border-left: 4px solid #10b981;
        }
        
        .formation-name {
            font-size: 16px;
            font-weight: bold;
            color: #10b981;
            margin-bottom: 5px;
        }
        
        .formation-description {
            font-size: 11px;
            color: #475569;
            line-height: 1.5;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 11px;
        }
        
        th {
            background-color: #0ea5e9;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #0284c7;
        }
        
        td {
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
        }
        
        .stats-value {
            font-weight: bold;
            color: #10b981;
        }
        
        .info-box {
            background-color: #f0f9ff;
            border: 1px solid #0ea5e9;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        
        .info-box strong {
            color: #0ea5e9;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            font-size: 10px;
            color: #94a3b8;
        }
        
        .footer .copyright {
            margin-bottom: 3px;
        }
        
        .footer .generated {
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN ANALISIS KLASIFIKASI</h1>
        <div class="subtitle">Formasi Sepak Bola Berdasarkan Statistik Tim</div>
    </div>

    <!-- Formasi Terpilih -->
    <div class="section">
        <h2 class="section-title">Formasi Terpilih</h2>
        @foreach($recommendations as $index => $rec)
            <div class="formation-box">
                <div class="formation-name">{{ $rec['formasi'] }}</div>
                <p class="formation-description">
                    {{ $descriptions[$rec['formasi']] ?? 'Deskripsi tidak tersedia.' }}
                </p>
            </div>
        @endforeach
    </div>

    <!-- Info Box -->
    <div class="info-box">
        <strong>Metode Klasifikasi:</strong> Gaussian Naive Bayes | 
        <strong>Probabilitas Tertinggi:</strong> {{ number_format($recommendations[0]['prob'] * 100, 2) }}%
    </div>

    <!-- Rata-rata Statistik Tim -->
    <div class="section">
        <h2 class="section-title">Rata-rata Statistik Tim</h2>
        <table>
            <thead>
                <tr>
                    <th>Atribut</th>
                    <th>Nilai Rata-rata</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>Pace</td><td class="stats-value">{{ number_format($averages[0], 2) }}</td></tr>
                <tr><td>Shooting</td><td class="stats-value">{{ number_format($averages[1], 2) }}</td></tr>
                <tr><td>Passing</td><td class="stats-value">{{ number_format($averages[2], 2) }}</td></tr>
                <tr><td>Dribbling</td><td class="stats-value">{{ number_format($averages[3], 2) }}</td></tr>
                <tr><td>Defending</td><td class="stats-value">{{ number_format($averages[4], 2) }}</td></tr>
                <tr><td>Physical</td><td class="stats-value">{{ number_format($averages[5], 2) }}</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Referensi Formasi -->
    <div class="section">
        <h2 class="section-title">Referensi Formasi dari Dataset</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Formasi</th>
                    <th>Pace</th>
                    <th>Shooting</th>
                    <th>Passing</th>
                    <th>Dribbling</th>
                    <th>Defending</th>
                    <th>Physical</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $referenceDataset = [
                        ['formasi' => '4-3-3', 'Pace' => 74, 'Shooting' => 73, 'Passing' => 75, 'Dribbling' => 75, 'Defending' => 68, 'Physical' => 70],
                        ['formasi' => '3-5-2', 'Pace' => 72, 'Shooting' => 68, 'Passing' => 75, 'Dribbling' => 72, 'Defending' => 72, 'Physical' => 72],
                        ['formasi' => '3-4-3', 'Pace' => 73, 'Shooting' => 71, 'Passing' => 72, 'Dribbling' => 73, 'Defending' => 65, 'Physical' => 73],
                        ['formasi' => '4-2-3-1', 'Pace' => 73, 'Shooting' => 70, 'Passing' => 73, 'Dribbling' => 70, 'Defending' => 69, 'Physical' => 70],
                        ['formasi' => '5-4-1', 'Pace' => 70, 'Shooting' => 65, 'Passing' => 70, 'Dribbling' => 70, 'Defending' => 78, 'Physical' => 78],
                    ];
                @endphp
                @foreach($referenceDataset as $index => $ref)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="formation-code">{{ $ref['formasi'] }}</td>
                        <td>{{ $ref['Pace'] }}</td>
                        <td>{{ $ref['Shooting'] }}</td>
                        <td>{{ $ref['Passing'] }}</td>
                        <td>{{ $ref['Dribbling'] }}</td>
                        <td>{{ $ref['Defending'] }}</td>
                        <td>{{ $ref['Physical'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="copyright">
            &copy; 2025 Klasifikasi Formasi Sepak Bola. All Rights Reserved.
        </div>
        <div class="generated">
            Laporan digenerate pada: {{ date('d F Y, H:i:s') }}
        </div>
    </div>
</body>
</html>