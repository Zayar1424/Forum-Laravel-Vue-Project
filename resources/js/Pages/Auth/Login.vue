<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineOptions({
    layout: null,
});

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

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-indigo-100 via-white to-purple-100 flex items-center justify-center">
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl p-10">
            <Head title="Log in" />
            <h1 class="text-3xl font-extrabold text-indigo-700 mb-8 text-center drop-shadow">
                <Link href="/" class="hover:text-indigo-500 transition">Code Forum</Link>
            </h1>

            <div
                v-if="status"
                class="mb-4 text-sm font-medium text-green-600 text-center"
            >
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="space-y-7">
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
                        autofocus
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
                        autocomplete="current-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="flex items-center mt-2">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600">Remember me</span>
                </div>

                <div class="flex items-center justify-between mt-6">
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm text-indigo-600 underline hover:text-indigo-900 transition"
                    >
                        Forgot your password?
                    </Link>
                    <PrimaryButton
                        class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-bold px-8 py-3 rounded-lg shadow hover:scale-105 transition"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Log in
                    </PrimaryButton>
                </div>
                <div class="text-center mt-6">
                    <Link
                        href="/register"
                        class="text-sm text-indigo-600 underline hover:text-indigo-900 transition"
                    >
                        Don't have an account? Register
                    </Link>
                </div>
            </form>
        </div>
    </div>
</template>
