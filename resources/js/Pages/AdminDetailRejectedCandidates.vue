<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from "vue";
import { Head, usePage, router } from "@inertiajs/vue3";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";
import DetailHeader from "@/Components/Candidates/DetailHeader.vue";

const dropdownOpen = ref(false);
const dropdownRef = ref(null);

const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
};

// Menutup dropdown ketika klik di luar
const closeDropdown = (e) => {
    if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
        dropdownOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener("click", closeDropdown);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", closeDropdown);
});

const props = defineProps({
    candidates: {
        type: Object,
        required: true,
    },
    screenings: {
        type: Array,
        required: true,
    },
});

const showPreview = ref(false);
const previewContent = ref("");

const isSubMenuOpen = ref(true);

const candidateItems = [
    { name: "New", path: "/adminNewCandidates" },
    { name: "Screened", path: "/adminScreenedCandidates" },
    { name: "Interview", path: "/adminInterviewCandidates" },
    { name: "Rejected", path: "/adminRejectedCandidates" },
];
const toggleSubMenu = () => {
    isSubMenuOpen.value = !isSubMenuOpen.value;
};

const currentPage = ref(1);
const totalPages = ref(6);

// Action methods
const disqualifyCandidate = (user_id) => {
    // Implementation for disqualifying candidate
    router.post(route("disqualify", props.candidates.user_id));
};

const moveToScreened = (user_id) => {
    router.visit(route("adminDetailNewCandidates", id));
};

const normalizeCityName = (rawName) => {
    if (!rawName) return "";
    return rawName
        .toLowerCase()
        .replace(/^kota\s+/i, "")
        .replace(/^kabupaten\s+/i, "")
        .split(" ")
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
        .join(" ");
};

// Fix for CV preview URL
const cvUrl = computed(() => {
    if (!props.candidates.cv_path) return null;

    // Extract just the filename from the path
    const pathParts = props.candidates.cv_path.split("/");
    const filename = pathParts[pathParts.length - 1];

    // Try different path formats
    return `/storage/cv_files/${filename}`;
});

// Fallback viewer using Google Docs Viewer as an alternative
const googleDocsViewerUrl = computed(() => {
    if (!cvUrl.value) return null;

    const baseUrl = window.location.origin;
    const fullPdfUrl = `${baseUrl}${cvUrl.value}`;

    return `https://docs.google.com/viewer?url=${encodeURIComponent(
        fullPdfUrl
    )}&embedded=true`;
});

// Track PDF loading state
const isPdfLoading = ref(true);
const hasPdfError = ref(false);

const handlePdfLoad = () => {
    isPdfLoading.value = false;
    hasPdfError.value = false;
};

const handlePdfError = () => {
    isPdfLoading.value = false;
    hasPdfError.value = true;
};
</script>

<template>
    <Head title="Candidate Detail" />
    <div class="flex">
        <Sidebar :user="$page.props.auth.user" />

        <!-- Main Content -->
        <div class="w-full p-6 ml-64">
            <DetailHeader
                :jobVacancy="candidates.job_vacancy"
                status="Rejected"
                :currentPage="currentPage"
                :totalPages="totalPages"
                :showDisqualify="false"
                :showMoveToScreened="false"
                :showMoveToInterview="false"
                :showMoveToHired="false"
                @disqualify="disqualifyCandidate"
                @moveToScreened="moveToScreened"
                @prevPage="handlePrevPage"
                @nextPage="handleNextPage"
            />

            <!-- Candidate Profile -->
            <div class="flex mb-6">
                <div class="w-1/2 pr-4">
                    <!-- Profile Info -->
                    <div class="bg-white rounded-lg shadow p-6 mb-6">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-16 h-16 bg-blue-200 rounded-full mr-4"
                            ></div>
                            <div>
                                <h2 class="text-xl font-semibold">
                                    {{
                                        candidates.user ||
                                        candidates.name ||
                                        "Unknown Candidate"
                                    }}
                                </h2>
                                <p class="text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    {{
                                        normalizeCityName(
                                            candidates.city ||
                                                "Unknown Location"
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <i
                                    class="fas fa-envelope mr-2 text-gray-400"
                                ></i>
                                <span>{{ candidates.email }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone mr-2 text-gray-400"></i>
                                <span>{{
                                    candidates.phone || "no phone number"
                                }}</span>
                            </div>
                            <div
                                class="flex items-center"
                                v-if="candidates.linkedin"
                            >
                                <i class="fas fa-link mr-2 text-gray-400"></i>
                                <a
                                    :href="candidates.linkedin"
                                    class="text-blue-500"
                                    target="_blank"
                                    rel="noopener"
                                >
                                    {{ candidates.linkedin }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Screening Questions -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">
                            Screening questions
                        </h3>
                        <div v-if="screenings.length > 0" class="space-y-4">
                            <div v-for="(qa, index) in screenings" :key="index">
                                <p class="text-gray-600 mb-1">
                                    {{ qa.question }}
                                </p>
                                <p>{{ qa.answer }}</p>
                            </div>
                        </div>
                        <div v-else class="text-gray-500 italic">
                            No screening answers provided.
                        </div>
                    </div>
                </div>
                <!-- CV Preview Section -->
                <div class="w-1/2 pl-4">
                    <div class="bg-white rounded-lg shadow p-6">
                        <!-- Primary PDF Viewer with fallback options -->
                        <div v-if="cvUrl" class="w-full">
                            <!-- Loading indicator -->
                            <div
                                v-if="isPdfLoading"
                                class="w-full h-16 flex items-center justify-center"
                            >
                                <span class="text-gray-500"
                                    >Loading PDF...</span
                                >
                            </div>

                            <!-- First attempt: Direct iframe -->
                            <iframe
                                v-show="!hasPdfError"
                                :src="cvUrl"
                                class="w-full"
                                style="height: 650px"
                                frameborder="0"
                                @load="handlePdfLoad"
                                @error="handlePdfError"
                            ></iframe>

                            <!-- Fallback: Google Docs Viewer -->
                            <iframe
                                v-if="hasPdfError && googleDocsViewerUrl"
                                :src="googleDocsViewerUrl"
                                class="w-full"
                                style="height: 650px"
                                frameborder="0"
                            ></iframe>
                        </div>

                        <!-- No PDF available message -->
                        <div
                            v-else
                            class="w-full h-96 flex items-center justify-center bg-gray-100 text-gray-500"
                        >
                            CV file not available or access restricted
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
:deep(*) {
    font-family: "Kaisei Opti", sans-serif;
}
</style>
