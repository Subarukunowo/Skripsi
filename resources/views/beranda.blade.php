<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="Content-Security-Policy" content="script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com; object-src 'none';">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Klasifikasi Formasi Sepak Bola</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/beranda.css') }}">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar">
    <div class="navbar-brand">
      <h2>Klasifikasi Formasi Sepak Bola</h2>
    </div>
    <ul class="navbar-nav">
      <li class="nav-item">
        <a href="{{ url('/') }}" class="nav-link active">Beranda</a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/about') }}" class="nav-link">Tentang</a>
      </li>
     <li class="nav-item">
    <a href="{{ url('/settings') }}" class="nav-link">Pengaturan</a>
</li>
    </ul>
  </nav>

  <div class="container">
    <div class="page-header">
      <h2>Klasifikasi Formasi Sepak Bola</h2>
      <p>Analisis tim Anda dan temukan formasi terbaik</p>
    </div>
    
    <div class="content">
      <div class="file-upload" id="dropArea">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
        </svg>
        <p>Unggah file data tim Anda</p>
        <span>Format yang didukung: CSV, XLSX</span>
        <input type="file" id="fileInput" accept=".csv,.xlsx" />
      </div>
      
      <div class="file-name" id="fileName"></div>
      <div class="error" id="error"></div>
      
      <button id="processBtn" class="process-btn" disabled>Analisis & Klasifikasi</button>
      
      <div class="loading" id="loading">
        <div class="loading-spinner"></div>
        <p>Menganalisis data tim...</p>
      </div>
      
      <div class="output-container" id="outputContainer">
        <div class="output-title">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          Formasi yang Direkomendasikan
        </div>
        
        <div class="formations" id="formations">
          <!-- Hasil dari Laravel akan dimasukkan di sini -->
        </div>
      </div>
<!-- STARTING XI -->
<div id="startingXISection" style="display: none; margin-top: 20px;">
    <h3 style="text-align: center; color: #0ea5e9; margin-bottom: 15px;">Starting XI</h3>
    <div class="player-grid" id="startingXIGrid">
        <!-- Kartu pemain akan dimasukkan di sini -->
    </div>
</div>

<!-- CADANGAN -->
<div id="substitutesSection" style="display: none; margin-top: 20px;">
    <h3 style="text-align: center; color: #f59e0b; margin-bottom: 15px;">Pemain Cadangan</h3>
    <div class="player-grid" id="substitutesGrid">
        <!-- Kartu pemain akan dimasukkan di sini -->
    </div>
</div>
<!-- SUBSTITUTES SECTION -->
<div id="substitutesSection" style="display: none; margin-top: 20px;">
    <h3 style="text-align: center; color: #f59e0b; margin-bottom: 15px;">Pemain Cadangan</h3>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <thead>
                <tr style="background-color: #f59e0b; color: white;">
                    <th style="padding: 12px; text-align: left;">#</th>
                    <th style="padding: 12px; text-align: left;">Nama Pemain</th>
                    <th style="padding: 12px; text-align: left;">Posisi</th>
                    <th style="padding: 12px; text-align: left;">OVR</th>
                </tr>
            </thead>
            <tbody id="substitutesTable">
                <!-- Data akan diisi oleh JavaScript -->
            </tbody>
        </table>
    </div>
</div>
      <div class="download-section" id="downloadSection">
        <button id="downloadPDFBtn" class="process-btn" style="background: linear-gradient(135deg, #8b5cf6, #ec4899);">
          Unduh Laporan PDF
        </button>
        <button id="downloadExcelBtn" class="process-btn" style="background: linear-gradient(135deg, #10b981, #0ea5e9);">
          Unduh Laporan Excel
        </button>
      </div>
    </div>
    
    <div class="footer">
      © 2025 Klasifikasi Formasi Sepak Bola | Dibuat untuk analisis tim
    </div>
  </div>

<script>
    // --- Variabel DOM ---
    const fileInput = document.getElementById('fileInput');
    const fileName = document.getElementById('fileName');
    const processBtn = document.getElementById('processBtn');
    const loading = document.getElementById('loading');
    const outputContainer = document.getElementById('outputContainer');
    const formationsContainer = document.getElementById('formations');
    const dropArea = document.getElementById('dropArea');
    const errorDiv = document.getElementById('error');
    const downloadSection = document.getElementById('downloadSection');
    const downloadPDFBtn = document.getElementById('downloadPDFBtn');
    const downloadExcelBtn = document.getElementById('downloadExcelBtn');

    // --- 1. Fungsi Drag & Drop dan Update UI (Tetap Sama) ---
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
      dropArea.addEventListener(eventName, e => {
        e.preventDefault();
        e.stopPropagation();
      });
    });

    dropArea.addEventListener('dragenter', () => { dropArea.style.borderColor = '#0ea5e9'; dropArea.style.backgroundColor = 'rgba(14, 165, 233, 0.1)'; });
    dropArea.addEventListener('dragleave', () => { dropArea.style.borderColor = '#cbd5e1'; dropArea.style.backgroundColor = ''; });
    dropArea.addEventListener('drop', (e) => {
      const file = e.dataTransfer.files[0];
      if (file && (file.name.endsWith('.csv') || file.name.endsWith('.xlsx'))) {
        fileInput.files = e.dataTransfer.files;
        updateUI(file);
      }
    });

    fileInput.addEventListener('change', (e) => updateUI(e.target.files[0]));

    function updateUI(file) {
      if (file) {
        fileName.textContent = file.name;
        fileName.style.display = 'block';
        processBtn.disabled = false;
        errorDiv.textContent = '';
      }
    }

    // --- 2. Proses Analisis ---
    processBtn.addEventListener('click', () => {
      const file = fileInput.files[0];
      const formData = new FormData();
      formData.append('file', file);
      formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

      loading.style.display = 'block';
      outputContainer.style.display = 'none';

     fetch("{{ route('formation.analyze') }}", {
  method: 'POST',
  headers: {
    'Accept': 'application/json',
    'X-CSRF-TOKEN': document
      .querySelector('meta[name="csrf-token"]')
      .getAttribute('content')
  },
  body: formData
})
.then(async response => {
  if (!response.ok) {
    const text = await response.text(); // untuk debug
    throw new Error(text);
  }
  return response.json();
})
.then(data => {
  if (data.success) {
    renderResults(data);
    outputContainer.style.display = 'block';
    downloadSection.style.display = 'flex';
  } else {
    errorDiv.textContent = '❌ ' + data.message;
  }
})
.catch(err => {
  console.error('SERVER ERROR:', err);
  errorDiv.textContent = '❌ Gagal memproses data (cek Network → Response)';
})
.finally(() => {
  loading.style.display = 'none';
});
    });

    // --- 3. Render Hasil (Sinkronisasi dengan CSS) ---
   function renderResults(data) {
    // 1. Validasi awal data
    if (!data.recommendations || data.recommendations.length === 0) {
        console.error("DEBUG: data.recommendations tidak ditemukan atau kosong!");
        return;
    }

    formationsContainer.innerHTML = '';
    const recommendations = data.recommendations;
    const players = data.startingXI || [];

    recommendations.forEach((rec, i) => {
        const card = document.createElement('div');
        card.className = 'formation-card';
        const fieldId = `field-${i}`;
        
        // 2. Render HTML Kartu
        card.innerHTML = `
            <div class="formation-number">${rec.formasi}</div>
            <div class="formation-rank">Rekomendasi #${i + 1}</div>
            <div style="margin-bottom: 10px; font-size: 12px; color: #64748b;">
            </div>
            <div class="formation-field" id="${fieldId}" style="position: relative; overflow: hidden;">
                <div class="box-left"></div>
                <div class="goal-left"></div>
                <div class="box-right"></div>
                </div>
        `;
        formationsContainer.appendChild(card);

        // 3. Jalankan visualisasi (PENTING: Masukkan rec.formasi sebagai argumen kedua)
        setTimeout(() => {
            if (typeof visualizePlayers === "function") {
                // Kita kirim: ID Lapangan, String Formasi (ex: '4-2-3-1'), dan Array 11 Pemain
                visualizePlayers(fieldId, rec.formasi, players);
            } else {
                console.error("Fungsi visualizePlayers belum didefinisikan!");
            }
        }, 100);
    });

    // 4. Update Tabel Cadangan
    if (data.substitutes && data.substitutes.length > 0) {
        const subTable = document.getElementById('substitutesTable');
        const subSection = document.getElementById('substitutesSection');
        if(subTable) {
            subTable.innerHTML = data.substitutes.map((s, idx) => {
                // Hitung OVR secara dinamis jika key 'Overall' tidak ada di JSON
                const ovr = s.Overall || ((s.pace + s.shooting + s.passing + s.dribbling + s.defending + s.physical) / 6).toFixed(1);
                return `
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #eee;">${idx+1}</td>
                        <td style="padding: 10px; border-bottom: 1px solid #eee;">${s.name}</td>
                        <td style="padding: 10px; border-bottom: 1px solid #eee;">${s.Position}</td>
                        <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold;">${ovr}</td>
                    </tr>
                `;
            }).join('');
            subSection.style.display = 'block';
        }
    }
}

 function visualizePlayers(fieldId, formation, players) {
    const field = document.getElementById(fieldId);
    if (!field || !players || players.length === 0) return;

    field.querySelectorAll('.player-wrapper').forEach(w => w.remove());

    const lines = formation.split('-').map(Number);
    const outfieldPlayers = players.filter(p => p.Position !== 'GK');
    
    // 1. Tempatkan Kiper (GK) - Pastikan Y = 50% agar di tengah gawang
    const gk = players.find(p => p.Position === 'GK');
    if (gk) {
        addPlayerToField(field, gk, 8, 50); 
    }

    let playerCounter = 0;
    const xStart = 25;
    const xEnd = 85;
    const xStep = (xEnd - xStart) / (lines.length - 1 || 1);

    lines.forEach((count, lineIdx) => {
        const xPos = xStart + (lineIdx * xStep);
        
        for (let i = 0; i < count; i++) {
            if (outfieldPlayers[playerCounter]) {
                // LOGIKA PERBAIKAN: 
                // Jika hanya ada 1 pemain di baris tersebut (seperti ST tunggal), 
                // paksa posisinya ke 50%.
                let yPos;
                if (count === 1) {
                    yPos = 50; 
                } else {
                    // Distribusi merata untuk lebih dari 1 pemain
                    yPos = (100 / (count + 1)) * (i + 1);
                }
                
                addPlayerToField(field, outfieldPlayers[playerCounter], xPos, yPos);
                playerCounter++;
            }
        }
    });
}

function addPlayerToField(field, player, x, y) {
    const wrapper = document.createElement('div');
    wrapper.className = 'player-wrapper';
    
    // Gunakan gaya inline untuk memastikan posisi presisi di atas lapangan hijau
    Object.assign(wrapper.style, {
        left: `${x}%`,
        top: `${y}%`,
        position: 'absolute',
        transform: 'translate(-50%, -50%)',
        textAlign: 'center',
        zIndex: '10'
    });

    wrapper.innerHTML = `
        <div class="player" style="
            width: 14px; 
            height: 14px; 
            background: white; 
            border: 2px solid #0ea5e9; 
            border-radius: 50%; 
            margin: 0 auto 4px;">
        </div>
        <div class="player-name" style="
            font-size: 10px; 
            color: white; 
            font-weight: 500;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.8);
            white-space: nowrap;">
            ${player.name.split(' ').pop()}
        </div>
    `;
    field.appendChild(wrapper);
}

// Fungsi pembantu untuk membuat elemen DOM pemain
function addPlayerToField(field, player, x, y) {
    const wrapper = document.createElement('div');
    wrapper.className = 'player-wrapper';
    wrapper.style.left = x + '%';
    wrapper.style.top = y + '%';
    wrapper.style.position = 'absolute';
    wrapper.style.transform = 'translate(-50%, -50%)';

    wrapper.innerHTML = `
        <div class="player" style="background: white; border: 2px solid #0ea5e9; width: 12px; height: 12px; border-radius: 50%; margin: 0 auto;"></div>
        <div class="player-name" style="font-size: 10px; color: white; text-shadow: 1px 1px 2px black; white-space: nowrap; margin-top: 2px;">
            ${player.name.split(' ').pop()} 
        </div>
    `;
    field.appendChild(wrapper);
}

    // --- 4. Fungsi Download (Tetap Sama) ---
    function handleDownload(route, filename, errorMessage) {
        fetch(route, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({}),
        })
        .then(response => response.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            a.click();
        })
        .catch(err => alert(errorMessage));
    }

    downloadPDFBtn.addEventListener('click', () => handleDownload("{{ route('formation.download.pdf') }}", 'laporan.pdf', 'Gagal unduh PDF'));
    downloadExcelBtn.addEventListener('click', () => handleDownload("{{ route('formation.download.excel') }}", 'laporan.xlsx', 'Gagal unduh Excel'));
</script>
</body>
</html>