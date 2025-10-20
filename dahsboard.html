<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Klasifikasi Formasi Sepak Bola</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #0ea5e9;
      --secondary: #10b981;
      --dark: #1e293b;
      --light: #f8fafc;
      --accent: #f59e0b;
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #e0f2fe;
      color: var(--dark);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 20px;
      background-image: radial-gradient(circle at 10% 20%, rgba(14, 165, 233, 0.1) 0%, rgba(16, 185, 129, 0.1) 90%);
    }
    
    .container {
      max-width: 800px;
      width: 100%;
      background-color: white;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }
    
    .header {
      background-color: var(--primary);
      color: white;
      padding: 25px 30px;
      text-align: center;
      position: relative;
    }
    
    .header h2 {
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 5px;
    }
    
    .header p {
      font-size: 16px;
      opacity: 0.9;
    }
    
    .content {
      padding: 30px;
    }
    
    .file-upload {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      border: 2px dashed #cbd5e1;
      border-radius: 12px;
      padding: 30px;
      text-align: center;
      transition: all 0.3s ease;
      cursor: pointer;
      margin-bottom: 25px;
      position: relative;
    }
    
    .file-upload:hover {
      border-color: var(--primary);
      background-color: rgba(14, 165, 233, 0.05);
    }
    
    .file-upload svg {
      width: 60px;
      height: 60px;
      color: var(--primary);
      margin-bottom: 15px;
    }
    
    .file-upload p {
      color: #64748b;
      margin-bottom: 10px;
    }
    
    .file-upload span {
      font-size: 14px;
      color: #94a3b8;
    }
    
    .file-upload input {
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      opacity: 0;
      cursor: pointer;
    }
    
    .file-name {
      display: none;
      background-color: #f1f5f9;
      padding: 10px 15px;
      border-radius: 8px;
      margin-top: 15px;
      font-size: 14px;
      color: var(--dark);
      width: 100%;
      text-align: center;
    }
    
    .process-btn {
      display: block;
      width: 100%;
      padding: 15px;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-bottom: 25px;
    }
    
    .process-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(14, 165, 233, 0.4);
    }
    
    .process-btn:disabled {
      background: #94a3b8;
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }
    
    .output-container {
      display: none;
      margin-top: 20px;
    }
    
    .output-title {
      font-size: 18px;
      font-weight: 600;
      margin-bottom: 15px;
      color: var(--dark);
      display: flex;
      align-items: center;
    }
    
    .output-title svg {
      width: 24px;
      height: 24px;
      margin-right: 8px;
      color: var(--secondary);
    }
    
    .formations {
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
    }
    
    .formation-card {
      flex: 1;
      min-width: 200px;
      background-color: #f8fafc;
      border-radius: 12px;
      padding: 20px;
      text-align: center;
      border: 1px solid #e2e8f0;
      transition: all 0.3s ease;
    }
    
    .formation-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
      border-color: var(--primary);
    }
    
    .formation-number {
      font-size: 24px;
      font-weight: 700;
      color: var(--primary);
      margin-bottom: 10px;
    }
    
    .formation-rank {
      display: inline-block;
      background-color: var(--accent);
      color: white;
      font-size: 12px;
      font-weight: 600;
      padding: 4px 10px;
      border-radius: 20px;
      margin-bottom: 15px;
    }
    
    .formation-field {
      width: 100%;
      height: 120px;
      background-color: #15803d;
      border-radius: 8px;
      margin-top: 10px;
      position: relative;
      overflow: hidden;
    }
    
    .formation-field::before {
      content: "";
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 80%;
      height: 80%;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-radius: 5px;
    }
    
    .formation-field::after {
      content: "";
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 20px;
      height: 20px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-radius: 50%;
    }
    
    .player {
      position: absolute;
      width: 8px;
      height: 8px;
      background-color: white;
      border-radius: 50%;
    }
    
    .footer {
      text-align: center;
      padding: 20px;
      color: #64748b;
      font-size: 14px;
      border-top: 1px solid #e2e8f0;
    }
    
    @media (max-width: 768px) {
      .container {
        border-radius: 12px;
      }
      
      .formations {
        flex-direction: column;
      }
      
      .formation-card {
        min-width: 100%;
      }
    }
    
    .loading {
      display: none;
      text-align: center;
      padding: 20px;
    }
    
    .loading-spinner {
      width: 40px;
      height: 40px;
      border: 4px solid rgba(14, 165, 233, 0.1);
      border-left-color: var(--primary);
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin: 0 auto 15px;
    }
    
    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
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
          <!-- Formation cards will be inserted here -->
        </div>
      </div>
    </div>
    
    <div class="footer">
      © 2025 Klasifikasi Formasi Sepak Bola | Dibuat untuk analisis tim
    </div>
  </div>

  <script>
    const formasiList = ["4-3-3", "3-5-2", "3-4-3", "4-2-3-1", "5-4-1", "4-4-2", "4-5-1", "3-2-4-1", "5-3-2"];
    const fileInput = document.getElementById('fileInput');
    const fileName = document.getElementById('fileName');
    const processBtn = document.getElementById('processBtn');
    const loading = document.getElementById('loading');
    const outputContainer = document.getElementById('outputContainer');
    const formationsContainer = document.getElementById('formations');
    const dropArea = document.getElementById('dropArea');

    // Handle file selection
    fileInput.addEventListener('change', function(event) {
      const file = event.target.files[0];
      if (file) {
        fileName.textContent = file.name;
        fileName.style.display = 'block';
        processBtn.disabled = false;
      } else {
        fileName.style.display = 'none';
        processBtn.disabled = true;
      }
    });

    // Handle drag and drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
      dropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
      e.preventDefault();
      e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
      dropArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
      dropArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
      dropArea.style.borderColor = (--primary);
      dropArea.style.backgroundColor = 'rgba(14, 165, 233, 0.1)';
    }

    function unhighlight() {
      dropArea.style.borderColor = '#cbd5e1';
      dropArea.style.backgroundColor = '';
    }

    dropArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
      const dt = e.dataTransfer;
      const file = dt.files[0];
      fileInput.files = dt.files;
      
      if (file) {
        fileName.textContent = file.name;
        fileName.style.display = 'block';
        processBtn.disabled = false;
      }
    }

    // Process button click
    processBtn.addEventListener('click', function() {
      const file = fileInput.files[0];
      if (!file) {
        alert("Silakan pilih file CSV atau XLSX terlebih dahulu.");
        return;
      }

      // Show loading
      loading.style.display = 'block';
      outputContainer.style.display = 'none';
      
      // Simulate processing time
      setTimeout(() => {
        processFile(file);
      }, 1500);
    });

    function acakFormasi(n = 3) {
      const shuffled = [...formasiList].sort(() => 0.5 - Math.random());
      return shuffled.slice(0, n);
    }

    function processFile(file) {
      const reader = new FileReader();
      
      reader.onload = () => {
        // Get recommended formations
        const recommendedFormations = acakFormasi(3);
        
        // Create formation cards
        formationsContainer.innerHTML = '';
        
        recommendedFormations.forEach((formation, index) => {
          const card = document.createElement('div');
          card.className = 'formation-card';
          
          const rank = index + 1;
          let rankText = 'Rekomendasi #' + rank;
          
          card.innerHTML = `
            <div class="formation-number">${formation}</div>
            <div class="formation-rank">${rankText}</div>
            <div class="formation-field" id="field-${index}"></div>
          `;
          
          formationsContainer.appendChild(card);
          
          // Add players to the field visualization
          setTimeout(() => {
            visualizeFormation(formation, `field-${index}`);
          }, 100);
        });
        
        // Hide loading and show results
        loading.style.display = 'none';
        outputContainer.style.display = 'block';
      };

      reader.readAsArrayBuffer(file);
    }

    function visualizeFormation(formation, fieldId) {
      const field = document.getElementById(fieldId);
      const parts = formation.split('-').map(Number);
      
      // Add goalkeeper
      addPlayer(field, 50, 90);
      
      // Add other players based on formation
      const totalLines = parts.length;
      
      parts.forEach((playersInLine, lineIndex) => {
        const yPosition = 75 - (lineIndex + 1) * (60 / (totalLines + 1));
        
        for (let i = 0; i < playersInLine; i++) {
          const xSpacing = 80 / (playersInLine + 1);
          const xPosition = 10 + (i + 1) * xSpacing;
          addPlayer(field, xPosition, yPosition);
        }
      });
    }

    function addPlayer(field, xPercent, yPercent) {
      const player = document.createElement('div');
      player.className = 'player';
      player.style.left = `${xPercent}%`;
      player.style.top = `${yPercent}%`;
      field.appendChild(player);
    }
  </script>
<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9696e8f5e4bf5ffe',t:'MTc1NDIzNTU5MC4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>