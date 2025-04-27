<template>
    <div class="test-container">
        <h1 class="test-title">Formulir Test</h1>

        <div v-if="!isSubmitted">
            <form @submit.prevent="submitTest">
                <div class="form-section">
                    <h2>Data Pribadi</h2>
                    <div class="form-group">
                        <label for="name">Nama Lengkap:</label>
                        <input
                            type="text"
                            id="name"
                            v-model="userData.name"
                            required
                        />
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input
                            type="email"
                            id="email"
                            v-model="userData.email"
                            required
                        />
                    </div>

                    <!-- Tanggal Lahir dengan Fitur Kalender -->
                    <div class="form-group">
                        <label for="birthdate">Tanggal Lahir:</label>
                        <input
                            type="date"
                            id="birthdate"
                            v-model="userData.birthdate"
                            required
                        />
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="form-group">
                        <label>Jenis Kelamin:</label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input
                                    type="radio"
                                    name="gender"
                                    value="Laki-laki"
                                    v-model="userData.gender"
                                    required
                                />
                                Laki-laki
                            </label>
                            <label class="radio-label">
                                <input
                                    type="radio"
                                    name="gender"
                                    value="Perempuan"
                                    v-model="userData.gender"
                                    required
                                />
                                Perempuan
                            </label>
                        </div>
                    </div>

                    <!-- Pendidikan Terakhir -->
                    <div class="form-group">
                        <label for="education">Pendidikan Terakhir:</label>
                        <select
                            id="education"
                            v-model="userData.education"
                            required
                        >
                            <option value="" disabled selected>
                                Pilih pendidikan terakhir
                            </option>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA/SMK">SMA/SMK</option>
                            <option value="D3">D3</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                    </div>

                    <!-- Tanggal Test dengan Fitur Kalender -->
                    <div class="form-group">
                        <label for="testDate">Tanggal Test:</label>
                        <input
                            type="date"
                            id="testDate"
                            v-model="userData.testDate"
                            required
                        />
                    </div>
                </div>

                <!-- Section Pertanyaan Pilihan Ganda dengan Gambar -->
                <div class="questions-section form-section">
                    <div
                        v-for="(question, index) in questions.multipleChoice"
                        :key="'mc' + index"
                        class="question"
                    >
                        <p class="question-text">
                            {{ index + 1 }}. {{ question.text }}
                        </p>

                        <!-- Menampilkan gambar jika pertanyaan memiliki gambar -->
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
                            v-for="(option, optIndex) in question.options"
                            :key="'opt' + optIndex"
                            class="option"
                        >
                            <label>
                                <input
                                    type="radio"
                                    :name="'question' + index"
                                    :value="option.text || option"
                                    v-model="answers.multipleChoice[index]"
                                    required
                                />
                                <!-- Menampilkan teks opsi atau gambar opsi jika ada -->
                                <span v-if="typeof option === 'string'">{{
                                    option
                                }}</span>
                                <template v-else>
                                    <span>{{ option.text }}</span>
                                    <img
                                        v-if="option.imageUrl"
                                        :src="option.imageUrl"
                                        :alt="'Opsi ' + option.text"
                                        class="option-image"
                                    />
                                </template>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Section Pertanyaan Essay dengan Gambar -->

                <div class="form-actions">
                    <button type="submit" class="submit-btn">
                        Kirim Jawaban
                    </button>
                </div>
            </form>
        </div>

        <div v-else class="success-message">
            <h2>Test Berhasil Dikumpulkan!</h2>
            <p>
                Terima kasih {{ userData.name }} telah menyelesaikan test ini.
            </p>
            <p>Hasil test akan dikirimkan ke email {{ userData.email }}.</p>
            <button @click="resetTest" class="reset-btn">Ulangi Test</button>
        </div>
    </div>
</template>

<script>
export default {
    name: "TestForm",
    data() {
        return {
            isSubmitted: false,
            userData: {
                name: "",
                email: "",
                birthdate: "",
                gender: "",
                education: "",
                testDate: "",
            },
            questions: {
                multipleChoice: [
                    {
                        imageUrl: "images/gambar soal form/A1.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/A1-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A1-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A1-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A1-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A1-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A1-6.png",
                            },
                        ],
                    },
                    {
                        imageUrl: "images/gambar soal form/A2.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/A2-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A2-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A2-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A2-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A2-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A2-6.png",
                            },
                        ],
                    },
                    {
                        imageUrl: "images/gambar soal form/A3.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/A3-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A3-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A3-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A3-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A3-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A3-6.png",
                            },
                        ],
                    },
                    {
                        imageUrl: "images/gambar soal form/A4.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/A4-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A4-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A4-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A4-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A4-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A4-6.png",
                            },
                        ],
                    },
                    {
                        imageUrl: "images/gambar soal form/A5.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/A5-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A5-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A5-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A5-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A5-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A5-6.png",
                            },
                        ],
                    },
                    {
                        imageUrl: "images/gambar soal form/A6.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/A6-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A6-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A6-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A6-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A6-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A6-6.png",
                            },
                        ],
                    },
                    {
                        imageUrl: "images/gambar soal form/A7.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/A7-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A7-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A7-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A7-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A7-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A7-6.png",
                            },
                        ],
                    },
                    {
                        imageUrl: "images/gambar soal form/A8.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/A8-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A8-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A8-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A8-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A8-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A8-6.png",
                            },
                        ],
                    },
                    {
                        imageUrl: "images/gambar soal form/A9.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/A9-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A9-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A9-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A9-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A9-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A9-6.png",
                            },
                        ],
                    },
                    {
                        imageUrl: "images/gambar soal form/A10.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/A10-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A10-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A10-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A10-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A10-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A10-6.png",
                            },
                        ],
                    },
                    {
                        imageUrl: "images/gambar soal form/A11.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/A11-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A11-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A11-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A11-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A11-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A11-6.png",
                            },
                        ],
                    },
                    {
                        imageUrl: "images/gambar soal form/A12.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/A12-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A12-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A12-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A12-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A12-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/A12-6.png",
                            },
                        ],
                    },
                    {
                        imageUrl: "images/gambar soal form/B1.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/B1-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B1-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B1-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B1-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B1-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B1-6.png",
                            },
                        ],
                    },
                    {
                        imageUrl: "images/gambar soal form/B2.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/B2-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B2-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B2-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B2-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B2-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B2-6.png",
                            },
                        ],
                    },
                    {
                        imageUrl: "images/gambar soal form/B3.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/B3-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B3-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B3-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B3-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B3-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B3-6.png",
                            },
                        ],
                    },
                    {
                        imageUrl: "images/gambar soal form/B4.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/B4-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B4-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B4-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B4-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B4-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B4-6.png",
                            },
                        ],
                    },
                    {
                        imageUrl: "images/gambar soal form/B5.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/B5-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B5-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B5-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B5-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B5-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B5-6.png",
                            },
                        ],
                    },
                    {
                        imageUrl: "images/gambar soal form/B6.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/B6-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B6-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B6-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B6-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B6-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B6-6.png",
                            },
                        ],
                    },
                    {
                        imageUrl: "images/gambar soal form/B7.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/B7-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B7-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B7-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B7-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B7-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B7-6.png",
                            },
                        ],
                    },
                    {
                        imageUrl: "images/gambar soal form/B8.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/B8-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B8-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B8-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B8-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B8-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B8-6.png",
                            },
                        ],
                    },

                    {
                        imageUrl: "images/gambar soal form/B9.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/B9-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B9-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B9-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B9-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B9-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B9-6.png",
                            },
                        ],
                    },
                    {
                        imageUrl: "images/gambar soal form/B10.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/B10-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B10-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B10-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B10-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B10-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B10-6.png",
                            },
                        ],
                    },
                    {
                        imageUrl: "images/gambar soal form/B11.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/B11-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B11-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B11-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B11-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B11-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B11-6.png",
                            },
                        ],
                    },
                    {
                        imageUrl: "images/gambar soal form/B12.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/B12-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B12-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B12-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B12-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B12-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/B12-6.png",
                            },
                        ],
                    },
                    {
                        imageUrl: "images/gambar soal form/C1.png",
                        options: [
                            {
                                imageUrl: "/images/gambar soal form/C1-1.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/C1-2.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/C1-3.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/C1-4.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/C1-5.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/C1-6.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/C1-7.png",
                            },
                            {
                                imageUrl: "/images/gambar soal form/C1-8.png",
                            },
                        ],
                    },
                ],
            },
            answers: {
                multipleChoice: [],
                essay: [],
            },
        };
    },
    methods: {
        submitTest() {
            // Di sini Anda bisa menambahkan logika untuk mengirim data ke backend
            console.log("Data Pengguna:", this.userData);
            console.log("Jawaban Pilihan Ganda:", this.answers.multipleChoice);
            console.log("Jawaban Essay:", this.answers.essay);

            // Menampilkan pesan berhasil
            this.isSubmitted = true;
        },
        resetTest() {
            // Reset semua data
            this.isSubmitted = false;
            this.userData = {
                name: "",
                email: "",
                birthdate: "",
                gender: "",
                education: "",
                testDate: "",
            };
            this.answers = { multipleChoice: [], essay: [] };
        },
    },
};
</script>

<style scoped>
.test-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    font-family: Arial, sans-serif;
}

.test-title {
    text-align: center;
    color: #2c3e50;
    margin-bottom: 30px;
}

.form-section {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.form-section h2 {
    border-bottom: 1px solid #ddd;
    padding-bottom: 10px;
    margin-bottom: 20px;
    color: #2c3e50;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="text"],
input[type="email"],
input[type="date"],
textarea,
select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
}

select {
    height: 40px;
    background-color: white;
}

.radio-group {
    display: flex;
    gap: 20px;
}

.radio-label {
    display: flex;
    align-items: center;
    font-weight: normal;
    cursor: pointer;
}

.radio-label input {
    margin-right: 8px;
}

.question {
    margin-bottom: 25px;
}

.question-text {
    font-weight: bold;
    margin-bottom: 10px;
}

/* Gaya untuk kontainer gambar pertanyaan */
.question-image-container {
    margin: 10px 0;
    text-align: center;
}

.question-image {
    max-width: 150%;
    max-height: 400px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.option {
    margin: 8px 0;
}

.option label {
    display: flex;
    align-items: center;
    cursor: pointer;
}

.option input {
    margin-right: 8px;
}

/* Gaya untuk gambar opsi */
.option-image {
    max-width: 180px;
    max-height: 120px;
    margin-left: 10px;
    border-radius: 4px;
    border: 1px solid #ddd;
}

textarea {
    width: 100%;
    resize: vertical;
}

.form-actions {
    text-align: center;
    margin-top: 30px;
}

.submit-btn,
.reset-btn {
    background-color: #42b983;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

.submit-btn:hover,
.reset-btn:hover {
    background-color: #3aa876;
}

.success-message {
    text-align: center;
    padding: 30px;
    background-color: #f0f7f4;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.success-message h2 {
    color: #42b983;
    margin-bottom: 20px;
}

.reset-btn {
    margin-top: 20px;
}

/* Media queries untuk responsif pada layar kecil */
@media (max-width: 768px) {
    .option-image {
        max-width: 100px;
        max-height: 70px;
    }

    .question-image {
        max-height: 200px;
    }

    .radio-group {
        flex-direction: column;
        gap: 10px;
    }
}
</style>
