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
                                <p class="info-label">Skor Mentah</p>
                                <p class="info-value score">
                                    {{
                                        participant?.test_results?.raw_score ??
                                        "-"
                                    }}
                                </p>
                            </div>
                            <div class="info-row">
                                <p class="info-label">IQ (Estimasi)</p>
                                <p class="info-value score">
                                    {{ participant?.test_results?.iq ?? "-" }}
                                </p>
                            </div>
                            <div class="info-row">
                                <p class="info-label">Kategori</p>
                                <p class="info-value category-badge">
                                    {{
                                        participant?.test_results?.category ??
                                        "-"
                                    }}
                                </p>
                            </div>
                        </div>

                        <div
                            class="result-chart"
                            v-if="participant?.test_results?.iq"
                        >
                            <div class="chart-container">
                                <div class="chart-scale">
                                    <div
                                        class="scale-marker"
                                        v-for="val in [
                                            70, 80, 90, 100, 110, 120, 130,
                                        ]"
                                        :key="val"
                                        :style="{
                                            left: ((val - 70) / 60) * 100 + '%',
                                        }"
                                    >
                                        <div class="marker-line"></div>
                                        <div class="marker-label">
                                            {{ val }}
                                        </div>
                                    </div>
                                </div>
                                <div class="chart-categories">
                                    <div
                                        class="category-bar very-low"
                                        title="Rendah Sekali"
                                    >
                                        <span>Rendah Sekali</span>
                                    </div>
                                    <div
                                        class="category-bar low"
                                        title="Rendah"
                                    >
                                        <span>Rendah</span>
                                    </div>
                                    <div
                                        class="category-bar low-avg"
                                        title="Di bawah rata-rata"
                                    >
                                        <span>Di bawah rata-rata</span>
                                    </div>
                                    <div
                                        class="category-bar average"
                                        title="Rata-rata"
                                    >
                                        <span>Rata-rata</span>
                                    </div>
                                    <div
                                        class="category-bar high-avg"
                                        title="Di atas rata-rata"
                                    >
                                        <span>Di atas rata-rata</span>
                                    </div>
                                    <div
                                        class="category-bar superior"
                                        title="Superior"
                                    >
                                        <span>Superior</span>
                                    </div>
                                    <div
                                        class="category-bar very-superior"
                                        title="Very Superior"
                                    >
                                        <span>Very Superior</span>
                                    </div>
                                </div>
                                <div class="pointer-container">
                                    <div
                                        class="score-pointer"
                                        :style="{
                                            left:
                                                calculatePointerPosition(
                                                    participant?.test_results
                                                        ?.iq
                                                ) + '%',
                                        }"
                                    >
                                        <div class="pointer-triangle"></div>
                                        <div class="pointer-score">
                                            {{ participant?.test_results?.iq }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
import Sidebar from "@/Components/Sidebar/Sidebar.vue";
import dayjs from "dayjs";
import "dayjs/locale/id";

dayjs.locale("id"); // Bahasa Indonesia

export default {
    components: { Head, Sidebar },
    props: {
        participant: Object,
        userRole: String,
    },
    methods: {
        formatDate(date) {
            return dayjs(date).format("D MMMM YYYY");
        },
        normalizeCity(raw) {
            if (!raw) return "";
            return raw
                .toLowerCase()
                .replace(/^kota\s+/i, "")
                .replace(/^kabupaten\s+/i, "")
                .split(" ")
                .map((w) => w.charAt(0).toUpperCase() + w.slice(1))
                .join(" ");
        },
        calculatePointerPosition(iq) {
            // Konversi IQ ke posisi pada skala 70-130
            if (!iq) return 50; // Default di tengah
            const clamped = Math.max(70, Math.min(130, parseInt(iq)));
            return ((clamped - 70) / 60) * 100;
        },
        goBack() {
            if (this.userRole === "admin") {
                window.history.back();
            } else {
                this.$inertia.visit("/servicepsikotest"); // Ganti dengan route yang sesuai
            }
        },
    },
    mounted() {
        console.log("Isi participant:", this.participant);
    },
};
</script>

<style scoped>
:deep(*) {
    font-family: "Kaisei Opti", sans-serif;
}
/* Container utama yang berisi sidebar dan konten */
.app-container {
    display: flex;
    min-height: 100vh;
    background-color: #f3f4f6;
}

/* Sidebar sudah fixed di komponen Sidebar.vue
   Jadi kita beri margin-left pada main-content supaya tidak tertindih */
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

.info-row {
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
    color: #9b5de5;
    border-radius: 8px;
    font-weight: 600;
    font-size: 16px;
}

.result-chart {
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid #f3f4f6;
}

.chart-container {
    position: relative;
    height: 120px;
    width: 100%;
    padding-top: 2rem;
}

.chart-scale {
    position: relative;
    height: 20px;
    width: 100%;
    margin-bottom: 0.5rem;
}

.scale-marker {
    position: absolute;
    transform: translateX(-50%);
}

.marker-line {
    height: 10px;
    width: 1px;
    background-color: #d1d5db;
    margin: 0 auto;
}

.marker-label {
    text-align: center;
    font-size: 12px;
    color: #6b7280;
    margin-top: 4px;
}

.chart-categories {
    display: flex;
    width: 100%;
    height: 30px;
}

.category-bar {
    flex: 1;
    text-align: center;
    font-size: 10px;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    white-space: nowrap;
}

.category-bar span {
    opacity: 0;
    transition: opacity 0.3s;
}

.category-bar:hover span {
    opacity: 1;
}

.very-low {
    background-color: #ef4444;
}

.low {
    background-color: #f97316;
}

.low-avg {
    background-color: #f59e0b;
}

.average {
    background-color: #10b981;
}

.high-avg {
    background-color: #3b82f6;
}

.superior {
    background-color: #6366f1;
}

.very-superior {
    background-color: #8b5cf6;
}

.pointer-container {
    position: relative;
    height: 50px;
    margin-top: 0.5rem;
}

.score-pointer {
    position: absolute;
    transform: translateX(-50%);
}

.pointer-triangle {
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-bottom: 12px solid #1f2937;
    margin: 0 auto;
}

.pointer-score {
    margin-top: 4px;
    background-color: #1f2937;
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: bold;
    text-align: center;
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

.btn-icon {
    width: 18px;
    height: 18px;
}

/* Responsive untuk berbagai ukuran layar */
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
}

@media (min-width: 1024px) {
    .result-cards-container {
        grid-template-columns: 1fr 1fr;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>
