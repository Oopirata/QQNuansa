<!-- File: components/ProfileDropdown.vue -->
<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
})

const dropdownOpen = ref(false)
const dropdownRef = ref(null)

const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value
}

const closeDropdown = (e) => {
    if (dropdownRef.value && !dropdownRef.value.contains(e.target)) {
        dropdownOpen.value = false
    }
}

onMounted(() => {
    document.addEventListener('click', closeDropdown)
})

onBeforeUnmount(() => {
    document.removeEventListener('click', closeDropdown)
})
</script>

<template>
    <div class="mt-auto relative" ref="dropdownRef">
        <button
            @click="toggleDropdown"
            type="button"
            class="flex items-center w-full p-2 hover:bg-gray-100 rounded-md">
                <img
                    src="/images/profile.png"
                    alt="User Avatar"
                    class="w-10 h-10 rounded-full mr-2"
                />
                <div>
                    <p class="text-gray-600">
                        {{ user.name }}
                    </p>
                </div>
        </button>
        
        <Transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 translate-y-2"
            enter-to-class="transform opacity-100 translate-y-0"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 translate-y-0"
            leave-to-class="transform opacity-0 translate-y-2"
        >
            <div
                v-show="dropdownOpen"
                class="absolute left-0 bottom-full mb-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-1 z-50"
            >
                <Link
                    :href="route('profile.edit')"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                    Profile
                </Link>
                <Link
                    v-if="user.roles.some((role) => role.role_name === 'admin')"
                    :href="route('landing.page')"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                    Website
                </Link>
                <Link
                    :href="route('logout')"
                    method="post"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                    Log Out
                </Link>
            </div>
        </Transition>
    </div>
</template>