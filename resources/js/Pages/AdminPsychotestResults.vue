<template>
    <Head title="Admin Psikotest" />
    <div class="app-container">
        <!-- Sidebar -->
        <Sidebar :user="$page.props.auth.user" />

        <!-- Konten utama -->
        <main class="main-content">
            <button @click="goBack" class="text-xl">
                <i class="fas fa-arrow-left"></i>
            </button>
            <h1 class="text-3xl font-bold">
                Hasil Psikotest - {{ session.name }}
            </h1>

            <table class="access-table">
                <thead>
                    <tr>
                        <th>Nama Peserta</th>
                        <th>Skor</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="participant in participants"
                        :key="participant.id"
                    >
                        <td>{{ participant.name }}</td>
                        <td>{{ participant.testResults.iq }}</td>
                        <td>{{ participant.testResults.status }}</td>
                        <td>
                            <!-- Tombol untuk mengunduh PDF -->
                            <a
                                :href="`/admin/psychotest/${participant.id}/download-result`"
                                class="btn small"
                            >
                                Download PDF
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </main>
    </div>
</template>

<script>
import axios from "axios";
import { Head } from "@inertiajs/vue3";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";

export default {
    props: {
        session: Object,
        participants: Array,
    },
    components: {
        Head,
        Sidebar,
    },
    methods: {
        goBack() {
            this.$inertia.visit(
                this.route("admin.psychotest.show", this.session.id)
            );
        },
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
}

/* Sidebar posisinya fixed di komponen Sidebar.vue,
   jadi kita beri margin-left pada main-content supaya tidak tertindih */
.main-content {
    flex: 1;
    margin-left: 16rem; /* sesuaikan dengan lebar sidebar */
    padding: 2rem;
    background-color: #f9fafb;
    overflow-x: auto;
}

/* Styling untuk tabel */
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

/* Styling untuk tombol */
.btn {
    background-color: #9b5de5;
    color: white;
    padding: 10px 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    font-size: 14px;
    transition: background-color 0.3s;
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

/* Responsive untuk sidebar dan main content */
@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        padding: 1rem;
    }
}
</style>
