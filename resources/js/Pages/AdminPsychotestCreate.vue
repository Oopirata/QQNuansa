<template>
    <Head title="Admin Psikotest" />
    <div class="app-container">
        <!-- Sidebar -->
        <Sidebar :user="$page.props.auth.user" />

        <!-- Konten utama -->
        <main class="main-content">
            <div class="session-container">
                <h1 class="page-title">Buat Sesi Psikotest Baru</h1>

                <form @submit.prevent="createSession" class="session-card">
                    <div class="form-group">
                        <label for="name">Nama Sesi</label>
                        <input
                            type="text"
                            id="name"
                            v-model="form.name"
                            required
                            :class="['input', { error: errors.name }]"
                        />
                        <div v-if="errors.name" class="error-message">
                            {{ errors.name }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi (Opsional)</label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            rows="4"
                            :class="['input', { error: errors.description }]"
                        ></textarea>
                        <div v-if="errors.description" class="error-message">
                            {{ errors.description }}
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="start_date">Tanggal Mulai</label>
                            <input
                                type="datetime-local"
                                id="start_date"
                                v-model="form.start_date"
                                required
                                :class="['input', { error: errors.start_date }]"
                            />
                            <div v-if="errors.start_date" class="error-message">
                                {{ errors.start_date }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="end_date">Tanggal Berakhir</label>
                            <input
                                type="datetime-local"
                                id="end_date"
                                v-model="form.end_date"
                                required
                                :class="['input', { error: errors.end_date }]"
                            />
                            <div v-if="errors.end_date" class="error-message">
                                {{ errors.end_date }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="max_participants">Jumlah Peserta</label>
                        <input
                            type="number"
                            id="max_participants"
                            v-model="form.max_participants"
                            min="1"
                            required
                            :class="[
                                'input',
                                { error: errors.max_participants },
                            ]"
                        />
                        <div
                            v-if="errors.max_participants"
                            class="error-message"
                        >
                            {{ errors.max_participants }}
                        </div>
                        <div class="help-text">
                            Jumlah kode akses yang akan dibuat untuk sesi ini
                        </div>
                    </div>

                    <div class="form-actions">
                        <Link
                            :href="route('admin.psychotest.index')"
                            class="btn danger"
                        >
                            Batal
                        </Link>
                        <button type="submit" class="btn" :disabled="loading">
                            {{ loading ? "Memproses..." : "Buat Sesi" }}
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</template>

<script>
import { Link } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { Head } from "@inertiajs/vue3";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";

export default {
    components: { Link, Sidebar, Head },
    data() {
        return {
            form: {
                name: "",
                description: "",
                start_date: "",
                end_date: "",
                max_participants: 10,
            },
            loading: false,
            errors: {},
        };
    },
    methods: {
        createSession() {
            this.loading = true;
            this.errors = {};

            Inertia.post(route("admin.psychotest.store"), this.form, {
                onSuccess: () => {
                    // Redirect ke halaman index setelah sukses
                    Inertia.visit(route("admin.psychotest.index"));
                },
                onError: (errors) => {
                    this.errors = errors;
                },
                onFinish: () => {
                    this.loading = false;
                },
            });
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
   jadi kita beri margin-left supaya konten tidak tertumpuk */
.main-content {
    flex: 1;
    margin-left: 16rem; /* Sesuaikan dengan lebar sidebar */
    padding: 2rem;
    background-color: #f9fafb;
    overflow-x: auto;
}

.session-container {
    max-width: 640px;
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
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.form-group {
    margin-bottom: 1.25rem;
}

label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
    color: #374151;
}

.input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    transition: border 0.2s;
}

.input.error {
    border-color: #ef4444;
    background-color: #fef2f2;
}

textarea.input {
    resize: vertical;
}

.error-message {
    color: #ef4444;
    font-size: 13px;
    margin-top: 4px;
}

.help-text {
    font-size: 13px;
    color: #6b7280;
    margin-top: 4px;
}

.form-row {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.form-row .form-group {
    flex: 1;
}

.form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 2rem;
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
    transition: background-color 0.3s;
    text-decoration: none;
    display: inline-block;
    text-align: center;
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

/* Responsive untuk main content margin */
@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        padding: 1rem;
    }
}
</style>
