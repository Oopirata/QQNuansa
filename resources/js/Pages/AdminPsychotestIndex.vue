<template>
    <Head title="Admin Psikotest" />
    <div class="app-container">
        <!-- Sidebar -->
        <Sidebar :user="$page.props.auth.user" />

        <!-- Konten utama -->
        <main class="main-content">
            <div class="session-container">
                <h1 class="page-title">Manajemen Sesi Psikotest</h1>

                <div class="session-actions">
                    <Link :href="route('admin.psychotest.create')" class="btn">
                        + Buat Sesi Baru
                    </Link>
                </div>

                <div class="session-card">
                    <table class="access-table">
                        <thead>
                            <tr>
                                <th>Nama Sesi</th>
                                <th>Tanggal</th>
                                <th>Partisipan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="session in sessions" :key="session.id">
                                <td>{{ session.name }}</td>
                                <td>
                                    {{
                                        formatDateRange(
                                            session.start_date,
                                            session.end_date
                                        )
                                    }}
                                </td>
                                <td>
                                    {{ session.used_codes }} /
                                    {{ session.total_codes }}
                                </td>
                                <td>
                                    <span
                                        :class="[
                                            'status-badge',
                                            session.is_active
                                                ? 'active'
                                                : 'inactive',
                                        ]"
                                    >
                                        {{
                                            session.is_active
                                                ? "Aktif"
                                                : "Tidak Aktif"
                                        }}
                                    </span>
                                </td>
                                <td>
                                    <Link
                                        :href="
                                            route(
                                                'admin.psychotest.show',
                                                session.id
                                            )
                                        "
                                        class="btn small"
                                    >
                                        Detail
                                    </Link>
                                </td>
                            </tr>
                            <tr v-if="sessions.length === 0">
                                <td colspan="5" class="empty-state">
                                    Belum ada sesi psikotest yang dibuat
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
import { Link, Head } from "@inertiajs/vue3";
import dayjs from "dayjs";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";

export default {
    components: {
        Link,
        Sidebar,
        Head,
    },
    props: {
        sessions: Array,
    },
    methods: {
        formatDateRange(start, end) {
            const startDate = dayjs(start);
            const endDate = dayjs(end);

            if (
                startDate.year() === endDate.year() &&
                startDate.month() === endDate.month() &&
                startDate.date() === endDate.date()
            ) {
                return `${startDate.format("D MMM YYYY")}`;
            } else {
                return `${startDate.format("D MMM")} - ${endDate.format(
                    "D MMM YYYY"
                )}`;
            }
        },
    },
};
</script>

<style scoped>
/* Container utama yang berisi sidebar dan konten */
.app-container {
    display: flex;
    min-height: 100vh;
}

/* Sidebar sudah fixed di komponen Sidebar.vue
   Jadi kita beri margin-left pada main-content supaya tidak tertindih */
.main-content {
    flex: 1;
    margin-left: 16rem; /* Sesuaikan dengan lebar sidebar */
    padding: 2rem;
    background-color: #f9fafb;
    overflow-x: auto;
}

.session-container {
    max-width: 1200px;
    margin: auto;
    font-family: "Inter", sans-serif;
}

.page-title {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: #1f2937;
}

.session-card {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    padding: 2rem;
    margin-bottom: 2rem;
}

.session-actions {
    margin-bottom: 1.5rem;
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
    text-decoration: none;
    display: inline-block;
    text-align: center;
}
.btn:hover {
    background-color: #7c3aed;
}
.btn.small {
    font-size: 12px;
    padding: 6px 10px;
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
    background-color: #fef3c7;
    color: #92400e;
}
.status-badge.inactive {
    background-color: #fee2e2;
    color: #b91c1c;
}

/* Table style */
.access-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
    background-color: #ffffff;
}

.access-table th,
.access-table td {
    text-align: left;
    padding: 12px 8px;
    border-bottom: 1px solid #e5e7eb;
    color: #1f2937;
    font-size: 14px;
}

.access-table th {
    color: #6b7280;
    font-weight: 500;
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 1rem;
    color: #6b7280;
    font-style: italic;
}

/* Responsive untuk main content margin */
@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        padding: 1rem;
    }
}
</style>
