TYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekomendasi Formasi Tim</title>
    <link rel="stylesheet" href="formations-display.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Rekomendasi Formasi Sepak Bola</h1>
            <p class="subtitle">Berdasarkan analisis statistik tim Anda</p>
        </header>

        <div class="formations-grid">
            <!-- Formasi 3-4-3 -->
            <div class="formation-card">
                <div class="formation-header">
                    <h2 class="formation-name">3-4-3</h2>
                    <span class="recommendation-badge">Rekomendasi #1</span>
                </div>
                <div class="field">
                    <!-- Goalkeeper -->
                    <div class="player" style="top: 85%; left: 50%;"></div>
                    
                    <!-- Defenders (3) -->
                    <div class="player" style="top: 68%; left: 25%;"></div>
                    <div class="player" style="top: 68%; left: 50%;"></div>
                    <div class="player" style="top: 68%; left: 75%;"></div>
                    
                    <!-- Midfielders (4) -->
                    <div class="player" style="top: 45%; left: 17%;"></div>
                    <div class="player" style="top: 45%; left: 40%;"></div>
                    <div class="player" style="top: 45%; left: 60%;"></div>
                    <div class="player" style="top: 45%; left: 83%;"></div>
                    
                    <!-- Forwards (3) -->
                    <div class="player" style="top: 20%; left: 25%;"></div>
                    <div class="player" style="top: 20%; left: 50%;"></div>
                    <div class="player" style="top: 20%; left: 75%;"></div>
                </div>
                <div class="formation-description">
                    <h3>Mengapa Formasi Ini?</h3>
                    <p>Formasi 3-4-3 sangat cocok untuk tim dengan kekuatan menyerang tinggi. Dengan 3 striker, tim Anda dapat memberikan tekanan konstan ke lini pertahanan lawan. 4 gelandang memberikan fleksibilitas untuk mengontrol tengah lapangan dan mendukung serangan sayap.</p>
                </div>
            </div>

            <!-- Formasi 4-3-3 -->
            <div class="formation-card">
                <div class="formation-header">
                    <h2 class="formation-name">4-3-3</h2>
                    <span class="recommendation-badge badge-secondary">Rekomendasi #2</span>
                </div>
                <div class="field">
                    <!-- Goalkeeper -->
                    <div class="player" style="top: 85%; left: 50%;"></div>
                    
                    <!-- Defenders (4) -->
                    <div class="player" style="top: 68%; left: 17%;"></div>
                    <div class="player" style="top: 68%; left: 40%;"></div>
                    <div class="player" style="top: 68%; left: 60%;"></div>
                    <div class="player" style="top: 68%; left: 83%;"></div>
                    
                    <!-- Midfielders (3) -->
                    <div class="player" style="top: 45%; left: 30%;"></div>
                    <div class="player" style="top: 45%; left: 50%;"></div>
                    <div class="player" style="top: 45%; left: 70%;"></div>
                    
                    <!-- Forwards (3) -->
                    <div class="player" style="top: 20%; left: 25%;"></div>
                    <div class="player" style="top: 20%; left: 50%;"></div>
                    <div class="player" style="top: 20%; left: 75%;"></div>
                </div>
                <div class="formation-description">
                    <h3>Mengapa Formasi Ini?</h3>
                    <p>Formasi 4-3-3 adalah pilihan seimbang yang memberikan stabilitas defensif dengan 4 bek. 3 gelandang menciptakan kontrol di tengah lapangan, sementara 3 penyerang memberikan ancaman di lini depan. Ideal untuk tim yang membutuhkan keseimbangan antara bertahan dan menyerang.</p>
                </div>
            </div>

            <!-- Formasi 4-2-3-1 -->
            <div class="formation-card">
                <div class="formation-header">
                    <h2 class="formation-name">4-2-3-1</h2>
                    <span class="recommendation-badge badge-tertiary">Rekomendasi #3</span>
                </div>
                <div class="field">
                    <!-- Goalkeeper -->
                    <div class="player" style="top: 85%; left: 50%;"></div>
                    
                    <!-- Defenders (4) -->
                    <div class="player" style="top: 68%; left: 17%;"></div>
                    <div class="player" style="top: 68%; left: 40%;"></div>
                    <div class="player" style="top: 68%; left: 60%;"></div>
                    <div class="player" style="top: 68%; left: 83%;"></div>
                    
                    <!-- Defensive Midfielders (2) -->
                    <div class="player" style="top: 52%; left: 38%;"></div>
                    <div class="player" style="top: 52%; left: 62%;"></div>
                    
                    <!-- Attacking Midfielders (3) -->
                    <div class="player" style="top: 33%; left: 25%;"></div>
                    <div class="player" style="top: 33%; left: 50%;"></div>
                    <div class="player" style="top: 33%; left: 75%;"></div>
                    
                    <!-- Forward (1) -->
                    <div class="player" style="top: 15%; left: 50%;"></div>
                </div>
                <div class="formation-description">
                    <h3>Mengapa Formasi Ini?</h3>
                    <p>Formasi 4-2-3-1 sangat efektif untuk tim yang ingin mengontrol permainan. 2 gelandang bertahan memberikan perlindungan ekstra untuk pertahanan, sementara 3 gelandang serang dan 1 striker murni menciptakan peluang kreatif. Cocok untuk tim dengan pemain teknis yang bagus.</p>
                </div>
            </div>
        </div>

        <footer class="footer">
            <p>&copy; {{ date('Y') }} Sistem Rekomendasi Formasi Sepak Bola</p>
        </footer>
    </div>
</body>
</html>