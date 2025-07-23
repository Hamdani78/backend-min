<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { usePage, router } from '@inertiajs/vue3'

const page = usePage()

const email = ref('')
const password = ref('')
const remember = ref(false)
const errorMessage = ref('')
const showPassword = ref(false)

const logo = '/admin/img/ic_logo.png'

const csrf = page.props.csrf_token

onMounted(() => {
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
    axios.defaults.withCredentials = true
    axios.defaults.headers.common['Accept'] = 'application/json'
})

const submitForm = async () => {
    try {
        await axios.post('/admin/login', {
            email: email.value,
            password: password.value,
            remember: remember.value,
            _token: csrf,
        })
        // Gunakan inertia router, bukan window.location
        router.visit('/admin/dashboard')
    } catch (error) {
        console.error(error)
        errorMessage.value = 'Login gagal, cek kembali email dan password'
    }
}
</script>


<template>
    <div class="min-vh-100 d-flex">
        <!-- Logo Side -->
        <div class="d-flex flex-column justify-content-center align-items-center bg-white px-4 py-5"
            style="width: 50%; border-bottom-right-radius: 60px;">
            <img :src="logo" alt="Logo" class="img-fluid" style="max-width: 500px; height: auto;">
        </div>

        <!-- Login Form -->
        <div class="d-flex flex-column justify-content-center align-items-center bg-light px-4" style="width: 50%;">
            <div class="login-box w-100" style="max-width: 400px;">
                <div class="text-center mb-4">
                    <h2 class="fw-bold">ADMIN MIN 1 ROKAN HULU</h2>
                    <p class="text-muted">Silakan masuk untuk melanjutkan</p>
                </div>

                <div class="card shadow-sm p-4">
                    <h3 class="text-center fw-bold mb-3">Login</h3>
                    <form @submit.prevent="submitForm">
                        <div class="form-group mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input v-model="email" type="email" class="form-control" placeholder="Username"
                                    required>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input :type="showPassword ? 'text' : 'password'" v-model="password"
                                    class="form-control" placeholder="Password" required>
                                <button type="button" class="btn btn-outline-secondary"
                                    @click="showPassword = !showPassword">
                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" v-model="remember">
                                <label class="form-check-label" for="remember">Remember Me</label>
                            </div>
                            <button type="submit" class="btn btn-dark">Masuk</button>
                        </div>

                        <div class="text-center">
                            <small>Belum Terdaftar? <a href="#">Registrasi disini</a></small>
                        </div>

                        <div v-if="errorMessage" class="alert alert-danger mt-2">
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
