<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import Sidebar from "@/Components/Sidebar/Sidebar.vue";

const months = [
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
const years = Array.from(
    { length: 10 },
    (_, i) => new Date().getFullYear() - 5 + i
);

const currentDate = ref({
    month: new Date().getMonth(),
    year: new Date().getFullYear(),
});
const selectedDate = ref(null);
const showEventModal = ref(false);
const events = ref([]);
const newEvent = ref({
    title: "",
    date: "",
    time: "",
    description: "",
});

const calendarDays = computed(() => {
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
    return events.value.filter((event) => {
        const eventDate = new Date(event.date);
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
        time: "",
        description: "",
    };
};

const addEvent = () => {
    events.value.push({
        ...newEvent.value,
        id: Date.now(),
    });
    closeEventModal();
};

const deleteEvent = (eventId) => {
    events.value = events.value.filter((event) => event.id !== eventId);
};
</script>

<template>
    <Head title="Dashboard" />
    <div class="flex min-h-screen">
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
                            href="/adminDashboard"
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
                                        >Time</label
                                    >
                                    <input
                                        v-model="newEvent.time"
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
