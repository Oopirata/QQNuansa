<!DOCTYPE html>
<html lang="id">
    @php
    function normalize_city($raw) {
        $raw = strtolower($raw);
        $raw = preg_replace('/^(kota|kabupaten)\s+/i', '', $raw);
        return ucwords($raw);
    }
    
    // Fallback data default jika variabel tidak di-pass ke view
    $participant = $participant ?? (object)[
        'name' => 'Nama Peserta Contoh', 
        'place_of_birth' => 'Kota Kelahiran',
        'birthdate' => \Carbon\Carbon::now()->subYears(25), 
        'gender' => 'Laki-laki', 
        'education' => 'S1 Contoh Jurusan',
    ];
    
    $testResults = $testResults ?? (object)[
        'raw_score' => 45, 
        'level' => 'III', 
        'category' => 'INTELLECTUALLY AVERAGE', 
        'created_at' => \Carbon\Carbon::now(),
    ];
    
    $interpretationData = $interpretationData ?? [
        'level_text' => 'rata-rata',
        'description' => 'Peserta menunjukkan kemampuan intelektual rata-rata dengan kemampuan memahami pola-pola logis dan menyelesaikan masalah visual secara standar.'
    ];
    
    // Variabel untuk menyimpan klasifikasi dari controller untuk dicocokkan dengan checkbox
    $currentClassification = $testResults->category ?? '';
    
    // Variabel untuk menampilkan teks yang lebih mudah dibaca di Kesimpulan
    $readableClassification = match($currentClassification) {
        'INTELLECTUALLY SUPERIOR' => 'Superior',
        'DEFINITELY ABOVE THE AVERAGE INTELLECTUAL CAPACITY' => 'Di Atas Rata-rata',
        'INTELLECTUALLY AVERAGE' => 'Rata-rata',
        'DEFINITELY BELOW THE AVERAGE INTELLECTUAL CAPACITY' => 'Di Bawah Rata-rata',
        'INTELLECTUALITY DEFECTIVE' => 'Sangat di Bawah Rata-rata (Defective)',
        default => 'Tidak Teridentifikasi'
    };
    @endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Psikotes</title>
    <style>
        h3 + div {
            margin-top: 0 !important;
        }
        @page {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: white;
            position: relative;
            padding-bottom: 30px;
        }
        .header-container {
            position: relative;
            width: 100%;
            height: 120px;
            z-index: -1;
        }
        .wave-header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: auto;
        }
        .content-container {
            padding: 0 90px;
            margin-top: 3.5em;
            position: relative;
            z-index: 1;
        }
        .title-container {
            text-align: center;
            margin-bottom: 2px;
        }
        .title {
            font-family: Arial, sans-serif;
            font-size: 28px;
            font-weight: bold;
            color: #0066a6;
        }
        .subtitle {
            font-size: 18px;
            color: #333;
        }
        h3 {
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: bold;
            margin-block-end: 0;
            margin-bottom: 0;
        }
        .identity-section {
            margin-bottom: 20px;
        }
        .info-row {
            margin-bottom: 0;
        }
        .info-label {
            display: inline-block;
            width: 150px;
        }
        .info-value {
            display: inline-block;
        }
        .dotted-line {
            display: inline-block;
            border-bottom: 1px dotted #000;
            width: 250px;
        }
        .category-container {
            margin-top: 1em;
        }
        .checkbox-container {
            margin-top: 5px;
        }
        .checkbox-item {
            margin-bottom: 4px;
        }
        .checkbox {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            margin-right: 8px;
        }
        .checked {
            background-color: #000;
        }
        .interpretation {
            text-align: justify;
        }
        .underline {
            text-decoration: underline;
        }
        .conclusion {
            text-align: justify;
        }
        .signature-section {
            margin-top: 40px;
            margin-bottom: 30px;
        }
        .signature-text {
            margin-bottom: 40px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .signature-img {
            position: absolute;
            margin-top: -40px;
            height: 70px;
        }
        .stamp-img {
            position: absolute;
            margin-top: -74px;
            margin-left: 82px;
            height: 110px;
            opacity: 0.8;
        }
        .wa-img {
            position: absolute;
            margin-left: 39px;
            height: 18px;
            z-index: 1;
        }
        .footer-container {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 70px;
            z-index: -1;
        }
        .wave-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <!-- Header Section with Wave Background -->
    <div class="header-container">
        <img class="wave-header" src="{{ public_path('images/header-bg.png') }}" alt="Header Background">
    </div>

    <!-- Content Section -->
    <div class="content-container">
        <div class="title-container">
            <div class="title">LAPORAN HASIL PSIKOTES</div>
            <div class="subtitle">Tes SPM (Standard Progressive Matrices)</div>
        </div>

        <!-- Identity Section -->
        <h3>I. Identitas Peserta</h3>
        <div class="identity-section">
            <div class="info-row">
                <span class="info-label">Nama</span>: 
                <span class="info-value">{{ $participant->name ?? '.................................' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tempat/Tanggal Lahir</span>: 
                <span class="info-value">
                    {{ $participant->place_of_birth ? normalize_city($participant->place_of_birth) : '..................' }},
                    {{ $participant->birthdate ? \Carbon\Carbon::parse($participant->birthdate)->translatedFormat('d F Y') : '..................' }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Jenis Kelamin</span>: 
                <span class="info-value">{{ $participant->gender ?? '.................................' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Pendidikan Terakhir</span>: 
                <span class="info-value">{{ $participant->education ?? '.................................' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Tes</span>: 
                <span class="info-value">{{ isset($testResults->created_at) ? \Carbon\Carbon::parse($testResults->created_at)->translatedFormat('d F Y') : '.................................' }}</span>
            </div>
        </div>

        <!-- Test Results Section -->
        <h3>II. Hasil Tes</h3>
        <div class="test-results-section">
            <div class="info-row">
                <span class="info-label">Skor Benar</span>: 
                <span class="info-value">{{ $testResults->raw_score ?? '..................' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Level Intelektualitas</span>: 
                <span class="info-value"><strong>{{ $testResults->level ?? '..................' }}</strong></span>
            </div>

            <div class='category-container'>
                <div class="info-row">
                    <span class="info-label">Klasifikasi:</span>
                </div>
                <div class="checkbox-container">
                    <div class="checkbox-item"><span class="checkbox {{ $currentClassification === 'INTELLECTUALLY SUPERIOR' ? 'checked' : '' }}"></span> INTELLECTUALLY SUPERIOR</div>
                    <div class="checkbox-item"><span class="checkbox {{ $currentClassification === 'DEFINITELY ABOVE THE AVERAGE INTELLECTUAL CAPACITY' ? 'checked' : '' }}"></span> DEFINITELY ABOVE THE AVERAGE INTELLECTUAL CAPACITY</div>
                    <div class="checkbox-item"><span class="checkbox {{ $currentClassification === 'INTELLECTUALLY AVERAGE' ? 'checked' : '' }}"></span> INTELLECTUALLY AVERAGE</div>
                    <div class="checkbox-item"><span class="checkbox {{ $currentClassification === 'DEFINITELY BELOW THE AVERAGE INTELLECTUAL CAPACITY' ? 'checked' : '' }}"></span> DEFINITELY BELOW THE AVERAGE INTELLECTUAL CAPACITY</div>
                    <div class="checkbox-item"><span class="checkbox {{ $currentClassification === 'INTELLECTUALITY DEFECTIVE' ? 'checked' : '' }}"></span> INTELLECTUALITY DEFECTIVE</div>
                </div>
            </div>
        </div>

        <!-- Interpretation Section -->
        <h3>III. Interpretasi</h3>
        <div class="interpretation">
            <p>Berdasarkan hasil tes, peserta menunjukkan kemampuan intelektual <strong><span class="underline">{{ $interpretationData['level_text'] ?? 'tidak teridentifikasi' }}</span></strong>, {{ $interpretationData['description'] ?? 'dengan kemampuan yang perlu dievaluasi lebih lanjut.' }}</p>
        </div>

        <!-- Conclusion Section -->
        <h3>IV. Kesimpulan</h3>
        <div class="conclusion">
            <p>Hasil tes menunjukkan bahwa kemampuan kognitif peserta berada dalam klasifikasi <strong>{{ $readableClassification }} (Level {{ $testResults->level ?? 'Tidak Teridentifikasi' }})</strong>. Hasil ini dapat digunakan sebagaimana mestinya.</p>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-text">Hormat kami,</div>
            <img class="signature-img" src="{{ public_path('images/signature.png') }}" alt="Tanda Tangan">
            <img class="stamp-img" src="{{ public_path('images/stamp.png') }}" alt="Stempel QQ Nuansa">
            <div style="margin-top: 36px;">
                <div class="signature-name">Rizki Nuansa Hadyan, MM, Psikolog</div>
                <div>SIPP: 20140976-2025-03-0663</div>
            </div>
        </div>
    </div>

    <!-- Footer Section with Wave Background -->
    <div class="footer-container">
        <img class="wa-img" src="{{ public_path('images/wa.png') }}" alt="WhatsApp Logo">
        <img class="wave-footer" src="{{ public_path('images/footer-bg.png') }}" alt="Footer Background">
    </div>
</body>
</html>