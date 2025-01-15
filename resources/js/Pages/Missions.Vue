<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { ref } from "vue";

const showingNavigationDropdown = ref(false);

const logout = () => {
    router.post(route("logout"));
};

// Jika kita perlu menggunakan asset dari Laravel, kita bisa membuat computed property
const asset = (path) => `/assets/${path}`;
</script>

<template>
    <Head title="Company Profile" />

    <div class="bg-white">
        <!-- Header Section -->
        <header class="bg-blue-200 p-4 flex justify-between items-center">
            <div class="flex items-center -mr-16">
                <img
                    alt="Company Logo"
                    class="h-14 w-14"
                    src="/images/QQ.png"
                />
            </div>
            <nav class="space-x-10 -ml-60 mr-80">
                <Link
                    class="text-blue-900 transition-colors duration-200 hover:text-[#4D62D7]"
                    href="/"
                >
                    Tentang Kami
                </Link>
                <Link
                    class="text-blue-900 transition-colors duration-200 hover:text-[#4D62D7]"
                    href="#"
                >
                    Psiko Test
                </Link>
                <Link
                    class="text-blue-900 transition-colors duration-200 hover:text-[#4D62D7]"
                    href="#"
                >
                    Konsultasi
                </Link>
                <Link
                    class="text-blue-900 transition-colors duration-200 hover:text-[#4D62D7]"
                    href="#"
                >
                    Pelatihan
                </Link>
            </nav>
            <div class="-mr-72">
                <Link
                    class="text-[#5932EA] font-bold transition-colors duration-200 hover:text-[#4D62D7]"
                    href="/hiring"
                >
                    We are hiring!!
                </Link>
            </div>
            <div class="flex items-center space-x-2">
                <template v-if="$page.props.auth.user">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <span class="inline-flex rounded-md">
                                <button
                                    type="button"
                                    class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                >
                                    {{ $page.props.auth.user.name }}
                                    <i
                                        class="fas fa-user-circle text-blue-900 text-3xl ml-2"
                                    ></i>
                                </button>
                            </span>
                        </template>

                        <template #content>
                            <DropdownLink :href="route('profile.edit')">
                                Profile
                            </DropdownLink>
                            <DropdownLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Log Out
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </template>
                <template v-else>
                    <Link href="/login">
                        <button
                            class="bg-green-200 text-green-800 px-4 py-2 rounded mr-3"
                        >
                            Log In
                        </button>
                    </Link>
                    <i class="fas fa-user-circle text-blue-900 text-3xl"></i>
                </template>
            </div>
        </header>

        <!-- Navigation Menu -->
        <main class="p-8 -mt-8">
            <div
                class="bg-yellow-100 p-4 flex justify-center space-x-16 w-2/5 mx-auto"
            >
                <Link
                    class="text-gray-700 text-center transition-colors duration-200 hover:text-[#CDC052]"
                    href="/"
                >
                    Company Profile
                </Link>
                <Link
                    class="text-gray-700 text-center transition-colors duration-200 hover:text-[#CDC052]"
                    href="/legalitas"
                >
                    Legalitas
                </Link>
                <Link
                    class="text-gray-700 text-center transition-colors duration-200 hover:text-[#CDC052]"
                    href="/missions"
                >
                    Missions
                </Link>
                <Link
                    class="text-gray-700 text-center transition-colors duration-200 hover:text-[#CDC052]"
                    href="/services"
                >
                    Services
                </Link>
            </div>
        </main>

        <!-- Content Section -->
        <section class="mt-8 ml-40 mr-40">
            <div
                style="
                    display: flex;
                    justify-content: center;
                    align-items: center;
                "
            >
                <img
                    alt="Missions Icon"
                    class="h-36 w-36 -mt-10 mb-5 -ml-10"
                    src="/images/missions.jpeg"
                />
            </div>

            <h1 class="text-center text-2xl font-bold -ml-4">OUR MISSION</h1>
            <div
                style="
                    display: flex;
                    justify-content: center;
                    align-items: center;
                "
            ></div>
            <p class="text-2xl font-bold ml-5 mt-5">
                1. Meningkatkan Potensi Individu Secara Optimal & Positif
            </p>
            <div style="padding: 20px; text-align: left; font-size: 1rem">
                <p class="-mt-4">
                    Layanan ini bertujuan untuk mengembangkan kemampuan individu
                    dalam berbagai aspek kehidupan. Penjelasannya meliputi:
                </p>
                <ul>
                    <li class="mb-4 mt-2">
                        <strong>Kognitif:</strong> Memberikan pelatihan atau
                        program yang meningkatkan kemampuan berpikir kritis,
                        pemecahan masalah, daya ingat, dan kemampuan belajar
                        individu. <br />
                        <em>Contoh:</em> Tes kecerdasan, pelatihan logika, dan
                        seminar manajemen waktu.
                    </li>
                    <li class="mb-4">
                        <strong>Emosi:</strong> Meningkatkan keterampilan
                        pengelolaan emosi seperti pengendalian stres, manajemen
                        konflik, dan peningkatan ketahanan emosional. <br />
                        <em>Contoh:</em> Konseling psikologi, terapi stres, atau
                        pelatihan mindfulness.
                    </li>
                    <li class="mb-4">
                        <strong>Sosial:</strong> Mengembangkan keterampilan
                        sosial individu agar lebih percaya diri dalam
                        berinteraksi dengan lingkungan sosial. <br />
                        <em>Contoh:</em> Pelatihan komunikasi, kerjasama tim,
                        dan peningkatan empati sosial.
                    </li>
                    <li class="mb-4">
                        <strong>Komunikasi:</strong> Membantu individu
                        meningkatkan keterampilan verbal dan non-verbal agar
                        dapat menyampaikan ide secara efektif. <br />
                        <em>Contoh:</em> Public speaking training, pelatihan
                        negosiasi, dan penanganan konflik interpersonal.
                    </li>
                    <li class="mb-4">
                        <strong>Perilaku:</strong> Mengarahkan individu untuk
                        membentuk kebiasaan yang mendukung pengembangan diri,
                        seperti disiplin, tanggung jawab, dan motivasi diri.
                        <br />
                        <em>Contoh:</em> Coaching perilaku produktif dan program
                        modifikasi kebiasaan.
                    </li>
                    <li class="mb-4">
                        <strong>Spiritual:</strong> Mendukung individu dalam
                        menemukan makna hidup dan membangun hubungan dengan
                        aspek spiritual, yang dapat membantu meningkatkan
                        kesehatan mental. <br />
                        <em>Contoh:</em> Workshop pengembangan spiritualitas dan
                        pelatihan meditasi.
                    </li>
                    <li class="mb-4">
                        <strong>Visual Motorik:</strong> Melatih koordinasi
                        antara penglihatan dan gerakan fisik untuk mendukung
                        keterampilan praktis. <br />
                        <em>Contoh:</em> Terapi motorik halus untuk anak-anak
                        atau pelatihan koordinasi fisik untuk pekerjaan
                        tertentu.
                    </li>
                </ul>
            </div>
            <div
                style="
                    display: flex;
                    justify-content: center;
                    align-items: center;
                "
            ></div>
            <p class="text-2xl font-bold ml-5 mt-5 -mb-4">
                2. Menyediakan Jasa Aplikasi Psikologi dan Konsultan SDM
            </p>
            <div style="padding: 20px; text-align: left; font-size: 1rem">
                <p class="mb-4">
                    <strong>Aplikasi Psikologi:</strong> Layanan yang
                    memanfaatkan teknologi untuk memberikan solusi berbasis
                    psikologi. <br />
                    <em>Contoh:</em> Pengembangan aplikasi tes psikologi online,
                    alat asesmen kepribadian, atau platform e-learning berbasis
                    psikologi.
                </p>
                <p>
                    <strong>Konsultan SDM:</strong> Membantu organisasi dan
                    perusahaan dalam mengembangkan sumber daya manusia secara
                    efektif.
                </p>
                <ul>
                    <li>
                        Menyusun program pelatihan karyawan untuk meningkatkan
                        keterampilan tertentu.
                    </li>
                    <li>
                        Memberikan layanan asesmen karyawan untuk promosi atau
                        rekrutmen.
                    </li>
                    <li>
                        Konsultasi terkait pengembangan budaya kerja yang
                        mendukung produktivitas dan kesejahteraan karyawan.
                    </li>
                </ul>
            </div>
        </section>
    </div>
</template>

<style scoped>
/* Menerapkan font ke seluruh komponen */
:deep(*) {
    font-family: "Kaisei Opti", sans-serif;
}
</style>
