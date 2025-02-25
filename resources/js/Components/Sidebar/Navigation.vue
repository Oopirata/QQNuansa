<script setup>
import { ref, watch } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

const page = usePage();
const isSubMenuOpen = ref(false);

watch(
    () => page.url,
    (newUrl) => {
        if (
            newUrl.includes("admin") &&
            (newUrl.includes("Candidates") || newUrl.includes("candidates"))
        ) {
            isSubMenuOpen.value = true;
        }
    },
    { immediate: true }
);

const toggleSubMenu = () => {
    isSubMenuOpen.value = !isSubMenuOpen.value;
};

const candidateItems = [
    { name: "New", path: "/adminNewCandidates" },
    { name: "Screened", path: "/adminScreenedCandidates" },
    { name: "Interview", path: "/adminInterviewCandidates" },
    { name: "Rejected", path: "/adminRejectedCandidates" },
];
</script>

<template>
    <nav class="flex-1">
        <ul>
            <li class="mb-4">
                <Link
                    href="/adminDashboard"
                    class="flex items-center hover:text-gray-900"
                    :class="[
                        $page.url === '/adminDashboard' ||
                        $page.url === '/adminDashboardSchedule'
                            ? 'text-purple-600'
                            : 'text-gray-600',
                    ]"
                >
                    <i class="fas fa-tachometer-alt mr-2"></i>
                    Dashboard
                </Link>
            </li>
            <li class="mb-4">
                <button
                    @click="toggleSubMenu"
                    class="flex items-center w-full text-gray-600 hover:text-gray-900"
                >
                    <i class="fas fa-users mr-2"></i>
                    Candidates
                    <i
                        :class="[
                            'fas ml-auto',
                            isSubMenuOpen
                                ? 'fa-chevron-down'
                                : 'fa-chevron-right',
                        ]"
                    ></i>
                </button>
                <ul v-if="isSubMenuOpen" class="ml-6 mt-2">
                    <li
                        v-for="item in candidateItems"
                        :key="item.name"
                        class="mb-2"
                    >
                        <Link
                            :href="item.path"
                            class="hover:text-gray-900"
                            :class="[
                                $page.url === item.path
                                    ? 'text-purple-600'
                                    : 'text-gray-600',
                            ]"
                        >
                            {{ item.name }}
                        </Link>
                    </li>
                </ul>
            </li>
            <li class="mb-4">
                <Link
                    href="/adminEmail"
                    class="flex items-center hover:text-gray-900"
                    :class="[
                        $page.url === '/adminEmail'
                            ? 'text-purple-600'
                            : 'text-gray-600',
                    ]"
                >
                    <i class="fas fa-envelope mr-2"></i>
                    E-mail
                </Link>
            </li>
            <li class="mb-4">
                <Link
                    href="/adminJobList"
                    class="flex items-center hover:text-gray-900"
                    :class="[
                        $page.url === '/adminJobList'
                            ? 'text-purple-600'
                            : 'text-gray-600',
                    ]"
                >
                    <i class="fas fa-envelope mr-2"></i>
                    Job
                </Link>
            </li>
        </ul>
    </nav>
</template>
