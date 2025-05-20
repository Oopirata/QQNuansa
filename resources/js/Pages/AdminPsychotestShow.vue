<script>
import dayjs from "dayjs";
import axios from "axios";
import { Head } from "@inertiajs/vue3";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";

export default {
    props: {
        session: Object,
    },
    components: {
        Sidebar,
        Head,
    },
    data() {
        return {
            search: "",
            statusFilter: "all",
            statusLabels: {
                unused: "Belum Digunakan",
                active: "Sedang Digunakan",
                completed: "Selesai",
                abandoned: "Tidak Selesai",
            },
        };
    },
    computed: {
        filteredCodes() {
            return this.session.access_codes.filter((code) => {
                if (
                    this.statusFilter !== "all" &&
                    code.status !== this.statusFilter
                ) {
                    return false;
                }
                if (this.search) {
                    const searchLower = this.search.toLowerCase();
                    if (code.code.toLowerCase().includes(searchLower))
                        return true;
                    if (
                        code.participant &&
                        code.participant.name
                            .toLowerCase()
                            .includes(searchLower)
                    )
                        return true;
                    return false;
                }
                return true;
            });
        },
        completedCount() {
            return this.session.access_codes.filter(
                (c) => c.status === "completed"
            ).length;
        },
        activeCount() {
            return this.session.access_codes.filter(
                (c) => c.status === "active"
            ).length;
        },
        unusedCount() {
            return this.session.access_codes.filter(
                (c) => c.status === "unused"
            ).length;
        },
    },
    methods: {
        copyAllCodes() {
            // Cek apakah navigator.clipboard tersedia
            if (navigator.clipboard) {
                const allCodes = this.filteredCodes
                    .map((code) => code.code)
                    .join("\n");

                navigator.clipboard
                    .writeText(allCodes)
                    .then(() => {
                        alert("Semua kode akses berhasil disalin ke clipboard");
                    })
                    .catch((error) => {
                        console.error("Gagal menyalin kode:", error);
                        alert("Gagal menyalin kode");
                    });
            } else {
                // Jika clipboard tidak tersedia, fallback ke execCommand
                const allCodes = this.filteredCodes
                    .map((code) => code.code)
                    .join("\n");

                // Buat textarea sementara untuk menyalin
                const textArea = document.createElement("textarea");
                textArea.value = allCodes;
                document.body.appendChild(textArea);
                textArea.select();
                textArea.setSelectionRange(0, 99999); // Untuk mobile
                try {
                    document.execCommand("copy"); // Salin menggunakan execCommand
                    alert("Semua kode akses berhasil disalin ke clipboard");
                } catch (err) {
                    console.error("Gagal menyalin kode:", err);
                    alert("Gagal menyalin kode");
                }
                document.body.removeChild(textArea);
            }
        },
        formatDateRange(start, end) {
            const startDate = dayjs(start);
            const endDate = dayjs(end);
            return `${startDate.format("D MMM YYYY HH:mm")} - ${endDate.format(
                "D MMM YYYY HH:mm"
            )}`;
        },
        formatDateTime(datetime) {
            return dayjs(datetime).format("D MMM YYYY HH:mm");
        },
        toggleSessionStatus() {
            axios
                .patch(`/api/psychotest/sessions/${this.session.id}/toggle`)
                .then(() => {
                    window.location.reload();
                })
                .catch((error) => {
                    alert("Terjadi kesalahan saat mengubah status sesi");
                    console.error("Error toggling session status:", error);
                });
        },
        exportResults() {
            window.location.href = `/api/psychotest/sessions/${this.session.id}/export`;
        },
        copyCode(code) {
            navigator.clipboard
                .writeText(code)
                .then(() => {
                    alert("Kode berhasil disalin ke clipboard");
                })
                .catch(() => {
                    alert("Gagal menyalin kode");
                });
        },
        resetCode(codeId) {
            if (
                confirm(
                    "Apakah Anda yakin ingin mereset kode ini? Semua progres peserta akan hilang."
                )
            ) {
                axios
                    .post(`/api/psychotest/codes/${codeId}/reset`)
                    .then(() => {
                        window.location.reload();
                    })
                    .catch((error) => {
                        alert("Terjadi kesalahan saat mereset kode");
                        console.error("Error resetting code:", error);
                    });
            }
        },
        viewResults(participantId) {
            window.open(`/psychotest/results/${participantId}`, "_blank");
        },
    },
};
</script>

<template>
    <Head title="Admin Psikotest" />
    <div class="app-container">
        <!-- Sidebar -->
        <Sidebar :user="$page.props.auth.user" />

        <!-- Konten utama -->
        <main class="main-content">
            <div class="session-container">
                <a href="/admin/psychotest" class="text-xl">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="page-title">Detail Sesi Psikotest</h1>

                <div class="session-card">
                    <div class="session-header">
                        <h2 class="session-name">{{ session.name }}</h2>
                        <div class="session-meta">
                            <span class="date-range">
                                {{
                                    formatDateRange(
                                        session.start_date,
                                        session.end_date
                                    )
                                }}
                            </span>
                            <span
                                :class="[
                                    'status-badge',
                                    session.is_active ? 'active' : 'inactive',
                                ]"
                            >
                                {{
                                    session.is_active ? "Aktif" : "Tidak Aktif"
                                }}
                            </span>
                        </div>
                    </div>

                    <div class="session-description" v-if="session.description">
                        <p>{{ session.description }}</p>
                    </div>

                    <div class="session-stats">
                        <div class="stat-card">
                            <div class="stat-value">{{ completedCount }}</div>
                            <div class="stat-label">Selesai</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">{{ activeCount }}</div>
                            <div class="stat-label">Dalam Proses</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">{{ unusedCount }}</div>
                            <div class="stat-label">Belum Digunakan</div>
                        </div>
                    </div>

                    <div class="session-actions">
                        <button
                            @click="toggleSessionStatus"
                            class="btn toggle-btn"
                        >
                            {{
                                session.is_active
                                    ? "Nonaktifkan Sesi"
                                    : "Aktifkan Sesi"
                            }}
                        </button>
                        <button @click="copyAllCodes" class="btn">
                            Salin Semua Kode
                        </button>
                    </div>
                </div>

                <div class="access-codes">
                    <h3 class="section-title">Kode Akses</h3>

                    <div class="filter-controls">
                        <input
                            type="text"
                            v-model="search"
                            class="input"
                            placeholder="Cari kode atau nama peserta..."
                        />
                        <select v-model="statusFilter" class="input">
                            <option value="all">Semua Status</option>
                            <option value="unused">Belum Digunakan</option>
                            <option value="active">Sedang Digunakan</option>
                            <option value="completed">Selesai</option>
                            <option value="abandoned">Tidak Selesai</option>
                        </select>
                    </div>

                    <table class="access-table">
                        <thead>
                            <tr>
                                <th>Kode Akses</th>
                                <th>Status</th>
                                <th>Peserta</th>
                                <th>Waktu Mulai</th>
                                <th>Waktu Selesai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="code in filteredCodes" :key="code.id">
                                <td>{{ code.code }}</td>
                                <td>
                                    <span
                                        :class="['status-badge', code.status]"
                                    >
                                        {{ statusLabels[code.status] }}
                                    </span>
                                </td>
                                <td>
                                    {{
                                        code.participant
                                            ? code.participant.name
                                            : "-"
                                    }}
                                </td>
                                <td>
                                    {{
                                        code.participant?.start_time
                                            ? formatDateTime(
                                                  code.participant.start_time
                                              )
                                            : "-"
                                    }}
                                </td>
                                <td>
                                    {{
                                        code.participant?.finish_time
                                            ? formatDateTime(
                                                  code.participant.finish_time
                                              )
                                            : "-"
                                    }}
                                </td>
                                <td>
                                    <button
                                        v-if="
                                            code.participant &&
                                            code.status === 'completed'
                                        "
                                        @click="
                                            viewResults(code.participant.id)
                                        "
                                        class="btn small"
                                    >
                                        Lihat Hasil
                                    </button>
                                    <button
                                        v-if="code.status === 'unused'"
                                        @click="copyCode(code.code)"
                                        class="btn small"
                                    >
                                        Salin
                                    </button>
                                    <button
                                        v-if="code.status === 'active'"
                                        @click="resetCode(code.id)"
                                        class="btn small danger"
                                    >
                                        Reset
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
:deep(*) {
    font-family: "Kaisei Opti", sans-serif;
}
.app-container {
    display: flex;
    min-height: 100vh;
}

/* Sidebar sudah fixed di komponen sidebar, 
jadi di sini main-content harus punya margin kiri agar tidak tertindih */
.main-content {
    flex: 1;
    margin-left: 16rem; /* sesuaikan dengan lebar sidebar */
    padding: 2rem;
    background-color: #f9fafb;
    overflow-x: auto;
}

.session-container {
    max-width: 1200px;
    margin: auto;
}

.page-title {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 1.5rem;
}

.session-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    padding: 2rem;
    margin-bottom: 2rem;
}

.session-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.session-name {
    font-size: 20px;
    font-weight: 500;
}

.session-meta {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.status-badge {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    text-transform: capitalize;
    background-color: #e0e0e0;
    color: #555;
}
.status-badge.active {
    background-color: #d1fae5;
    color: #059669;
}
.status-badge.inactive {
    background-color: #fee2e2;
    color: #b91c1c;
}
.status-badge.unused {
    background-color: #e5e7eb;
    color: #6b7280;
}
.status-badge.completed {
    background-color: #dbeafe;
    color: #2563eb;
}
.status-badge.abandoned {
    background-color: #f3f4f6;
    color: #9ca3af;
}

.session-description {
    margin-top: 1rem;
    color: #555;
}

.session-stats {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.stat-card {
    flex: 1;
    background: #f9fafb;
    border-radius: 8px;
    padding: 1rem;
    text-align: center;
}
.stat-value {
    font-size: 20px;
    font-weight: bold;
    color: #111827;
}
.stat-label {
    color: #6b7280;
    margin-top: 0.25rem;
}

.session-actions {
    margin-top: 1.5rem;
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn {
    background-color: #9b5de5;
    color: white;
    padding: 10px 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    font-size: 14px;
    transition: background 0.3s;
}
.btn:hover {
    background-color: #7c3aed;
}
.btn.danger {
    background-color: #ef4444;
}
.btn.danger:hover {
    background-color: #dc2626;
}
.btn.small {
    font-size: 12px;
    padding: 6px 10px;
}

.access-codes {
    background: #fff;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.section-title {
    font-size: 18px;
    font-weight: 500;
    margin-bottom: 1rem;
}

.filter-controls {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.input {
    padding: 10px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    flex: 1;
}

.access-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

.access-table th,
.access-table td {
    text-align: left;
    padding: 12px 8px;
    border-bottom: 1px solid #e5e7eb;
}

.access-table th {
    color: #6b7280;
    font-weight: 500;
    font-size: 14px;
}

/* Responsive untuk sidebar dan main content */
@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        padding: 1rem;
    }
}
</style>
