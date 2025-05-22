<template>
    <Head title="Psikotest" />
    <div id="app">
        <TestForm
            :is-new-session="isNewSession"
            :is-resume-session="isResumeSession"
            :user-data="user"
        />
    </div>
</template>

<script>
import TestForm from "../Components/Forms/TestForm.vue";
import { Head } from "@inertiajs/vue3";
import axios from "axios";

export default {
    name: "FormPage",
    components: {
        TestForm,
        Head,
    },
    props: {
        user: Object,
    },
    data() {
        return {
            loading: false,
            error: null,
            isNewSession: false,
            isResumeSession: false,
            userData: {
                name: "",
                email: "",
                birthdate: "",
                gender: "",
                education: "",
                testDate: new Date().toISOString().split("T")[0],
            },
        };
    },
    created() {
        // Periksa data yang ada di localStorage
        const participantData = localStorage.getItem("psychotest_participant");
        const sessionToken = localStorage.getItem("psychotest_token");
        const accessCode = localStorage.getItem("psychotest_access_code");
        const sessionData = localStorage.getItem("psychotest_session");

        if (participantData && sessionToken) {
            // Ini adalah resume session
            console.log("Resuming existing session");
            this.isResumeSession = true;

            // Tidak perlu melakukan apa-apa karena TestForm akan menangani
        } else if (accessCode && sessionData) {
            // Ini adalah sesi baru yang perlu diinisialisasi
            console.log("New session, need to start test");
            this.isNewSession = true;

            // Tidak perlu mengisi form karena kita akan menggunakan data langsung dari TestForm.vue
        } else {
            // Tidak ada data yang diperlukan, redirect ke halaman entry
            console.error("Missing required data for test");
            localStorage.setItem(
                "entry_error",
                "Data sesi tidak ditemukan. Silakan masukkan kode akses."
            );
            window.location.href = "/psychotest/entry";
        }
    },
};
</script>

<style>
#app {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    color: #2c3e50;
    margin: 20px auto;
}
/* Add this to your existing CSS */
.options-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr); /* Creates 2 columns */
    gap: 10px;
    margin-top: 10px;
}

.option {
    padding: 8px;
    border: 1px solid #eee;
    border-radius: 4px;
}

/* You might also want to adjust image sizing in this layout */
.option-image {
    max-width: 100%;
    height: auto;
    margin-top: 5px;
}

.question-image {
    max-width: 300px;
    margin: 10px 0;
}
</style>
