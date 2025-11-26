@extends('layouts.app')

@section('content')
<style>
    .dashboard-bg {
        background-color: #2c3e50;
        color: white;
        min-height: 100vh;
        padding: 20px 0;
    }
    .dashboard-header {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #3498db;
    }
    .dashboard-header h1 {
        font-weight: bold;
        font-size: 28px;
        margin: 0;
    }
    .dashboard-header .subtitle {
        font-size: 18px;
        opacity: 0.9;
        margin-top: 5px;
    }
    .stat-card {
        background: rgba(255, 255, 255, 0.08);
        border-radius: 8px;
        padding: 12px;
        text-align: center;
        margin-bottom: 12px;
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        background: rgba(255, 255, 255, 0.12);
    }
    .stat-value {
        font-size: 20px;
        font-weight: bold;
        color: #1abc9c;
    }
    .stat-label {
        font-size: 12px;
        opacity: 0.8;
    }
    .recommended-badge {
        background-color: #2ecc71;
        color: white;
        padding: 6px 16px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 18px;
        display: inline-block;
        margin: 10px 0;
        box-shadow: 0 2px 6px rgba(46, 204, 113, 0.3);
    }
    .table-dark-custom {
        background: rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(5px);
    }
    .table th {
        background-color: rgba(52, 152, 219, 0.3) !important;
        color: white;
    }
    .nav-tabs .nav-link {
        color: #bdc3c7;
        border: none;
    }
    .nav-tabs .nav-link.active {
        color: white;
        background-color: rgba(52, 152, 219, 0.4);
        border-radius: 5px;
    }
    .formation-badge {
        background-color: #27ae60;
        color: white;
        padding: 4px 10px;
        border-radius: 12px;
        font-weight: bold;
        font-size: 14px;
    }
</style>

<div class="dashboard-bg">
    <div class="container">
        <!-- Navigasi Tab -->
        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" data-bs-target="#dashboard" type="button" role="tab">Dashboard Formasi</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="input-tab" data-bs-toggle="tab" data-bs-target="#input" type="button" role="tab">Input Data</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">Riwayat Analisis</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="about-tab" data-bs-toggle="tab" data-bs-target="#about" type="button" role="tab">Tentang</button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Tab Dashboard Formasi -->
            <div class="tab-pane fade show active" id="dashboard" role="tabpanel">
                <!-- Header -->
                <div class="dashboard-header">
                    <h1>DASHBOARD KLASIFIKASI FORMASI</h1>
                    <div class="subtitle">TIM: {{ $teamName }} | Musim: Musim 2024/2025</div>
                </div>

                <div class="row">
                    <!-- Ringkasan Statistik Tim (Kiri Atas) -->
                    <div class="col-md-4 mb-4">
                        <h5 class="mb-3">Ringkasan Statistik Tim</h5>
                        <div class="stat-card"><div class="stat-label">Pace</div><div class="stat-value">{{ number_format($avgPace, 2) }}</div></div>
                        <div class="stat-card"><div class="stat-label">Shooting</div><div class="stat-value">{{ number_format($avgShooting, 2) }}</div></div>
                        <div class="stat-card"><div class="stat-label">Passing</div><div class="stat-value">{{ number_format($avgPassing, 2) }}</div></div>
                        <div class="stat-card"><div class="stat-label">Dribbling</div><div class="stat-value">{{ number_format($avgDribbling, 2) }}</div></div>
                        <div class="stat-card"><div class="stat-label">Defending</div><div class="stat-value">{{ number_format($avgDefending, 2) }}</div></div>
                        <div class="stat-card"><div class="stat-label">Physical</div><div class="stat-value">{{ number_format($avgPhysical, 2) }}</div></div>
                    </div>

                    <!-- Rekomendasi Formasi (Tengah Atas) -->
                    <div class="col-md-8 mb-4">
                        <h5>Rekomendasi Formasi</h5>
                        <div class="recommended-badge">
                            Formasi Terbaik: {{ $recommendedFormation }}
                        </div>
                        <p class="text-muted">Metode: Gaussian Naïve Bayes | Probabilitas: {{ number_format($recommendations[0]['prob'] * 100, 2) }}%</p>

                        <!-- Visualisasi Formasi (Diagram Sederhana) -->
                        <div class="mt-4">
                            <h6>Diagram Formasi: {{ $recommendedFormation }}</h6>
                            <div class="formation-visual" style="display: flex; flex-direction: column; align-items: center; gap: 15px; margin-top: 10px;">
                                @php
                                    $parts = explode('-', $recommendedFormation);
                                    $positions = ['GK'];
                                    if (count($parts) === 3) {
                                        $positions = array_merge($positions, array_fill(0, $parts[0], 'DF'), array_fill(0, $parts[1], 'MF'), array_fill(0, $parts[2], 'FW'));
                                    } elseif (count($parts) === 4) {
                                        $positions = array_merge($positions, array_fill(0, $parts[0], 'DF'), array_fill(0, $parts[1], 'MF'), array_fill(0, $parts[2], 'AMF'), array_fill(0, $parts[3], 'FW'));
                                    }
                                    $rows = [];
                                    $rows[] = ['GK'];
                                    if (count($parts) === 3) {
                                        $rows[] = array_fill(0, $parts[2], 'FW');
                                        $rows[] = array_fill(0, $parts[1], 'MF');
                                        $rows[] = array_fill(0, $parts[0], 'DF');
                                    } else {
                                        $rows[] = array_fill(0, $parts[3], 'FW');
                                        $rows[] = array_fill(0, $parts[2], 'AMF');
                                        $rows[] = array_fill(0, $parts[1], 'MF');
                                        $rows[] = array_fill(0, $parts[0], 'DF');
                                    }
                                @endphp

                                @foreach(array_reverse($rows) as $row)
                                    <div style="display: flex; gap: 10px;">
                                        @foreach($row as $pos)
                                            <div style="width: 40px; height: 40px; background: #3498db; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px;">
                                                {{ $pos }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Daftar Pemain -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h5>Daftar Pemain</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-dark-custom">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Posisi</th>
                                        <th>Pace</th>
                                        <th>Shooting</th>
                                        <th>Passing</th>
                                        <th>Dribbling</th>
                                        <th>Defending</th>
                                        <th>Physical</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($players as $player)
                                        <tr>
                                            <td>{{ $player->name ?? 'Pemain ' . ($loop->index + 1) }}</td>
                                            <td>{{ $player->position ?? 'MF' }}</td>
                                            <td>{{ number_format($player->pace, 2) }}</td>
                                            <td>{{ number_format($player->shooting, 2) }}</td>
                                            <td>{{ number_format($player->passing, 2) }}</td>
                                            <td>{{ number_format($player->dribbling, 2) }}</td>
                                            <td>{{ number_format($player->defending, 2) }}</td>
                                            <td>{{ number_format($player->physical, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Input Data -->
            <div class="tab-pane fade" id="input" role="tabpanel">
                <div class="text-center py-5">
                    <h4>Upload Data Tim Anda</h4>
                    <p>Unggah file CSV/XLSX berisi statistik pemain untuk menganalisis formasi terbaik.</p>
                    <a href="{{ route('formation.index') }}" class="btn btn-primary">Kembali ke Halaman Utama</a>
                </div>
            </div>

            <!-- Tab Riwayat Analisis -->
            <div class="tab-pane fade" id="history" role="tabpanel">
                <div class="text-center py-5">
                    <h4>Riwayat Analisis</h4>
                    <p>Belum ada riwayat. Lakukan analisis pertama Anda!</p>
                </div>
            </div>

            <!-- Tab Tentang -->
            <div class="tab-pane fade" id="about" role="tabpanel">
                <div class="py-4">
                    <h4>Tentang Sistem Ini</h4>
                    <p>Sistem ini menggunakan metode <strong>Gaussian Naïve Bayes</strong> untuk merekomendasikan formasi sepak bola berdasarkan rata-rata statistik tim.</p>
                    <p>Formasi referensi berasal dari dataset standar industri.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection