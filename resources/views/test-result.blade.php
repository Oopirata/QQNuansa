<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Psikotes</title>
</head>
<body>
    <h2>LAPORAN HASIL PSIKOTES</h2>
    <p><strong>Tes SPM (Standard Progressive Matrices)</strong></p>

    <h3>I. Identitas Peserta</h3>
    <p><strong>Nama:</strong> {{ $participant->name }}</p>
    <p><strong>Tempat/Tanggal Lahir:</strong> {{ $participant->birthplace ?? '-' }}, {{ $participant->birthdate ?? '-' }}</p>

    <p><strong>Jenis Kelamin:</strong> {{ $participant->gender ?? '-' }}</p>
    <p><strong>Pendidikan Terakhir:</strong> {{ $participant->education ?? '-' }}</p>
    <p><strong>Tanggal Tes:</strong> {{ $testResults->created_at->format('d-m-Y') ?? '-' }}</p>

    <h3>II. Hasil Tes</h3>
    <p><strong>Skor Mentah:</strong> {{ $testResults->raw_score ?? '-' }}</p>
    <p><strong>IQ (Estimasi):</strong> {{ $testResults->iq ?? '-' }}</p>

    <h3>Kategori:</h3>
    <p>
        @if ($testResults->iq >= 130)
            ☐ Very Superior /Tinggi Sekali
        @elseif ($testResults->iq >= 120)
            ☐ Superior /Tinggi
        @elseif ($testResults->iq >= 110)
            ☐ Di atas rata-rata/ High Average
        @elseif ($testResults->iq >= 90)
            ☐ Rata-rata /Average
        @elseif ($testResults->iq >= 80)
            ☐ Di bawah rata-rata / Low Average
        @elseif ($testResults->iq >= 70)
            ☐ Rendah
        @else
            ☐ Rendah Sekali
        @endif
    </p>

    <h3>III. Interpretasi</h3>
    <p>Berdasarkan hasil tes, peserta menunjukkan kemampuan intelektual rata-rata, dengan kecenderungan mampu memahami pola-pola logis dan menyelesaikan masalah visual secara efisien. Peserta dapat mengikuti pelatihan atau pekerjaan yang membutuhkan pemikiran analitis dasar dengan baik, namun mungkin memerlukan waktu tambahan dalam menyelesaikan tugas yang kompleks secara logis.</p>

    <h3>IV. Kesimpulan</h3>
    <p>Hasil tes menunjukkan bahwa kemampuan kognitif peserta berada dalam kategori {{ $answers['category'] ?? '-' }}. Hasil ini dapat digunakan sebagaimana mestinya.</p>

    <p>Hormat kami,</p>
    <p>Rizki Nuansa Hadyan, MM, Psikolog</p>
    <p>SIPP: 20140976-2025-03-0663</p>

    <p>{{ $participant->phone ?? '-' }}</p>
</body>
</html>