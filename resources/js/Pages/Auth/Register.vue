# Register.vue
<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Create an Account" />
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="flex w-full max-w-4xl bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Left side with logo and heading -->
            <div class="w-1/2 bg-blue-200 p-8 flex flex-col justify-center items-center">
                <h2 class="text-4xl font-bold text-white mb-4">
                    Developing Your
                    <span class="text-orange-400">People</span>
                </h2>
                <img 
                    src="/images/QQ.png" 
                    alt="logo" 
                    class="mb-4" 
                    width="200" 
                    height="150"
                />
            </div>

            <!-- Right side with registration form -->
            <div class="w-1/2 p-8">
                <h2 class="text-2xl font-bold mb-6">Create an Account</h2>
                
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <TextInput
                            id="name"
                            type="text"
                            v-model="form.name"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Full Name"
                            required
                            autofocus
                            autocomplete="name"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="mb-4">
                        <TextInput
                            id="email"
                            type="email"
                            v-model="form.email"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Email"
                            required
                            autocomplete="username"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="mb-4">
                        <TextInput
                            id="password"
                            type="password"
                            v-model="form.password"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Password"
                            required
                            autocomplete="new-password"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="mb-4">
                        <TextInput
                            id="password_confirmation"
                            type="password"
                            v-model="form.password_confirmation"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Confirm Password"
                            required
                            autocomplete="new-password"
                        />
                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>

                    <div class="mb-4">
                        <button
                            type="submit"
                            class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600"
                            :disabled="form.processing"
                            :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                        >
                            Create Account
                        </button>
                    </div>

                    <p class="text-center text-gray-600">
                        Already have an account?
                        <Link 
                            :href="route('login')"
                            class="text-blue-500 hover:text-blue-700"
                        >
                            Login
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