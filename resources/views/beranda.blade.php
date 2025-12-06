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
        <a href="{{ url('/setting') }}" class="nav-link">Pengaturan</a>
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

    // Drag & drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
      dropArea.addEventListener(eventName, e => {
        e.preventDefault();
        e.stopPropagation();
      });
    });

    ['dragenter', 'dragover'].forEach(() => {
      dropArea.addEventListener('dragenter', highlight);
      dropArea.addEventListener('dragover', highlight);
    });

    ['dragleave', 'drop'].forEach(() => {
      dropArea.addEventListener('dragleave', unhighlight);
      dropArea.addEventListener('drop', handleDrop);
    });

    function highlight() {
      dropArea.style.borderColor = '#0ea5e9';
      dropArea.style.backgroundColor = 'rgba(14, 165, 233, 0.1)';
    }

    function unhighlight() {
      dropArea.style.borderColor = '#cbd5e1';
      dropArea.style.backgroundColor = '';
    }

    function handleDrop(e) {
      const dt = e.dataTransfer;
      const file = dt.files[0];
      if (file && (file.name.endsWith('.csv') || file.name.endsWith('.xlsx'))) {
        fileInput.files = dt.files;
        updateUI(file);
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
      } else {
        fileName.style.display = 'none';
        processBtn.disabled = true;
      }
    }

    // Proses via AJAX ke Laravel
    processBtn.addEventListener('click', () => {
      const file = fileInput.files[0];
      if (!file) {
        errorDiv.textContent = 'Pilih file terlebih dahulu.';
        return;
      }

      const formData = new FormData();
      formData.append('file', file);
      formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

      errorDiv.textContent = '';
      loading.style.display = 'block';
      outputContainer.style.display = 'none';
      downloadSection.style.display = 'none';

      fetch("{{ route('formation.analyze') }}", {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          renderResults(data.recommendations);
          outputContainer.style.display = 'block';
          downloadSection.style.display = 'block';
        } else {
          errorDiv.textContent = '❌ ' + data.message;
        }
      })
      .catch(err => {
        console.error(err);
        errorDiv.textContent = '❌ Terjadi kesalahan saat memproses file.';
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
        setTimeout(() => visualizeFormation(item.formasi, `field-${i}`), 50);
      });
    }

    function visualizeFormation(formation, fieldId) {
      const field = document.getElementById(fieldId);
      if (!field) return;

      const parts = formation.split('-').map(Number);
      addPlayer(field, 50, 90); // GK

      const totalLines = parts.length;
      parts.forEach((count, lineIdx) => {
        const y = 75 - (lineIdx + 1) * (60 / (totalLines + 1));
        for (let i = 0; i < count; i++) {
          const x = 10 + (i + 1) * (80 / (count + 1));
          addPlayer(field, x, y);
        }
      });
    }

    function addPlayer(field, x, y) {
      const p = document.createElement('div');
      p.className = 'player';
      p.style.left = `${x}%`;
      p.style.top = `${y}%`;
      field.appendChild(p);
    }

    downloadPDFBtn.addEventListener('click', () => {
      fetch("{{ route('formation.download.pdf') }}", {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({}),
      })
      .then(response => {
        if (response.ok) {
          return response.blob();
        }
        throw new Error('Gagal mengunduh PDF.');
      })
      .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'laporan-formasi-sepak-bola.pdf';
        document.body.appendChild(a);
        a.click();
        a.remove();
      })
      .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan saat mengunduh PDF.');
      });
    });

    downloadExcelBtn.addEventListener('click', () => {
      fetch("{{ route('formation.download.excel') }}", {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({}),
      })
      .then(response => {
        if (response.ok) {
          return response.blob();
        }
        throw new Error('Gagal mengunduh Excel.');
      })
      .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'laporan-formasi-sepak-bola.xlsx';
        document.body.appendChild(a);
        a.click();
        a.remove();
      })
      .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan saat mengunduh Excel.');
      });
    });
  </script>
</body>
</html>