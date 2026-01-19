<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineOptions({
    layout: null,
});

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
    <div class="min-h-screen bg-gradient-to-br from-indigo-100 via-white to-purple-100 flex items-center justify-center">
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl p-10">
            <Head title="Register" />
            <h1 class="text-3xl font-extrabold text-indigo-700 mb-8 text-center drop-shadow">
                <Link href="/" class="hover:text-indigo-500 transition">Code Forum</Link>
            </h1>
            <form @submit.prevent="submit" class="space-y-7">
                <div>
                    <InputLabel
                        for="name"
                        value="Name"
                        class="text-lg font-semibold text-gray-700 mb-2"
                    />
                    <TextInput
                        id="name"
                        type="text"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 mt-1"
                        v-model="form.name"
                        autofocus
                        autocomplete="name"
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>
                <div>
                    <InputLabel
                        for="email"
                        value="Email"
                        class="text-lg font-semibold text-gray-700 mb-2"
                    />
                    <TextInput
                        id="email"
                        type="email"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 mt-1"
                        v-model="form.email"
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
                <div>
                    <InputLabel
                        for="password"
                        value="Password"
                        class="text-lg font-semibold text-gray-700 mb-2"
                    />
                    <TextInput
                        id="password"
                        type="password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 mt-1"
                        v-model="form.password"
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>
                <div>
                    <InputLabel
                        for="password_confirmation"
                        value="Confirm Password"
                        class="text-lg font-semibold text-gray-700 mb-2"
                    />
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 mt-1"
                        v-model="form.password_confirmation"
                        autocomplete="new-password"
                    />
                    <InputError
                        class="mt-2"
                        :message="form.errors.password_confirmation"
                    />
                </div>
                <div class="flex items-center justify-between mt-6">
                    <Link
                        :href="route('login')"
                        class="text-sm text-indigo-600 underline hover:text-indigo-900 transition"
                    >
                        Already registered?
                    </Link>
                    <PrimaryButton
                        class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-bold px-8 py-3 rounded-lg shadow hover:scale-105 transition"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Register
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>
