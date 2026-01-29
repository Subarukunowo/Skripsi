<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekomendasi Formasi Tim</title>
    <link rel="stylesheet" href="{{ asset('css/formations-display.css') }}">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Rekomendasi Formasi Sepak Bola</h1>
            <p class="subtitle">Berdasarkan analisis statistik tim Anda</p>
        </header>

        <div class="formations-grid">
            @foreach($recommendations as $index => $rec)
            <div class="formation-card">
                <div class="formation-header">
                    <h2 class="formation-name">{{ $rec['formasi'] }}</h2>
                    <span class="recommendation-badge {{ $index === 0 ? '' : ($index === 1 ? 'badge-secondary' : 'badge-tertiary') }}">
                        Rekomendasi #{{ $index + 1 }} ({{ number_format($rec['prob'] * 100, 1) }}%)
                    </span>
                </div>
                
                <div class="field">
                    <!-- Goalkeeper -->
                    @php
                        $gk = collect($startingXI)->first(fn($p) => $p['Position'] === 'GK');
                    @endphp
                    @if($gk)
                        <div class="player-container" style="top: 85%; left: 50%;">
                            <div class="player"></div>
                            <div class="player-name">{{ $gk['name'] }}</div>
                        </div>
                    @endif

                    <!-- Outfield players -->
                    @php
                        $outfield = collect($startingXI)->filter(fn($p) => $p['Position'] !== 'GK');
                        $parts = explode('-', $rec['formasi']);
                        $lines = [];
                        
                        // Kelompokkan pemain berdasarkan posisi
                        foreach ($outfield as $player) {
                            $pos = $player['Position'];
                            if (in_array($pos, ['CB','LB','RB','LWB','RWB'])) {
                                $lines[count($parts)-2][] = $player;
                            } elseif (in_array($pos, ['CM','CDM','CAM','LM','RM','LW','RW'])) {
                                $lines[count($parts)-3][] = $player;
                            } else {
                                $lines[0][] = $player; // Striker
                            }
                        }
                    @endphp

                    @for($i = 0; $i < count($parts); $i++)
                        @php
                            $lineIndex = count($parts) - 1 - $i;
                            $playersInLine = $lines[$i] ?? [];
                            $count = count($playersInLine);
                        @endphp
                        @foreach($playersInLine as $j => $player)
                            @php
                                $top = 85 - ($i + 1) * 15;
                                $left = 20 + ($j) * (60 / max(1, $count - 1));
                                if ($count == 1) $left = 50;
                            @endphp
                            <div class="player-container"style="top: {{ $top }}%; left: {{ $left }}%;">
                                <div class="player"></div>
                                <div class="player-name">{{ $player['name'] }}</div>
                            </div>
                        @endforeach
                    @endfor
                </div>

                <div class="formation-description">
                    <h3>Starting XI</h3>
                    <div class="player-list">
                        @foreach($startingXI as $player)
                        <div class="player-item">
                            <span class="player-pos-badge">{{ $player['Position'] }}</span>
                            <span class="player-full-name">{{ $player['name'] }}</span>
                            <span class="player-overall">OVR: {{ number_format($player['overall'], 1) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                @if(!empty($substitutes))
                <div class="substitutes-section">
                    <h3>Pemain Cadangan</h3>
                    <div class="player-list">
                        @foreach($substitutes as $sub)
                        <div class="player-item">
                            <span class="player-pos-badge">{{ $sub['Position'] }}</span>
                            <span class="player-full-name">{{ $sub['name'] }}</span>
                            <span class="player-overall">OVR: {{ number_format($sub['overall'], 1) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <footer class="footer">
            <p>&copy; {{ date('Y') }} Sistem Rekomendasi Formasi Sepak Bola</p>
        </footer>
    </div>
</body>
</html>