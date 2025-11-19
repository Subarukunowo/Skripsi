<table border="1" style="border-collapse: collapse; width: 100%;">
    <tr>
        <td colspan="2" style="font-size: 18px; font-weight: bold; text-align: center; background-color: #0ea5e9; color: white;">
            LAPORAN ANALISIS KLASIFIKASI FORMASI SEPAK BOLA
        </td>
    </tr>
    <tr>
        <td colspan="2" style="font-size: 14px; text-align: center; background-color: #f1f5f9; color: #475569;">
            Formasi Sepak Bola Berdasarkan Statistik Tim
        </td>
    </tr>

    <tr><td colspan="2">&nbsp;</td></tr>

    <!-- Formasi Terpilih -->
    <tr>
        <td colspan="2" style="font-size: 14px; font-weight: bold; background-color: #0ea5e9; color: white;">
            Formasi Terpilih
        </td>
    </tr>
    @foreach($recommendations as $index => $rec)
        <tr>
            <td style="font-weight: bold; color: #10b981;">{{ $rec['formasi'] }}</td>
            <td>{{ $descriptions[$rec['formasi']] ?? 'Deskripsi tidak tersedia.' }}</td>
        </tr>
    @endforeach

    <tr><td colspan="2">&nbsp;</td></tr>

    <!-- Info Box -->
    <tr>
        <td colspan="2" style="background-color: #e0f2fe; border: 1px solid #0ea5e9;">
            <strong>Metode Klasifikasi:</strong> Gaussian Naive Bayes | 
            <strong>Probabilitas Tertinggi:</strong> {{ number_format($recommendations[0]['prob'] * 100, 2) }}%
        </td>
    </tr>

    <tr><td colspan="2">&nbsp;</td></tr>

    <!-- Rata-rata Statistik Tim -->
    <tr>
        <td colspan="2" style="font-size: 14px; font-weight: bold; background-color: #0ea5e9; color: white;">
            Rata-rata Statistik Tim
        </td>
    </tr>
    <tr>
        <td>Pace</td>
        <td style="font-weight: bold; color: #10b981;">{{ number_format($averages[0], 2) }}</td>
    </tr>
    <tr>
        <td>Shooting</td>
        <td style="font-weight: bold; color: #10b981;">{{ number_format($averages[1], 2) }}</td>
    </tr>
    <tr>
        <td>Passing</td>
        <td style="font-weight: bold; color: #10b981;">{{ number_format($averages[2], 2) }}</td>
    </tr>
    <tr>
        <td>Dribbling</td>
        <td style="font-weight: bold; color: #10b981;">{{ number_format($averages[3], 2) }}</td>
    </tr>
    <tr>
        <td>Defending</td>
        <td style="font-weight: bold; color: #10b981;">{{ number_format($averages[4], 2) }}</td>
    </tr>
    <tr>
        <td>Physical</td>
        <td style="font-weight: bold; color: #10b981;">{{ number_format($averages[5], 2) }}</td>
    </tr>

    <tr><td colspan="2">&nbsp;</td></tr>

    <!-- Referensi Formasi -->
    <tr>
        <td colspan="2" style="font-size: 14px; font-weight: bold; background-color: #0ea5e9; color: white;">
            Referensi Formasi dari Dataset
        </td>
    </tr>
    <tr style="background-color: #0ea5e9; color: white; font-weight: bold;">
        <td>No</td>
        <td>Formasi</td>
        <td>Pace</td>
        <td>Shooting</td>
        <td>Passing</td>
        <td>Dribbling</td>
        <td>Defending</td>
        <td>Physical</td>
    </tr>
    @foreach($referenceDataset as $index => $ref)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $ref['formasi'] }}</td>
            <td>{{ $ref['Pace'] }}</td>
            <td>{{ $ref['Shooting'] }}</td>
            <td>{{ $ref['Passing'] }}</td>
            <td>{{ $ref['Dribbling'] }}</td>
            <td>{{ $ref['Defending'] }}</td>
            <td>{{ $ref['Physical'] }}</td>
        </tr>
    @endforeach

    <tr><td colspan="2">&nbsp;</td></tr>

    <!-- Footer -->
    <tr>
        <td colspan="2" style="font-size: 10px; text-align: center; background-color: #e2e8f0; color: #64748b;">
            &copy; 2025 Klasifikasi Formasi Sepak Bola. All Rights Reserved.<br>
            Laporan digenerate pada: {{ date('d F Y, H:i:s') }}
        </td>
    </tr>
</table>