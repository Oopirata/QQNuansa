<template>
    <Head title="Hasil Psikotest" />
    <div class="app-container">
        <!-- Konten utama -->
        <main class="main-content">
            <div class="results-container">
                <h1 class="page-title">
                    Hasil Psikotest - {{ participant?.name || "-" }}
                </h1>

                <div class="result-cards-container">
                    <!-- Kartu Informasi Peserta -->
                    <div class="result-card info-card">
                        <div class="card-header">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="card-icon"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path
                                    d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"
                                ></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <h2>Informasi Peserta</h2>
                        </div>
                        <div class="info-grid">
                            <div class="info-row">
                                <p class="info-label">Nama</p>
                                <p class="info-value">
                                    {{ participant?.name || "-" }}
                                </p>
                            </div>
                            <div class="info-row">
                                <p class="info-label">Tempat/Tanggal Lahir</p>
                                <p class="info-value">
                                    {{
                                        participant?.birthdate
                                            ? normalizeCity(
                                                  participant?.place_of_birth
                                              ) +
                                              ", " +
                                              formatDate(participant.birthdate)
                                            : "-"
                                    }}
                                </p>
                            </div>
                            <div class="info-row">
                                <p class="info-label">Jenis Kelamin</p>
                                <p class="info-value">
                                    {{ participant?.gender || "-" }}
                                </p>
                            </div>
                            <div class="info-row">
                                <p class="info-label">Pendidikan Terakhir</p>
                                <p class="info-value">
                                    {{ participant?.education || "-" }}
                                </p>
                            </div>
                            <div class="info-row">
                                <p class="info-label">Tanggal Tes</p>
                                <p class="info-value">
                                    {{
                                        participant?.test_date
                                            ? formatDate(participant.test_date)
                                            : "-"
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu Hasil Tes -->
                    <div class="result-card test-card">
                        <div class="card-header">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="card-icon"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path
                                    d="M22 11.08V12a10 10 0 1 1-5.93-9.14"
                                ></path>
                                <polyline
                                    points="22 4 12 14.01 9 11.01"
                                ></polyline>
                            </svg>
                            <h2>Hasil Tes</h2>
                        </div>
                        <div class="info-grid">
                            <div class="info-row">
                                <p class="info-label">Skor Benar (Raw Score)</p>
                                <p class="info-value score">
                                    {{
                                        participant?.test_results?.raw_score ??
                                        "-"
                                    }}
                                </p>
                            </div>
                            <div class="info-row">
                                <p class="info-label">Level Intelektualitas</p>
                                <p class="info-value score">
                                    {{
                                        participant?.test_results?.level ?? "-"
                                    }}
                                </p>
                            </div>
                            <div class="info-row">
                                <p class="info-label">Klasifikasi</p>
                                <p class="info-value category-badge">
                                    {{
                                        participant?.test_results?.category ??
                                        "-"
                                    }}
                                </p>
                            </div>
                        </div>

                        <div
                            class="additional-stats"
                            v-if="participant?.test_results"
                        >
                            <h4 class="stats-title">Statistik Tambahan</h4>
                            <div class="stats-grid">
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"
                                            ></path>
                                            <rect
                                                x="8"
                                                y="2"
                                                width="8"
                                                height="4"
                                                rx="1"
                                                ry="1"
                                            ></rect>
                                        </svg>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-label"
                                            >Persentil</span
                                        >
                                        <span class="stat-value">{{
                                            calculatePercentile()
                                        }}</span>
                                    </div>
                                </div>

                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <circle
                                                cx="12"
                                                cy="12"
                                                r="10"
                                            ></circle>
                                            <polyline
                                                points="12 6 12 12 16 14"
                                            ></polyline>
                                        </svg>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-label"
                                            >Durasi Tes</span
                                        >
                                        <span class="stat-value">{{
                                            formatDuration(
                                                participant?.test_duration
                                            )
                                        }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="form-actions">
                    <button @click="goBack" class="btn secondary-btn">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="btn-icon"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        Kembali
                    </button>
                    <a
                        :href="
                            participant?.id
                                ? `/test-result/${participant.id}/download`
                                : '#'
                        "
                        target="_blank"
                        class="btn download-btn"
                        :class="{ 'disabled-btn': !participant?.id }"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="btn-icon"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path
                                d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"
                            ></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Download PDF
                    </a>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import dayjs from "dayjs";
import "dayjs/locale/id";

dayjs.locale("id"); // Set locale ke Bahasa Indonesia

export default {
    components: { Head },
    props: {
        participant: Object,
        userRole: String,
    },
    methods: {
        formatDate(date) {
            if (!date) return "-";
            return dayjs(date).format("D MMMM YYYY");
        },
        normalizeCity(raw) {
            if (!raw) return "";
            return raw
                .toLowerCase()
                .replace(/^(kota|kabupaten)\s+/i, "")
                .split(" ")
                .map((w) => w.charAt(0).toUpperCase() + w.slice(1))
                .join(" ");
        },
        goBack() {
            if (this.userRole === "admin") {
                // Untuk admin, kembali ke halaman sebelumnya
                window.history.back();
            } else {
                // Untuk user biasa, arahkan ke halaman utama layanan
                this.$inertia.visit("/servicepsikotest");
            }
        },
        /**
         * Menghitung persentil berdasarkan level intelektualitas
         */
        calculatePercentile() {
            if (!this.participant?.test_results?.level) return "-";

            const level = this.participant.test_results.level;
            const percentileMap = {
                V: "5%",
                IV: "20%",
                "III-": "35%",
                III: "50%",
                "III+": "65%",
                II: "80%",
                "II+": "90%",
                I: "95%",
            };

            return percentileMap[level] || "-";
        },

        /**
         * Format durasi tes
         */
        formatDuration(duration) {
            if (!duration || duration === null) return "-";

            // Pastikan duration adalah angka positif
            const minutes = Math.abs(parseInt(duration));

            if (minutes >= 60) {
                const hours = Math.floor(minutes / 60);
                const remainingMinutes = minutes % 60;
                return `${hours}j ${remainingMinutes}m`;
            }

            return `${minutes} menit`;
        },
    },
    mounted() {
        console.log("Data Peserta yang Diterima Komponen:", this.participant);
    },
};
</script>

<style scoped>
:deep(*) {
    font-family: "Kaisei Opti", sans-serif;
}
/* Container utama */
.app-container {
    display: flex;
    min-height: 100vh;
    background-color: #f3f4f6;
}

/* Konten utama */
.main-content {
    flex: 1;
    padding: 2.5rem;
    overflow-x: auto;
}

.results-container {
    max-width: 1000px;
    margin: 0 auto;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 2rem;
    color: #1f2937;
    border-bottom: 2px solid #e5e7eb;
    padding-bottom: 0.75rem;
}

.result-cards-container {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.result-card {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    padding: 1.5rem;
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
}

.result-card:hover {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
}

.card-header {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
    border-bottom: 1px solid #f3f4f6;
    padding-bottom: 1rem;
}

.card-icon {
    width: 24px;
    height: 24px;
    margin-right: 0.75rem;
    color: #9b5de5;
}

.result-card h2 {
    font-size: 18px;
    font-weight: 600;
    color: #374151;
    margin: 0;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

.info-row:not(:last-child) {
    display: flex;
    flex-direction: column;
    border-bottom: 1px solid #f3f4f6;
    padding-bottom: 0.75rem;
}

.info-label {
    font-size: 14px;
    color: #6b7280;
    margin: 0 0 0.25rem 0;
}

.info-value {
    font-size: 16px;
    font-weight: 500;
    color: #111827;
    margin: 0;
}

.score {
    font-size: 20px;
    font-weight: 700;
    color: #9b5de5;
}

.category-badge {
    display: inline-block;
    padding: 6px 12px;
    background-color: #f3e8ff;
    color: #9a5de5;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px; /* Disesuaikan agar tidak terlalu besar */
    text-transform: capitalize; /* Membuatnya lebih rapi */
    max-width: 100%;
    word-wrap: break-word;
}

.additional-stats {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid #f3f4f6;
}

.stats-title {
    font-size: 16px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background-color: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.stat-item:hover {
    background-color: #f1f5f9;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f3e8ff;
    border-radius: 8px;
    flex-shrink: 0;
}

.stat-icon svg {
    width: 20px;
    height: 20px;
    color: #9b5de5;
}

.stat-content {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    flex: 1;
}

.stat-label {
    font-size: 12px;
    color: #6b7280;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 16px;
    font-weight: 700;
    color: #1f2937;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

.btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    background-color: #9b5de5;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    font-size: 16px;
    transition: all 0.3s;
    text-decoration: none;
}

.btn:hover {
    background-color: #8b5cf6;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.2);
}

.download-btn {
    background-color: #9b5de5;
}
.secondary-btn {
    background-color: #6b7280;
}
.secondary-btn:hover {
    background-color: #4b5563;
}
.disabled-btn {
    background-color: #d1d5db;
    cursor: not-allowed;
}
.disabled-btn:hover {
    transform: none;
    box-shadow: none;
}

.btn-icon {
    width: 18px;
    height: 18px;
}

/* Responsive */
@media (min-width: 768px) {
    .info-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        padding: 1.5rem;
    }
    .page-title {
        font-size: 24px;
    }
    .form-actions {
        flex-direction: column;
    }
    .stats-grid {
        grid-template-columns: 1fr;
    }
    .stat-item {
        padding: 0.75rem;
    }
}
@media (min-width: 1024px) {
    .result-cards-container {
        grid-template-columns: 1fr 1fr;
    }
    .info-grid {
        grid-template-columns: 1fr;
    }
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
