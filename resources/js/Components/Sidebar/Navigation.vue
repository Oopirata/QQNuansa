<script setup>
import { ref, watch } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

const page = usePage();

const isCandidatesOpen = ref(false);
const isPsychotestOpen = ref(false);

watch(
    () => page.url,
    (newUrl) => {
        if (newUrl.toLowerCase().includes("candidates")) {
            isCandidatesOpen.value = true;
        }
        if (newUrl.toLowerCase().includes("psychotest")) {
            isPsychotestOpen.value = true;
        }
    },
    { immediate: true }
);

const toggleCandidates = () => {
    isCandidatesOpen.value = !isCandidatesOpen.value;
};

const togglePsychotest = () => {
    isPsychotestOpen.value = !isPsychotestOpen.value;
};

const candidateItems = [
    { name: "New", path: "/adminNewCandidates" },
    { name: "Screened", path: "/adminScreenedCandidates" },
    { name: "Interview", path: "/adminInterviewCandidates" },
    { name: "Rejected", path: "/adminRejectedCandidates" },
];

const psychotestItems = [
    { name: "Manajemen Sesi", path: "/admin/psychotest" },
    { name: "Buat Sesi Baru", path: "/admin/psychotest/create" },
];

const isActive = (paths) => {
    return paths.includes(page.url);
};
</script>

<template>
    <nav class="flex-1">
        <ul>
            <li class="mb-4">
                <Link
                    href="/admin/dashboard"
                    class="flex items-center hover:text-gray-900"
                    :class="
                        isActive([
                            '/admin/dashboard',
                            '/admin/dashboardSchedule',
                        ])
                            ? 'text-purple-600'
                            : 'text-gray-600'
                    "
                >
                    <i class="fas fa-tachometer-alt mr-2"></i>
                    Dashboard
                </Link>
            </li>

            <li class="mb-4">
                <button
                    @click="toggleCandidates"
                    class="flex items-center w-full text-gray-600 hover:text-gray-900"
                >
                    <i class="fas fa-users mr-2"></i>
                    Candidates
                    <i
                        :class="[
                            'fas ml-auto',
                            isCandidatesOpen
                                ? 'fa-chevron-down'
                                : 'fa-chevron-right',
                        ]"
                    ></i>
                </button>
                <ul v-if="isCandidatesOpen" class="ml-6 mt-2">
                    <li
                        v-for="item in candidateItems"
                        :key="item.name"
                        class="mb-2"
                    >
                        <Link
                            :href="item.path"
                            class="hover:text-gray-900"
                            :class="
                                page.url === item.path
                                    ? 'text-purple-600'
                                    : 'text-gray-600'
                            "
                        >
                            {{ item.name }}
                        </Link>
                    </li>
                </ul>
            </li>

            <li class="mb-4">
                <Link
                    href="/adminKaryawan"
                    class="flex items-center hover:text-gray-900"
                    :class="
                        page.url === '/adminKaryawan'
                            ? 'text-purple-600'
                            : 'text-gray-600'
                    "
                >
                    <i class="fas fa-user-tie mr-2"></i>
                    Daftar Karyawan
                </Link>
            </li>

            <li class="mb-4">
                <button
                    @click="togglePsychotest"
                    class="flex items-center w-full text-gray-600 hover:text-gray-900"
                >
                    <i class="fas fa-vial mr-2"></i>
                    <!-- icon beaker/flask for psychotest -->
                    Psikotest
                    <i
                        :class="[
                            'fas ml-auto',
                            isPsychotestOpen
                                ? 'fa-chevron-down'
                                : 'fa-chevron-right',
                        ]"
                    ></i>
                </button>
                <ul v-if="isPsychotestOpen" class="ml-6 mt-2">
                    <li
                        v-for="item in psychotestItems"
                        :key="item.name"
                        class="mb-2"
                    >
                        <Link
                            :href="item.path"
                            class="hover:text-gray-900"
                            :class="
                                page.url === item.path
                                    ? 'text-purple-600'
                                    : 'text-gray-600'
                            "
                        >
                            {{ item.name }}
                        </Link>
                    </li>
                </ul>
            </li>

            <li class="mb-4">
                <Link
                    href="/adminJobList"
                    class="flex items-center hover:text-gray-900"
                    :class="
                        page.url === '/adminJobList'
                            ? 'text-purple-600'
                            : 'text-gray-600'
                    "
                >
                    <i class="fas fa-briefcase mr-2"></i>
                    Job
                </Link>
            </li>
        </ul>
    </nav>
</template>
