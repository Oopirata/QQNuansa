<script setup>
import { Head } from "@inertiajs/vue3";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";

const props = defineProps({
    employees: {
        type: Array,
        required: true,
    },
});

const formatDate = (dateStr) => {
    if (!dateStr) return "-";
    return new Date(dateStr).toLocaleDateString("id-ID");
};
</script>

<template>
    <Head title="Daftar Karyawan" />

    <div class="flex h-screen bg-white">
        <Sidebar :user="$page.props.auth.user" />

        <main class="flex-1 overflow-hidden ml-64">
            <div class="p-6">
                <h1 class="text-3xl font-bold mb-6">Daftar Karyawan</h1>

                <div class="bg-white rounded-lg shadow overflow-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                >
                                    Nama
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                >
                                    Email
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                >
                                    Tanggal Masuk
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="karyawan in employees"
                                :key="karyawan.id"
                            >
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                >
                                    {{ karyawan.nama }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    {{ karyawan.email }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    {{ formatDate(karyawan.tanggal_masuk) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</template>

<style scoped>
:deep(*) {
    font-family: "Kaisei Opti", sans-serif;
}
</style>
