<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false); // Untuk mengontrol visibilitas password

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Sign-in Page" />
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="flex w-full max-w-4xl bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Left side with logo and heading -->
            <div class="w-1/3 bg-blue-200 p-8 flex flex-col justify-center items-center">
                <h2 class="text-4xl font-bold text-white mb-4 text-left">
                    Developing Your <br />
                    <span class="text-orange-500">People</span>
                </h2>
                <div class="mt-8">
                    <img src="/images/QQ.png" alt="logo" class="mb-4" width="200" height="150" />
                </div>
            </div>

            <!-- Right side with login form -->
            <div class="w-2/3 p-16">
                <h2 class="text-2xl font-bold mb-6 text-center">Sign-in</h2>

                <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
                    {{ status }}
                </div>

                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <TextInput
                            id="email"
                            type="email"
                            v-model="form.email"
                            class="shadow appearance-none border-b w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            placeholder="Email"
                            required
                            autofocus
                            autocomplete="username"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="mb-6 relative">
                        <!-- Input password dengan tipe dinamis -->
                        <TextInput
                            id="password"
                            :type="showPassword ? 'text' : 'password'"
                            v-model="form.password"
                            class="shadow appearance-none border-b w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                            placeholder="Password"
                            required
                            autocomplete="current-password"
                        />
                        <!-- Tombol untuk toggle password visibility -->
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute top-0 right-0 mt-2 mr-3 text-gray-500 hover:text-gray-700 focus:outline-none"
                        >
                            <i v-if="showPassword" class="fas fa-eye-slash"></i>
                            <i v-else class="fas fa-eye"></i>
                        </button>
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="mb-4 block">
                        <label class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" />
                            <span class="ms-2 text-sm text-gray-600">Remember me</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-between">
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm text-blue-500 hover:text-blue-800"
                        >
                            Forgot your password?
                        </Link>

                        <button
                            type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                            :disabled="form.processing"
                        >
                            Login
                        </button>
                    </div>

                    <p class="text-center text-gray-500 text-xs mt-4">
                        Don't have an account?
                        <Link :href="route('register')" class="text-blue-500 hover:text-blue-800">
                            Signup Here
                        </Link>
                    </p>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
:deep(*) {
    font-family: 'Roboto', sans-serif;
}
</style>