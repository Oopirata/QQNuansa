<script setup>
import LogoSection from './LogoSection.vue'
import Navigation from './Navigation.vue'
import ProfileDropup from '../ProfileDropup.vue'

defineProps({
    user: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <div class="bg-blue-100 w-64 p-4 flex flex-col">
        <LogoSection />
        <Navigation />
        <ProfileDropup :user="user" />
    </div>
</template>