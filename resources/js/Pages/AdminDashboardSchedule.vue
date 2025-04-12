<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed, onMounted } from "vue";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";
import axios from "axios";

const props = defineProps({
    initialEvents: Array,
});

const months = ref([]);
const years = ref([]);
const today = ref(null);

const currentDate = ref({
    month: null,
    year: null,
});
const selectedDate = ref(null);
const showEventModal = ref(false);
const events = ref(props.initialEvents || []);
const newEvent = ref({
    title: "",
    date: "",
    start_time: "",
    end_time: "",
    description: "",
});
const isLoading = ref(true);

// Fetch date-related data from API
onMounted(async () => {
    try {
        // First fetch calendar data
        const response = await axios.get("/calendar-data");

        // Set months from API
        months.value = response.data.months;

        // Set years from API
        years.value = response.data.years;

        // Set current date from API
        currentDate.value = {
            month: response.data.currentMonth,
            year: response.data.currentYear,
        };

        // Fetch events
        try {
            const eventsResponse = await axios.get("/schedules");
            // Check the actual response structure with console.log
            console.log('Raw event data:', eventsResponse.data);
            
            // Map the events to the format expected by the frontend
            events.value = eventsResponse.data.map(event => ({
                id: event.id,
                title: event.judul,
                date: event.tanggal.split('T')[0],
                time: formatTimeRange(event.jam_mulai, event.jam_selesai),
                description: event.deskripsi,
            }));
            
            // Debug the transformed events
            console.log('Transformed events:', events.value);
        } catch (error) {
            console.error("Failed to load events:", error);
        }

        // Check for events on today's date
        if (response.data.today) {
            today.value = new Date(response.data.today);
            const todayEvents = getEventsForDate(today.value);
            if (todayEvents.length > 0) {
                selectDate({
                    day: today.value.getDate(),
                    date: today.value,
                    isCurrentMonth: true,
                    events: todayEvents,
                });
            }
        }

        isLoading.value = false;
    } catch (error) {
        console.error("Failed to load calendar data:", error);
        // Fall back to client-side defaults if API fails
        setDefaultDateValues();
        isLoading.value = false;
    }
});

// Helper function to format time range
const formatTimeRange = (start, end) => {
    // Extract just the time part (HH:MM) from datetime strings
    const startTime = start.split('T')[1]?.substring(0, 5) || start.split(' ')[1]?.substring(0, 5);
    const endTime = end.split('T')[1]?.substring(0, 5) || end.split(' ')[1]?.substring(0, 5);
    return `${startTime} - ${endTime}`;
};

// Fallback function in case API fails
const setDefaultDateValues = () => {
    const now = new Date();
    months.value = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
    ];
    years.value = Array.from(
        { length: 10 },
        (_, i) => now.getFullYear() - 5 + i
    );
    currentDate.value = {
        month: now.getMonth(),
        year: now.getFullYear(),
    };
    today.value = now;
};

const calendarDays = computed(() => {
    if (isLoading.value || currentDate.value.month === null) {
        return []; // Return empty array while loading
    }

    const year = currentDate.value.year;
    const month = currentDate.value.month;
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const days = [];

    // Previous month days
    const prevMonthDays = firstDay.getDay();
    for (let i = prevMonthDays - 1; i >= 0; i--) {
        const date = new Date(year, month, -i);
        days.push({
            day: date.getDate(),
            date: date,
            isCurrentMonth: false,
            events: getEventsForDate(date),
        });
    }

    // Current month days
    for (let i = 1; i <= lastDay.getDate(); i++) {
        const date = new Date(year, month, i);
        days.push({
            day: i,
            date: date,
            isCurrentMonth: true,
            events: getEventsForDate(date),
        });
    }

    // Next month days
    const remainingDays = 42 - days.length;
    for (let i = 1; i <= remainingDays; i++) {
        const date = new Date(year, month + 1, i);
        days.push({
            day: i,
            date: date,
            isCurrentMonth: false,
            events: getEventsForDate(date),
        });
    }

    return days;
});

const getEventsForDate = (date) => {
    if (!date) return [];
    
    console.log('Checking events for date:', date.toDateString());
    console.log('Available events:', events.value);
    
    return events.value.filter((event) => {
        const eventDate = new Date(event.date);
        console.log('Event date:', eventDate.toDateString(), 'Comparing with:', date.toDateString());
        return eventDate.toDateString() === date.toDateString();
    });
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("en-US", {
        weekday: "long",
        year: "numeric",
        month: "long",
        day: "numeric",
    });
};

const previousMonth = () => {
    if (currentDate.value.month === 0) {
        currentDate.value.month = 11;
        currentDate.value.year--;
    } else {
        currentDate.value.month--;
    }
};

const nextMonth = () => {
    if (currentDate.value.month === 11) {
        currentDate.value.month = 0;
        currentDate.value.year++;
    } else {
        currentDate.value.month++;
    }
};

const selectDate = (date) => {
    selectedDate.value = {
        ...date,
        events: getEventsForDate(date.date),
    };
};

const isSelectedDate = (date) => {
    return (
        selectedDate.value &&
        date.date.toDateString() === selectedDate.value.date.toDateString()
    );
};

const openEventModal = () => {
    if (selectedDate.value) {
        const date = selectedDate.value.date;
        // Format date to local timezone
        const localDate = new Date(
            date.getTime() - date.getTimezoneOffset() * 60000
        );
        newEvent.value.date = localDate.toISOString().split("T")[0];
    }
    showEventModal.value = true;
};

const closeEventModal = () => {
    showEventModal.value = false;
    newEvent.value = {
        title: "",
        date: "",
        start_time: "",
        end_time: "",
        description: "",
    };
};

const startTime = computed(
    () => `${newEvent.value.date} ${newEvent.value.start_time}:00`
);
const endTime = computed(
    () => `${newEvent.value.date} ${newEvent.value.end_time}:00`
);

// Update the addEvent function to match the new schema
const addEvent = async () => {
    // Check if end time is earlier than start time
    if (new Date(endTime.value) < new Date(startTime.value)) {
        alert("Jam selesai tidak boleh lebih awal dari jam mulai!");
        return; // Stop execution
    }

    // Send data to server with correct field names
    try {
        const response = await axios.post("/schedules", {
            judul: newEvent.value.title, // Now using 'judul' for the title
            tanggal: newEvent.value.date,
            jam_mulai: startTime.value,
            jam_selesai: endTime.value,
            hari: new Date(newEvent.value.date).toLocaleDateString("id-ID", {
                weekday: "long",
            }),
            deskripsi: newEvent.value.description, // Now using 'deskripsi' for the description
        });

        // Create the formatted event object (this stays the same for the UI)
        const formattedEvent = {
            id: response.data.id,
            title: newEvent.value.title,
            date: newEvent.value.date,
            time: `${newEvent.value.start_time} - ${newEvent.value.end_time}`,
            description: newEvent.value.description,
        };

        // Add to global events array
        events.value.push(formattedEvent);

        // If the event is for the selected date, update the selectedDate.events array
        if (selectedDate.value && 
            new Date(newEvent.value.date).toDateString() === selectedDate.value.date.toDateString()) {
            // Create a new array with the existing events plus the new one
            selectedDate.value.events = [...selectedDate.value.events, formattedEvent];
        }

        closeEventModal();
    } catch (error) {
        console.error("Failed to add event:", error);
        // Handle error (show message to user, etc.)
    }
};

const deleteEvent = async (eventId) => {
    try {
        // Send delete request to the server
        await axios.delete(`/schedules/${eventId}`);
        
        // Find the event to be deleted
        const eventToDelete = events.value.find(event => event.id === eventId);
        
        // Remove from global events array
        events.value = events.value.filter((event) => event.id !== eventId);
        
        // If there's a selected date and the event belongs to that date, remove it from selectedDate.events
        if (selectedDate.value && eventToDelete) {
            const eventDate = new Date(eventToDelete.date);
            const selectedDateObj = new Date(selectedDate.value.date);
            
            if (eventDate.toDateString() === selectedDateObj.toDateString()) {
                // Create a new array excluding the deleted event
                selectedDate.value.events = selectedDate.value.events.filter(event => event.id !== eventId);
            }
        }
        
        // Optional: Add a success notification or toast
        // For example: toast.success('Event deleted successfully');
    } catch (error) {
        console.error("Failed to delete event:", error);
        
        // Optional: Show error message to user
        // For example: toast.error('Failed to delete event');
    }
};
</script>

<template>
    <Head title="Dashboard" />
    <div class="flex min-h-screen ml-64">
        <!-- Fixed Sidebar -->
        <Sidebar :user="$page.props.auth.user" />

        <!-- Main Content -->
        <main class="flex-1">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold">Dashboard</h1>
                    <div class="flex space-x-4">
                        <a href="/companyprofile">
                            <button
                                class="bg-gray-700 text-white px-4 py-2 rounded"
                            >
                                Visit Website
                            </button>
                        </a>
                        <a href="/adminNewJob">
                            <button
                                class="bg-purple-600 text-white px-4 py-2 rounded"
                            >
                                + New job
                            </button>
                        </a>
                    </div>
                </div>
                <div>
                    <div class="flex border-b mb-4">
                        <Link
                            href="/admin/dashboard"
                            class="text-gray-700 mr-4 pb-2 border-b-2 border-transparent hover:border-purple-500"
                            >Overview</Link
                        >
                        <Link
                            href="/adminDashboardSchedule"
                            class="text-purple-500 pb-2 border-b-2 border-purple-500"
                            >Schedule</Link
                        >
                    </div>
                </div>
                <main class="flex-1">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h1 class="text-3xl font-bold">Calendar</h1>
                            <button
                                @click="openEventModal"
                                class="bg-purple-600 text-white px-4 py-2 rounded"
                            >
                                + Add Event
                            </button>
                        </div>

                        <div class="grid grid-cols-4 gap-6">
                            <!-- Calendar Grid -->
                            <div
                                class="col-span-3 bg-white p-6 rounded-lg shadow max-w-4xl"
                            >
                                <div
                                    class="flex justify-between items-center mb-6"
                                >
                                    <div class="flex items-center gap-4">
                                        <button
                                            @click="previousMonth"
                                            class="p-2 hover:bg-gray-100 rounded"
                                        >
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                        <select
                                            v-model="currentDate.month"
                                            class="border rounded p-1 cursor-pointer"
                                        >
                                            <option
                                                v-for="(month, index) in months"
                                                :value="index"
                                            >
                                                {{ month }}
                                            </option>
                                        </select>
                                        <select
                                            v-model="currentDate.year"
                                            class="border rounded p-1 cursor-pointer"
                                        >
                                            <option
                                                v-for="year in years"
                                                :value="year"
                                            >
                                                {{ year }}
                                            </option>
                                        </select>
                                        <button
                                            @click="nextMonth"
                                            class="p-2 hover:bg-gray-100 rounded"
                                        >
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-7 gap-2 mb-2">
                                    <template
                                        v-for="day in [
                                            'Sun',
                                            'Mon',
                                            'Tue',
                                            'Wed',
                                            'Thu',
                                            'Fri',
                                            'Sat',
                                        ]"
                                    >
                                        <div
                                            class="text-center text-gray-600 font-semibold py-2"
                                        >
                                            {{ day }}
                                        </div>
                                    </template>
                                </div>

                                <div class="grid grid-cols-7 gap-2">
                                    <template v-for="date in calendarDays">
                                        <div
                                            @click="selectDate(date)"
                                            class="min-h-20 border rounded-lg p-2 cursor-pointer hover:bg-gray-50"
                                            :class="{
                                                'bg-gray-100':
                                                    !date.isCurrentMonth,
                                                'border-purple-500':
                                                    isSelectedDate(date),
                                            }"
                                        >
                                            <div class="flex justify-between">
                                                <span
                                                    :class="{
                                                        'text-gray-400':
                                                            !date.isCurrentMonth,
                                                    }"
                                                >
                                                    {{ date.day }}
                                                </span>
                                                <span
                                                    v-if="date.events?.length"
                                                    class="text-xs bg-purple-100 text-purple-600 px-2 rounded-full"
                                                >
                                                    {{ date.events.length }}
                                                </span>
                                            </div>
                                            <div class="mt-1">
                                                <div
                                                    v-for="event in date.events?.slice(
                                                        0,
                                                        2
                                                    )"
                                                    class="text-xs mb-1 p-1 rounded bg-purple-100 text-purple-600 truncate"
                                                >
                                                    {{ event.title }}
                                                </div>
                                                <div
                                                    v-if="
                                                        date.events?.length > 2
                                                    "
                                                    class="text-xs text-gray-500"
                                                >
                                                    +{{
                                                        date.events.length - 2
                                                    }}
                                                    more
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Selected Date Events Panel -->
                            <div
                                v-if="selectedDate"
                                class="bg-white p-6 rounded-lg shadow h-fit"
                            >
                                <h2 class="text-xl font-semibold mb-4">
                                    Events for
                                    {{ formatDate(selectedDate.date) }}
                                </h2>
                                <div
                                    v-if="selectedDate.events?.length"
                                    class="space-y-4"
                                >
                                    <div
                                        v-for="event in selectedDate.events"
                                        class="p-4 border rounded-lg hover:shadow-md transition-shadow"
                                    >
                                        <div
                                            class="flex justify-between items-start"
                                        >
                                            <h3 class="font-semibold text-lg">
                                                {{ event.title }}
                                            </h3>
                                            <button
                                                @click="deleteEvent(event.id)"
                                                class="text-red-500 hover:text-red-700"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <p class="text-gray-600">
                                            {{ event.time }}
                                        </p>
                                        <p class="text-gray-700 mt-2">
                                            {{ event.description }}
                                        </p>
                                    </div>
                                </div>
                                <div
                                    v-else
                                    class="text-gray-500 text-center py-4"
                                >
                                    No events scheduled for this date
                                </div>
                                <button
                                    @click="openEventModal"
                                    class="mt-4 w-full bg-purple-100 text-purple-600 px-4 py-2 rounded hover:bg-purple-200"
                                >
                                    + Add Event
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Event Modal -->
                    <div
                        v-if="showEventModal"
                        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center"
                    >
                        <div class="bg-white p-6 rounded-lg w-96">
                            <h3 class="text-xl font-semibold mb-4">
                                Add New Event
                            </h3>
                            <form @submit.prevent="addEvent">
                                <div class="mb-4">
                                    <label
                                        class="block text-sm font-medium mb-1"
                                        >Title</label
                                    >
                                    <input
                                        v-model="newEvent.title"
                                        type="text"
                                        class="w-full border rounded p-2"
                                        required
                                    />
                                </div>
                                <div class="mb-4">
                                    <label
                                        class="block text-sm font-medium mb-1"
                                        >Date</label
                                    >
                                    <input
                                        v-model="newEvent.date"
                                        type="date"
                                        class="w-full border rounded p-2"
                                        required
                                    />
                                </div>
                                <div class="mb-4">
                                    <label
                                        class="block text-sm font-medium mb-1"
                                        >Start Time</label
                                    >
                                    <input
                                        v-model="newEvent.start_time"
                                        type="time"
                                        class="w-full border rounded p-2"
                                        required
                                    />
                                </div>
                                <div class="mb-4">
                                    <label
                                        class="block text-sm font-medium mb-1"
                                        >End Time</label
                                    >
                                    <input
                                        v-model="newEvent.end_time"
                                        type="time"
                                        class="w-full border rounded p-2"
                                        required
                                    />
                                </div>
                                <div class="mb-4">
                                    <label
                                        class="block text-sm font-medium mb-1"
                                        >Description</label
                                    >
                                    <textarea
                                        v-model="newEvent.description"
                                        class="w-full border rounded p-2"
                                        rows="3"
                                    ></textarea>
                                </div>
                                <div class="flex justify-end gap-2">
                                    <button
                                        @click="closeEventModal"
                                        type="button"
                                        class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        type="submit"
                                        class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700"
                                    >
                                        Save Event
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </main>
            </div>
        </main>
    </div>
</template>

<style scoped>
:deep(*) {
    font-family: "Kaisei Opti", sans-serif;
}

select {
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
    background-image: none !important;
    cursor: pointer;
    padding: 0 8px;
    font-weight: 500;
}
</style>
