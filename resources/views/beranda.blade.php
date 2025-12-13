<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
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

    // --- 1. Fungsi Drag & Drop dan Update UI ---
    
    // Mencegah perilaku default browser saat drag/drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
      dropArea.addEventListener(eventName, e => {
        e.preventDefault();
        e.stopPropagation();
      });
    });

    // Efek visual saat file di-drag
    dropArea.addEventListener('dragenter', highlight);
    dropArea.addEventListener('dragover', highlight);
    dropArea.addEventListener('dragleave', unhighlight);
    dropArea.addEventListener('drop', handleDrop);
    
    function highlight() {
      dropArea.style.borderColor = '#0ea5e9';
      dropArea.style.backgroundColor = 'rgba(14, 165, 233, 0.1)';
    }

    function unhighlight() {
      dropArea.style.borderColor = '#cbd5e1';
      dropArea.style.backgroundColor = '';
    }

    function handleDrop(e) {
      unhighlight();
      const dt = e.dataTransfer;
      const file = dt.files[0];
      if (file && (file.name.endsWith('.csv') || file.name.endsWith('.xlsx'))) {
        fileInput.files = dt.files;
        updateUI(file);
      } else {
        errorDiv.textContent = '❌ Format file tidak didukung. Gunakan CSV atau XLSX.';
      }
    }

    fileInput.addEventListener('change', (e) => {
      updateUI(e.target.files[0]);
    });

    function updateUI(file) {
      if (file) {
        fileName.textContent = file.name;
        fileName.style.display = 'block';
        processBtn.disabled = false;
        errorDiv.textContent = '';
        // Sembunyikan hasil lama saat file baru diunggah
        outputContainer.style.display = 'none';
        downloadSection.style.display = 'none';
      } else {
        fileName.style.display = 'none';
        processBtn.disabled = true;
      }
    }
    
    // --- 2. Proses Analisis (AJAX) ---
    processBtn.addEventListener('click', () => {
      const file = fileInput.files[0];
      if (!file) {
        errorDiv.textContent = 'Pilih file terlebih dahulu.';
        return;
      }

      const formData = new FormData();
      formData.append('file', file);
      // Mengambil token CSRF dari meta tag
      formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

      errorDiv.textContent = '';
      loading.style.display = 'block';
      outputContainer.style.display = 'none';
      downloadSection.style.display = 'none';

      fetch("{{ route('formation.analyze') }}", {
        method: 'POST',
        body: formData
      })
      .then(response => {
        if (!response.ok) {
            // Tangani error HTTP seperti 500
            throw new Error(`HTTP Error! Status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        if (data.success) {
          renderResults(data.recommendations);
          outputContainer.style.display = 'block';
          downloadSection.style.display = 'flex'; // Gunakan flex agar tombol berdampingan
        } else {
          // Tangani error dari sisi Laravel (misal: data tidak valid)
          errorDiv.textContent = '❌ ' + data.message;
        }
      })
      .catch(err => {
        console.error('Fetch Error:', err);
        errorDiv.textContent = '❌ Terjadi kesalahan saat memproses file. Periksa konsol.';
      })
      .finally(() => {
        loading.style.display = 'none';
      });
    });

    function renderResults(results) {
      formationsContainer.innerHTML = '';
      results.forEach((item, i) => {
        const card = document.createElement('div');
        card.className = 'formation-card';
        card.innerHTML = `
          <div class="formation-number">${item.formasi}</div>
          <div class="formation-rank">Rekomendasi #${i + 1}</div>
          <div class="formation-field" id="field-${i}"></div>
        `;
        formationsContainer.appendChild(card);
        // Tunda visualisasi sebentar agar elemen DOM ter-render
        setTimeout(() => visualizeFormation(item.formasi, `field-${i}`), 50);
      });
    }

    // --- 3. Visualisasi Formasi (Lapang Penuh) ---
    
    // Fungsi pembantu untuk menempatkan pemain
    function addPlayer(field, x, y) {
      const p = document.createElement('div');
      p.className = 'player';
      // Posisi diukur dari kiri (x) dan atas (y)
      p.style.left = `${x}%`;
      p.style.top = `${y}%`;
      field.appendChild(p);
    }
    
    function visualizeFormation(formation, fieldId) {
        const field = document.getElementById(fieldId);
        if (!field) return;
        
        // Hapus pemain lama dan tambahkan elemen lapangan baru
        field.innerHTML = ''; 
        
        // Tambahkan elemen visual lapangan (sesuai CSS: Kiri, Tengah, Kanan)
        
        // 1. Kotak Penalti Kiri (Sisi Gawang Kita)
        const boxLeft = document.createElement('div');
        boxLeft.className = 'box-left';
        field.appendChild(boxLeft);
    
        // 2. Gawang Kiri
        const goalLeft = document.createElement('div');
        goalLeft.className = 'goal-left';
        field.appendChild(goalLeft);

        // 3. Kotak Penalti Kanan (Sisi Gawang Lawan)
        const boxRight = document.createElement('div');
        boxRight.className = 'box-right';
        field.appendChild(boxRight);


        const parts = formation.split('-').map(Number);
        
        // 1. Tambahkan GK (Posisi x: 5% dari kiri/gawang kita, y: 50% di tengah)
        addPlayer(field, 5, 50); 
    
        const totalLines = parts.length;
        
        // Posisi X (Kedalaman Lapangan, 0-100%). Pemain non-GK 
        // akan ditempatkan antara 20% (Bek) dan 90% (Striker)
        
        parts.forEach((count, lineIdx) => {
            // Skala Vertikal (X-axis): Membuat jarak antar baris sama
            // lineIdx = 0 (Defense), lineIdx = 1 (Midfield), dst.
            const lineStart = 20; 
            const totalDistance = 70; // Jarak dari 20% ke 90%
            const lineGap = totalDistance / (totalLines);
            // xPos = 20% + (Jarak yang ditempuh)
            const xPos = lineStart + (lineIdx * lineGap) + (lineGap / 2); // Tambah offset agar pas di tengah zona

            for (let i = 0; i < count; i++) {
                // Skala Horizontal (Y-axis): Menyebar dari 10% hingga 90% (lebar lapangan)
                const yPos = 10 + (i + 1) * (80 / (count + 1));
                
                addPlayer(field, xPos, yPos); 
            }
        });
    }

    // --- 4. Fungsi Download (PDF dan Excel) ---

    // Fungsi unduh umum
    function handleDownload(route, filename, errorMessage) {
        fetch(route, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({}),
        })
        .then(response => {
            if (!response.ok) {
                // Tangani error HTTP
                return response.json().then(errorData => {
                    throw new Error(errorData.message || errorMessage);
                });
            }
            return response.blob();
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
        })
        .catch(err => {
            console.error(err);
            alert(err.message);
        });
    }

    downloadPDFBtn.addEventListener('click', () => {
        handleDownload(
            "{{ route('formation.download.pdf') }}", 
            'laporan-formasi-sepak-bola.pdf', 
            'Gagal mengunduh PDF.'
        );
    });

    downloadExcelBtn.addEventListener('click', () => {
        handleDownload(
            "{{ route('formation.download.excel') }}", 
            'laporan-formasi-sepak-bola.xlsx', 
            'Gagal mengunduh Excel.'
        );
    });
</script>
</body>
</html>