<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { usePage, router } from '@inertiajs/vue3'
import logo from '../Landing/assets/img/ic_logo.png'


const page = usePage()
const email = ref('')
const password = ref('')
const remember = ref(false)
const showPassword = ref(false)
const errorMessage = ref('')


const csrf = page.props.csrf_token

onMounted(() => {
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
    axios.defaults.withCredentials = true
    axios.defaults.headers.common['Accept'] = 'application/json'
})

const submitForm = async () => {
    try {
        await axios.post('/user/login', {
            email: email.value,
            password: password.value,
            remember: remember.value,
            _token: csrf,
        })
        router.visit('/user/dashboard')
    } catch (error) {
        errorMessage.value = 'Login gagal, periksa kembali email dan password'
    }
}
</script>

<template>
    <div class="min-h-screen flex">
        <!-- Logo Section -->
        <div class="hidden md:flex flex-col justify-center items-center bg-white px-4 py-5 w-1/2 rounded-br-[60px]">
            <img :src="logo" alt="Logo" class="w-full max-w-md" />
        </div>

        <!-- Login Form -->
        <div class="flex flex-col justify-center items-center bg-gray-100 w-full md:w-1/2 px-6">
            <div class="w-full max-w-md">
                <div class="text-center mb-4">
                    <h2 class="font-bold text-xl">SISTEM PPDB</h2>
                    <p class="text-gray-600">Login untuk melanjutkan</p>
                </div>

                <div class="bg-white rounded shadow p-6">
                    <h3 class="text-center font-bold text-lg mb-4">Login Pengguna</h3>
                    <form @submit.prevent="submitForm">
                        <!-- Email -->
                        <div class="mb-4">
                            <label class="block mb-1">Email</label>
                            <input v-model="email" type="email" class="w-full border rounded p-2" required />
                        </div>

                        <!-- Password -->
                        <div class="mb-4 relative">
                            <label class="block mb-1">Password</label>
                            <input :type="showPassword ? 'text' : 'password'" v-model="password"
                                class="w-full border rounded p-2 pr-10" required />
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute top-8 right-3 text-gray-500">
                                <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>

                        <!-- Remember Me + Submit -->
                        <div class="flex items-center justify-between mb-4">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" v-model="remember" />
                                <span class="text-sm">Ingat saya</span>
                            </label>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Masuk
                            </button>
                        </div>
                        <p class="text-sm text-center mt-4">
                            Belum punya akun?
                            <a href="/user/register" class="text-blue-600 hover:underline">Daftar di sini</a>
                        </p>

                        <!-- Error Message -->
                        <div v-if="errorMessage" class="text-red-600 text-sm text-center mt-2">
                            {{ errorMessage }}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
body,
html {
    height: 100%;
    margin: 0;
}
</style>
