<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
import { ref } from "vue";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const showPassword = ref(false); // Untuk mengontrol visibilitas password

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Head title="Sign-in Page" />
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div
            class="flex w-full max-w-4xl bg-white shadow-lg rounded-lg overflow-hidden"
        >
            <!-- Left side with logo and heading -->
            <div
                class="w-1/3 bg-blue-200 p-8 flex flex-col justify-center items-center"
            >
                <h2 class="text-4xl font-bold text-white mb-4 text-left">
                    Developing Your <br />
                    <span class="text-orange-500">People</span>
                </h2>
                <div class="mt-8">
                    <img
                        src="/images/QQ.png"
                        alt="logo"
                        class="mb-4"
                        width="200"
                        height="150"
                    />
                </div>
            </div>

            <!-- Right side with login form -->
            <div class="w-2/3 p-16">
                <h2 class="text-2xl font-bold mb-6 text-center">Sign-in</h2>

                <div
                    v-if="status"
                    class="mb-4 text-sm font-medium text-green-600"
                >
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
                        <InputError
                            class="mt-2"
                            :message="form.errors.password"
                        />
                    </div>

                    <div class="mb-4 block">
                        <label class="flex items-center">
                            <Checkbox
                                name="remember"
                                v-model:checked="form.remember"
                            />
                            <span class="ms-2 text-sm text-gray-600"
                                >Remember me</span
                            >
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

                        <a
                            :href="`/oauth/google`"
                            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                        >
                            <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24">
                                <path
                                    fill="#EA4335"
                                    d="M5.266 9.765A7.077 7.077 0 0 1 12 4.909c1.69 0 3.218.6 4.418 1.582L19.91 3C17.782 1.145 15.055 0 12 0 7.27 0 3.198 2.698 1.24 6.65l4.026 3.115Z"
                                />
                                <path
                                    fill="#34A853"
                                    d="M16.04 18.013c-1.09.703-2.474 1.078-4.04 1.078a7.077 7.077 0 0 1-6.723-4.823l-4.04 3.067A11.965 11.965 0 0 0 12 24c2.933 0 5.735-1.043 7.834-3l-3.793-2.987Z"
                                />
                                <path
                                    fill="#4A90E2"
                                    d="M19.834 21c2.195-2.048 3.62-5.096 3.62-9 0-.71-.109-1.473-.272-2.182H12v4.637h6.436c-.317 1.559-1.17 2.766-2.395 3.558L19.834 21Z"
                                />
                                <path
                                    fill="#FBBC05"
                                    d="M5.277 14.268A7.12 7.12 0 0 1 4.909 12c0-.782.125-1.533.357-2.235L1.24 6.65A11.934 11.934 0 0 0 0 12c0 1.92.445 3.73 1.237 5.335l4.04-3.067Z"
                                />
                            </svg>
                            Continue with Google
                        </a>

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
                        <Link
                            :href="route('register')"
                            class="text-blue-500 hover:text-blue-800"
                        >
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
    font-family: "Roboto", sans-serif;
}
</style>
