<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Pengaturan - Klasifikasi Formasi Sepak Bola</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/setting.css') }}">
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Pengaturan Akun</h1>
      <p>Kelola profil dan preferensi tampilan Anda</p>
    </div>

    <div class="content">
      <!-- Profil Pengguna -->
      <div class="profile-section">
        <h2 class="section-title">Data Diri</h2>
        <form id="profileForm">
          <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input type="text" id="name" class="form-control" value="{{ auth()->user()->name ?? 'Nama Pengguna' }}" disabled>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" class="form-control" value="{{ auth()->user()->email ?? 'email@contoh.com' }}" disabled>
          </div>
          <div class="form-group">
            <label for="role">Peran</label>
            <input type="text" id="role" class="form-control" value="{{ auth()->user()->role ?? 'user' }}" disabled>
          </div>
        </form>
      </div>

      <!-- Pengaturan Tampilan -->
      <div class="theme-section">
        <h2 class="section-title">Tema Tampilan</h2>
        <p>Pilih tema yang paling nyaman untuk Anda</p>
        <div class="theme-options">
          <div class="theme-option active" data-theme="light">
            <div class="theme-preview light-preview">Tema Terang</div>
            <p>Default</p>
          </div>
          <div class="theme-option" data-theme="dark">
            <div class="theme-preview dark-preview">Tema Gelap</div>
            <p>Mode Malam</p>
          </div>
        </div>
      </div>

      <div class="text-center mt-4">
        <button class="btn" id="saveSettings">Simpan Perubahan</button>
      </div>
    </div>
  </div>

  <div class="footer">
    Pengaturan akun Anda disimpan secara aman
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const savedTheme = localStorage.getItem('app_theme') || 'light';
      updateThemeSelection(savedTheme);

      document.querySelectorAll('.theme-option').forEach(option => {
        option.addEventListener('click', function() {
          const theme = this.dataset.theme;
          updateThemeSelection(theme);
          localStorage.setItem('app_theme', theme);
        });
      });

      document.getElementById('saveSettings').addEventListener('click', function() {
        alert('Pengaturan disimpan! Tema akan berlaku di sesi berikutnya.');
      });
    });

    function updateThemeSelection(theme) {
      document.querySelectorAll('.theme-option').forEach(option => {
        option.classList.toggle('active', option.dataset.theme === theme);
      });
    }
  </script>
</body>
</html>