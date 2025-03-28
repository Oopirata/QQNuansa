<script setup>
import { ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    candidates: {
        type: Object,
        required: true,
    }
});

const emit = defineEmits(['emailSent']);

const emailForm = ref({
    applicant_id: props.candidates.id,
    subject: "",
    messages: "",
    status: String(props.candidates.status),
    interviewSchedule: null,
    email: props.candidates.email,
});

const showPreview = ref(false);
const previewContent = ref("");

const previewEmail = async () => {
    try {
        const response = await axios.post(
            route("preview.email"),
            emailForm.value
        );
        previewContent.value = response.data;
        showPreview.value = true;
    } catch (error) {
        console.log("Error detail:", error.response?.data);
        alert("Failed to generate preview: " + error.message);
    }
};

const sendEmail = async () => {
    try {
        const response = await axios.post(route("send.email"), emailForm.value);
        if (response.data.success) {
            alert("Email sent successfully!");
            emit('emailSent');
            emailForm.value = {
                applicant_id: props.candidates.id,
                subject: "",
                messages: "",
                status: props.candidates.status,
                interviewSchedule: null,
                email: props.candidates.email,
            };
        }
    } catch (error) {
        alert("Failed to send email: " + error.message);
    }
};
</script>

<template>
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Send Email</h3>
        <div class="space-y-4">
            <div>
                <label for="subject" class="block text-gray-600">Subject</label>
                <input
                    v-model="emailForm.subject"
                    id="subject"
                    type="text"
                    placeholder="Email Subject"
                    class="w-full border rounded p-2"
                />
            </div>
            <div>
                <label for="messages" class="block text-gray-600">Message</label>
                <textarea
                    v-model="emailForm.messages"
                    id="messages"
                    placeholder="Type your message..."
                    rows="4"
                    class="w-full border rounded p-2"
                ></textarea>
            </div>
            <div>
                <label for="interviewSchedule" class="block text-gray-600">
                    Interview Schedule (optional)
                </label>
                <input
                    v-model="emailForm.interviewSchedule"
                    id="interviewSchedule"
                    type="datetime-local"
                    class="w-full border rounded p-2"
                />
            </div>
            <div class="flex space-x-4">
                <button
                    @click="previewEmail"
                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600"
                >
                    Preview
                </button>
                <button
                    @click="sendEmail"
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                >
                    Send Email
                </button>
            </div>
        </div>

        <!-- Preview Modal -->
        <div
            v-if="showPreview"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg w-3/4 max-h-[80vh] shadow-xl overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <div class="bg-blue-100 rounded-lg p-2">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 text-blue-500"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">
                                    Email Preview
                                </h3>
                                <p class="text-sm text-gray-500">
                                    Preview your email before sending
                                </p>
                            </div>
                        </div>
                        <button
                            @click="showPreview = false"
                            class="p-2 hover:bg-gray-100 rounded-full transition-colors duration-200"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 text-gray-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="p-6 space-y-4">
                    <div class="bg-white rounded-lg border border-gray-200">
                        <div class="p-4 border-b border-gray-200">
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-500 w-20">To:</span>
                                    <span class="text-sm text-gray-800">{{ emailForm.email }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-500 w-20">Subject:</span>
                                    <span class="text-sm text-gray-800">{{ emailForm.subject }}</span>
                                </div>
                                <div v-if="emailForm.interviewSchedule" class="flex items-center">
                                    <span class="text-sm font-medium text-gray-500 w-20">Schedule:</span>
                                    <span class="text-sm text-gray-800">
                                        {{ new Date(emailForm.interviewSchedule).toLocaleString() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Email Content -->
                        <div class="p-4">
                            <div class="prose max-w-none" v-html="previewContent"></div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button
                        @click="showPreview = false"
                        class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                    >
                        Close Preview
                    </button>
                    <button
                        @click="sendEmail"
                        class="px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                    >
                        Send Email
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>