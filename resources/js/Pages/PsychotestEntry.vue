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
                <h2>Masukkan Kode Akses</h2>
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
                    {{ loading ? "Memverifikasi..." : "Verifikasi Kode" }}
                </button>
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
.psikotest-entry-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    font-family: Arial, sans-serif;
}

.test-title {
    color: #333;
    text-align: center;
    margin-bottom: 30px;
}

.form-card {
    background-color: #f9f9f9;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.instruction {
    margin-bottom: 20px;
    color: #555;
}

.form-group {
    margin-bottom: 20px;
}

.form-input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
}

.verify-button {
    width: 100%;
    padding: 12px;
    background-color: #4caf50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.verify-button:hover {
    background-color: #45a049;
}

.verify-button:disabled {
    background-color: #cccccc;
    cursor: not-allowed;
}

.error-message {
    color: #f44336;
    margin-bottom: 15px;
}

.error-notification {
    background-color: #fee2e2;
    border-left: 4px solid #ef4444;
    color: #b91c1c;
    padding: 12px 15px;
    margin-bottom: 20px;
    border-radius: 4px;
}
</style>
