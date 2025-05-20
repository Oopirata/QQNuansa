<template>
    <Head title="Psikotest Entry" />

    <div class="psikotest-entry-container">
        <h1 class="test-title">Psikotest Entry</h1>

        <!-- Tampilkan error dari TestForm jika ada -->
        <div v-if="entryError" class="error-notification">
            {{ entryError }}
        </div>

        <div class="entry-form-section">
            <div class="form-card">
                <div class="card-header">
                    <div class="icon-circle">
                        <span class="icon">🔑</span>
                    </div>
                    <h2>Masukkan Kode Akses</h2>
                </div>

                <p class="instruction">
                    Masukkan kode akses yang telah diberikan untuk memulai
                    psikotest
                </p>

                <div class="form-group">
                    <input
                        type="text"
                        v-model="accessCode"
                        placeholder="Kode Akses"
                        class="form-input"
                        :disabled="loading"
                    />
                </div>

                <div v-if="error" class="error-message">{{ error }}</div>

                <button
                    @click="verifyCode"
                    class="verify-button"
                    :disabled="loading || !accessCode"
                >
                    <span v-if="loading" class="loading-spinner"></span>
                    {{ loading ? "Memverifikasi..." : "Verifikasi Kode" }}
                </button>
            </div>
        </div>

        <div class="info-section">
            <div class="info-card">
                <div class="info-icon">ℹ️</div>
                <div class="info-content">
                    <h3>Informasi Psikotest</h3>
                    <p>
                        Pastikan Anda sudah menerima kode akses yang valid dari
                        administrator sebelum melanjutkan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import axios from "axios";

export default {
    components: {
        Head,
    },
    data() {
        return {
            accessCode: "",
            loading: false,
            error: null,
            entryError: localStorage.getItem("entry_error") || null,
            codeVerified: false,
        };
    },
    created() {
        // Hapus error dari localStorage jika ada
        if (this.entryError) {
            localStorage.removeItem("entry_error");
        }

        // Hapus data lama jika masuk ke halaman entry
        localStorage.removeItem("psychotest_participant");
        localStorage.removeItem("psychotest_token");
    },
    methods: {
        verifyCode() {
            this.loading = true;
            this.error = null;

            axios
                .post("/api/psychotest/verify-code", { code: this.accessCode })
                .then((response) => {
                    console.log("Verify code response:", response.data);

                    if (response.data.status === "new") {
                        // Simpan kode akses untuk digunakan nanti
                        localStorage.setItem(
                            "psychotest_access_code",
                            this.accessCode
                        );
                        localStorage.setItem(
                            "psychotest_session",
                            JSON.stringify(response.data.sessionData)
                        );

                        // Untuk kode baru, redirect ke halaman test
                        // FormPage akan meminta data user dan melakukan startTest
                        window.location.href = "/psychotest/test";
                    } else if (response.data.status === "resume") {
                        // Untuk resume, simpan data participant dan langsung redirect
                        localStorage.setItem(
                            "psychotest_participant",
                            JSON.stringify(response.data.participantData)
                        );
                        localStorage.setItem(
                            "psychotest_session",
                            JSON.stringify(response.data.sessionData)
                        );
                        localStorage.setItem(
                            "psychotest_token",
                            response.data.participantData.session_token
                        );

                        // Redirect ke halaman test
                        window.location.href = "/psychotest/test";
                    }
                })
                .catch((error) => {
                    console.error("Verification error:", error);
                    this.error =
                        error.response?.data?.message ||
                        error.response?.data?.detail ||
                        "Terjadi kesalahan saat verifikasi kode.";
                    this.loading = false;
                });
        },
    },
};
</script>

<style scoped>
:deep(*) {
    font-family: "Kaisei Opti", sans-serif;
}
.psikotest-entry-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 20px;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.test-title {
    color: #333;
    text-align: center;
    margin-bottom: 2rem;
    font-size: 2rem;
    font-weight: 600;
}

.form-card {
    background-color: #ffffff;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    margin-bottom: 1.5rem;
}

.card-header {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
}

.icon-circle {
    width: 48px;
    height: 48px;
    background-color: #f1e6ff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
}

.icon {
    font-size: 24px;
}

.card-header h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.instruction {
    margin-bottom: 1.5rem;
    color: #666;
    font-size: 1rem;
    line-height: 1.5;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s ease;
}

.form-input:focus {
    border-color: #9c27b0;
    outline: none;
    box-shadow: 0 0 0 3px rgba(156, 39, 176, 0.1);
}

.verify-button {
    width: 100%;
    padding: 14px;
    background-color: #9c27b0;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    transition: background-color 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.verify-button:hover {
    background-color: #7b1fa2;
}

.verify-button:disabled {
    background-color: #e0e0e0;
    color: #9e9e9e;
    cursor: not-allowed;
}

.loading-spinner {
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: #fff;
    animation: spin 1s ease-in-out infinite;
    margin-right: 10px;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.error-message {
    color: #f44336;
    margin-bottom: 15px;
    font-size: 14px;
    padding: 8px 0;
}

.error-notification {
    background-color: #fee2e2;
    border-left: 4px solid #ef4444;
    color: #b91c1c;
    padding: 12px 15px;
    margin-bottom: 20px;
    border-radius: 8px;
    font-size: 14px;
}

.info-section {
    padding: 1rem 0;
}

.info-card {
    display: flex;
    background-color: #f5f5f5;
    padding: 1.5rem;
    border-radius: 8px;
    border-left: 4px solid #9c27b0;
}

.info-icon {
    font-size: 1.5rem;
    margin-right: 1rem;
}

.info-content h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.1rem;
    color: #333;
}

.info-content p {
    margin: 0;
    font-size: 0.9rem;
    color: #666;
    line-height: 1.5;
}
</style>
