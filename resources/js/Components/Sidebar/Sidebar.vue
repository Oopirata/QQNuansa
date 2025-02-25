<script setup>
import LogoSection from "./LogoSection.vue";
import Navigation from "./Navigation.vue";
import ProfileDropup from "../ProfileDropup.vue";

defineProps({
    user: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <aside class="sidebar">
        <div class="p-4 flex flex-col h-full">
            <LogoSection />
            <Navigation class="my-4" />
            <div class="flex-grow"></div>
            <ProfileDropup :user="user" />
        </div>
    </aside>
</template>

<style scoped>
.sidebar {
    background-color: #dbeafe; /* bg-blue-100 equivalent */
    width: 16rem;
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    height: 100%;
    z-index: 10;
    overflow-y: auto;
}
</style>
