<template>
    <div class="test-container">
        <h1 class="test-title">Psikotest</h1>

        <!-- Loading state -->
        <div v-if="isInitializing" class="loading-message">
            <p>Memuat data test...</p>
        </div>

        <!-- Form Data Diri untuk sesi baru -->
        <div v-else-if="showProfileForm" class="form-section">
            <h2>Lengkapi Data Diri</h2>
            <form @submit.prevent="startNewTest">
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input
                        type="text"
                        id="name"
                        v-model="participantData.name"
                        :disabled="!!userData.name"
                        required
                    />
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        v-model="participantData.email"
                        :disabled="!!userData.email"
                        required
                    />
                </div>

                <div class="form-group">
                    <label for="birthdate">Tanggal Lahir</label>
                    <input
                        type="date"
                        id="birthdate"
                        v-model="participantData.birthdate"
                        required
                    />
                </div>

                <div class="form-group">
                    <label for="gender">Jenis Kelamin</label>
                    <select
                        id="gender"
                        v-model="participantData.gender"
                        required
                    >
                        <option value="">Pilih</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="education">Pendidikan Terakhir</label>
                    <input
                        type="text"
                        id="education"
                        v-model="participantData.education"
                        placeholder="Contoh: S1 Teknik Informatika"
                        required
                    />
                </div>

                <div class="form-actions">
                    <button type="submit" class="submit-btn">Mulai Test</button>
                </div>
            </form>
        </div>

        <!-- Test form yang sudah ada -->
        <div v-else-if="!isSubmitted">
            <form @submit.prevent="submitTest">
                <!-- Tampilkan data user -->
                <div class="participant-info">
                    <p>Nama: {{ participantData?.name }}</p>
                    <p>Email: {{ participantData?.email }}</p>
                    <p>Tanggal Lahir: {{ participantData?.birthdate }}</p>
                    <p>Jenis Kelamin: {{ participantData?.gender }}</p>
                    <p>Pendidikan Terakhir: {{ participantData?.education }}</p>
                    <p>Jawaban saat ini: {{ answers }}</p>
                </div>

                <!-- Progress indicator -->
                <div class="progress-indicator">
                    <div class="progress-bar">
                        <div
                            class="progress-fill"
                            :style="{ width: `${progressPercentage}%` }"
                        ></div>
                    </div>
                    <div class="progress-text">
                        {{ answeredCount }} dari {{ questions.length }} soal ({{
                            progressPercentage
                        }}%)
                    </div>
                </div>

                <!-- Questions Section -->
                <div class="questions-section form-section">
                    <div
                        v-for="(question, index) in questions"
                        :key="'q' + question.id"
                        class="question"
                    >
                        <p class="question-text">
                            {{ index + 1 }}. {{ question.text || "" }}
                        </p>

                        <div
                            v-if="question.imageUrl"
                            class="question-image-container"
                        >
                            <img
                                :src="question.imageUrl"
                                :alt="'Gambar untuk pertanyaan ' + (index + 1)"
                                class="question-image"
                            />
                        </div>

                        <div
                            :class="
                                getOptionsGridClass(question.options.length)
                            "
                        >
                            <div
                                v-for="(option, optIndex) in question.options"
                                :key="'opt' + optIndex"
                                class="option-card"
                            >
                                <label class="option-content">
                                    <input
                                        type="radio"
                                        :name="'question-' + question.id"
                                        :value="String(optIndex + 1)"
                                        v-model="answers[question.id]"
                                        required
                                    />
                                    <div class="option-inner">
                                        <span class="option-text">
                                            {{
                                                option.text ||
                                                "Opsi " + (optIndex + 1)
                                            }}
                                        </span>
                                        <div
                                            v-if="option.imageUrl"
                                            class="option-image-container"
                                        >
                                            <img
                                                :src="option.imageUrl"
                                                :alt="'Opsi ' + (optIndex + 1)"
                                                class="option-image"
                                            />
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button
                        type="submit"
                        class="submit-btn"
                        :disabled="submitting"
                    >
                        {{ submitting ? "Mengirim..." : "Kirim Jawaban" }}
                    </button>
                </div>
            </form>
        </div>

        <div v-else class="success-message">
            <h2>Test Berhasil Dikumpulkan!</h2>
            <p>
                Terima kasih {{ participantData?.name }} telah menyelesaikan
                test ini.
            </p>
            <p v-if="resultUrl">
                <a :href="resultUrl" class="result-link"
                    >Lihat hasil test Anda</a
                >
            </p>
            <p v-else>
                Hasil test akan dikirimkan ke email
                {{ participantData?.email }}.
            </p>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { throttle } from "lodash";
import { questionData } from "../../data/questionData.js";
import { toRaw } from "vue";

export default {
    name: "TestForm",
    props: {
        isNewSession: {
            type: Boolean,
            default: false,
        },
        isResumeSession: {
            type: Boolean,
            default: false,
        },
        userData: {
            type: Object,
            default: () => ({ nama: "", email: "" }),
        },
    },
    data() {
        return {
            isInitializing: true,
            isSubmitted: false,
            submitting: false,
            participantData: {
                name: "",
                email: "",
                birthdate: "",
                gender: "",
                education: "",
                testDate: new Date().toISOString().split("T")[0],
            },
            sessionToken: null,
            questions: [],
            answers: {}, // Standar object { questionId: answer }
            resultUrl: null,
            saveInProgress: false,
            lastSaveData: null,
            accessCode: localStorage.getItem("psychotest_access_code") || "",
            showProfileForm: false,
        };
    },
    computed: {
        answeredCount() {
            return Object.keys(this.answers).length;
        },
        progressPercentage() {
            if (!this.questions.length) return 0;
            return Math.round(
                (this.answeredCount / this.questions.length) * 100
            );
        },
    },
    created() {
        if (this.userData.name) {
            this.participantData.name = this.userData.name;
        }
        if (this.userData.email) {
            this.participantData.email = this.userData.email;
        }
        if (this.userData.birthdate) {
            this.participantData.birthdate = this.userData.birthdate;
        }
        if (this.userData.gender) {
            this.participantData.gender = this.userData.gender;
        }
        if (this.userData.education) {
            this.participantData.education = this.userData.education;
        }
        try {
            this.questions = questionData;

            if (this.isResumeSession) {
                const participantJson = localStorage.getItem(
                    "psychotest_participant"
                );
                this.sessionToken = localStorage.getItem("psychotest_token");

                if (participantJson && this.sessionToken) {
                    this.participantData = JSON.parse(participantJson);
                    this.loadSavedAnswers();
                    this.isInitializing = false;
                } else {
                    throw new Error(
                        "Missing participant data or session token"
                    );
                }
            } else if (this.isNewSession) {
                this.showProfileForm = true;
                this.isInitializing = false;
            } else {
                throw new Error("Invalid session state");
            }

            window.addEventListener("beforeunload", this.handleBeforeUnload);
        } catch (error) {
            console.error("Error initializing TestForm:", error);
            localStorage.setItem(
                "entry_error",
                "Terjadi kesalahan saat memuat test. Silakan mulai kembali."
            );
            window.location.href = "/psychotest/entry";
        }
    },
    beforeUnmount() {
        window.removeEventListener("beforeunload", this.handleBeforeUnload);
    },
    methods: {
        startNewTest() {
            if (!this.accessCode) {
                console.error("Missing access code");
                return;
            }

            axios
                .post("/api/psychotest/start", {
                    code: this.accessCode,
                    name: this.participantData.name,
                    email: this.participantData.email,
                    birthdate: this.participantData.birthdate,
                    gender: this.participantData.gender,
                    education: this.participantData.education,
                    testDate: this.participantData.testDate,
                })
                .then((response) => {
                    this.participantData = response.data.participant;
                    this.sessionToken = response.data.sessionToken;

                    localStorage.setItem(
                        "psychotest_participant",
                        JSON.stringify(this.participantData)
                    );
                    localStorage.setItem("psychotest_token", this.sessionToken);
                    localStorage.removeItem("psychotest_access_code");

                    this.showProfileForm = false;
                })
                .catch((error) => {
                    console.error("Error starting test:", error);
                    alert(
                        "Terjadi kesalahan saat memulai test. Silakan coba lagi."
                    );
                });
        },

        loadSavedAnswers() {
            const savedAnswersJson = localStorage.getItem("psychotest_answers");
            if (savedAnswersJson) {
                try {
                    this.answers = JSON.parse(savedAnswersJson);
                } catch {
                    this.answers = {};
                }
            }

            if (this.participantData?.id && this.sessionToken) {
                axios
                    .get(`/api/psychotest/answers/${this.participantData.id}`, {
                        params: { sessionToken: this.sessionToken },
                    })
                    .then((response) => {
                        if (response.data.answers) {
                            this.answers = response.data.answers;
                            localStorage.setItem(
                                "psychotest_answers",
                                JSON.stringify(this.answers)
                            );
                        }
                    })
                    .catch((error) => {
                        console.error("Error loading saved answers:", error);
                    });
            }
        },

        saveProgress: throttle(function () {
            if (this.saveInProgress) return;
            if (Object.keys(this.answers).length === 0) return;

            const currentAnswers = JSON.stringify(this.answers);
            if (this.lastSaveData === currentAnswers) return;

            if (!this.participantData?.id || !this.sessionToken) {
                this.saveInProgress = false;
                return;
            }

            this.saveInProgress = true;
            this.lastSaveData = currentAnswers;
            localStorage.setItem("psychotest_answers", currentAnswers);

            const plainAnswers = JSON.parse(JSON.stringify(this.answers));

            axios
                .post("/api/psychotest/save-progress", {
                    participant_id: this.participantData.id,
                    sessionToken: this.sessionToken,
                    answers: plainAnswers,
                })
                .then(() => {})
                .catch((error) => {
                    console.error("Error saving progress:", error);
                })
                .finally(() => {
                    this.saveInProgress = false;
                });
        }, 1500),

        submitTest() {
            if (this.submitting) return;

            if (Object.keys(this.answers).length !== this.questions.length) {
                alert("Harap isi semua jawaban sebelum submit.");
                return;
            }

            this.submitting = true;

            const plainAnswers = toRaw(this.answers);

            axios
                .post("/api/psychotest/complete", {
                    participant_id: this.participantData.id,
                    sessionToken: this.sessionToken,
                    answers: plainAnswers,
                    birthdate: this.participantData.birthdate,
                    gender: this.participantData.gender,
                    education: this.participantData.education,
                })
                .then((response) => {
                    this.isSubmitted = true;
                    this.resultUrl = response.data.resultUrl;

                    localStorage.removeItem("psychotest_participant");
                    localStorage.removeItem("psychotest_token");
                    localStorage.removeItem("psychotest_questions");
                    localStorage.removeItem("psychotest_answers");
                })
                .catch((error) => {
                    alert(
                        "Terjadi kesalahan saat mengirim jawaban. Silakan coba lagi."
                    );
                    console.error("Error submitting test:", error);
                })
                .finally(() => {
                    this.submitting = false;
                });
        },

        handleBeforeUnload(event) {
            try {
                if (Object.keys(this.answers).length > 0) {
                    localStorage.setItem(
                        "psychotest_answers",
                        JSON.stringify(this.answers)
                    );
                }
            } catch (e) {
                // ignore error
            }

            const confirmationMessage =
                "Apakah Anda yakin ingin meninggalkan halaman? Progres Anda sudah tersimpan.";
            event.returnValue = confirmationMessage;
            return confirmationMessage;
        },

        getOptionsGridClass(optionCount) {
            if (optionCount > 6) return "options-grid-four-columns";
            return "options-grid-three-columns";
        },
    },

    watch: {
        answers: {
            handler() {
                this.saveProgress();
            },
            deep: true,
        },
    },
};
</script>

<style scoped>
.participant-info {
    background-color: #f0f7ff;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    border-left: 4px solid #4285f4;
}

.progress-indicator {
    margin-bottom: 20px;
}

.progress-bar {
    height: 12px;
    background-color: #eee;
    border-radius: 6px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background-color: #4caf50;
    transition: width 0.3s ease;
}

.progress-text {
    margin-top: 5px;
    text-align: right;
    font-size: 14px;
    color: #666;
}

.result-link {
    display: inline-block;
    margin-top: 10px;
    padding: 10px 15px;
    background-color: #4285f4;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-weight: bold;
}

.result-link:hover {
    background-color: #3367d6;
}

.test-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    font-family: Arial, sans-serif;
}

.test-title {
    color: #333;
    text-align: center;
    margin-bottom: 30px;
}

.form-section {
    background-color: #f9f9f9;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="text"],
input[type="email"],
input[type="date"],
select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
}

input[disabled] {
    background-color: #f0f0f0;
    color: #666666;
    cursor: not-allowed;
}

.question {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.question-text {
    font-weight: bold;
    margin-bottom: 15px;
}

.question-image-container {
    display: flex;
    justify-content: center;
}

.question-image {
    max-width: 100%;
    height: auto;
    margin: 15px 0;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.options-grid-three-columns {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-top: 10px;
}

.options-grid-four-columns {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
    margin-top: 10px;
}

.option-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #fff;
    transition: transform 0.2s, box-shadow 0.2s;
    overflow: hidden;
    position: relative;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.option-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.option-content {
    display: block;
    width: 100%;
    height: 100%;
    cursor: pointer;
    font-weight: normal;
    padding: 0;
    margin: 0;
}

.option-inner {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 15px 12px;
    min-height: 120px;
}

.option-text {
    margin-bottom: 10px;
    text-align: center;
    font-size: 14px;
    color: #333;
}

.option-image-container {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
}

.option-image {
    max-width: 90%;
    max-height: 120px;
    object-fit: contain;
    border-radius: 4px;
}

.option-card input[type="radio"]:checked + .option-inner {
    background-color: #e6f7ff;
}

.option-card input[type="radio"] {
    position: absolute;
    top: 10px;
    right: 10px;
}

.form-actions {
    text-align: center;
    margin-top: 30px;
}

.submit-btn {
    background-color: #4caf50;
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

.submit-btn:hover {
    background-color: #45a049;
}

.success-message {
    text-align: center;
    padding: 30px;
    background-color: #f0f7f0;
    border-radius: 8px;
}

/* Responsive */
@media (max-width: 768px) {
    .options-grid-three-columns,
    .options-grid-four-columns {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .options-grid-three-columns,
    .options-grid-four-columns {
        grid-template-columns: 1fr;
    }
}
</style>
