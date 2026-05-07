<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

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
    <Head title="Admin Login" />

    <!-- Background Vanilla dengan sedikit dekorasi blur -->
    <div class="min-h-screen bg-[#FFFDF8] flex flex-col sm:justify-center items-center pt-10 sm:pt-0 p-5 relative overflow-hidden font-sans text-[#4A3B32]">
        
        <!-- Ornamen Dekorasi Background -->
        <div class="absolute -top-20 -left-20 w-72 h-72 bg-[#D98A59]/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-[#748E63]/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Notifikasi Status (Misal: Password berhasil direset) -->
        <div v-if="status" class="mb-4 font-bold text-sm text-[#748E63] bg-[#748E63]/10 px-4 py-2 rounded-xl text-center w-full max-w-md">
            {{ status }}
        </div>

        <!-- Kartu Login Bubbly -->
        <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white shadow-xl shadow-[#D98A59]/5 overflow-hidden rounded-[2.5rem] border-4 border-[#F2E8D9] relative z-10">
            
            <!-- Logo Obat Haus -->
            <div class="flex justify-center mb-6">
                <div class="w-24 h-24 bg-[#D98A59] rounded-full p-1 shadow-lg border-[5px] border-[#FFFDF8] flex items-center justify-center">
                    <span class="text-4xl drop-shadow-md">🧉</span>
                </div>
            </div>
            
            <div class="text-center mb-8">
                <h2 class="text-2xl font-black text-[#4A3B32]">Halo, Admin! 👋</h2>
                <p class="text-sm font-bold text-gray-400 mt-1 uppercase tracking-widest">Sistem Kendali Obat Haus</p>
            </div>

            <!-- Form Login -->
            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-black text-[#748E63] mb-2 pl-2">Email Admin</label>
                    <input 
                        id="email" 
                        type="email" 
                        v-model="form.email" 
                        required 
                        autofocus 
                        autocomplete="username"
                        class="w-full border-2 border-[#F2E8D9] bg-[#FFFDF8] rounded-2xl focus:ring-0 focus:border-[#D98A59] px-4 py-3 font-bold text-[#4A3B32] transition"
                        placeholder="Masukkan email..."
                    >
                    <p v-show="form.errors.email" class="mt-2 text-red-500 text-xs font-black pl-2">
                        {{ form.errors.email }}
                    </p>
                </div>

                <div>
                    <label for="password" class="block text-sm font-black text-[#748E63] mb-2 pl-2">Kata Sandi</label>
                    <input 
                        id="password" 
                        type="password" 
                        v-model="form.password" 
                        required 
                        autocomplete="current-password"
                        class="w-full border-2 border-[#F2E8D9] bg-[#FFFDF8] rounded-2xl focus:ring-0 focus:border-[#D98A59] px-4 py-3 font-bold text-[#4A3B32] transition"
                        placeholder="••••••••"
                    >
                    <p v-show="form.errors.password" class="mt-2 text-red-500 text-xs font-black pl-2">
                        {{ form.errors.password }}
                    </p>
                </div>

                <!-- Remember Me & Lupa Password -->
                <div class="flex items-center justify-between pl-2 pr-1">
                    <label class="flex items-center cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            v-model="form.remember" 
                            class="rounded-md border-[#D98A59] text-[#D98A59] shadow-sm focus:ring-[#D98A59] w-5 h-5 cursor-pointer"
                        >
                        <span class="ms-2 text-sm font-black text-gray-500">Ingat Saya</span>
                    </label>

                    <Link 
                        v-if="canResetPassword" 
                        :href="route('password.request')" 
                        class="text-xs font-black text-[#D98A59] hover:text-[#C47545] transition"
                    >
                        Lupa Sandi?
                    </Link>
                </div>

                <!-- Tombol Login -->
                <div class="pt-2">
                    <button 
                        type="submit" 
                        :class="{ 'opacity-50 cursor-not-allowed': form.processing }" 
                        :disabled="form.processing"
                        class="w-full bg-[#D98A59] hover:bg-[#C47545] text-white font-black py-4 rounded-2xl transition shadow-lg shadow-[#D98A59]/30 text-lg uppercase tracking-wider"
                    >
                        Masuk Sistem 🚀
                    </button>
                </div>
            </form>
        </div>

        <!-- Tombol Kembali ke Toko -->
        <div class="mt-8 relative z-10">
            <Link href="/" class="text-sm font-black text-gray-400 hover:text-[#D98A59] transition flex items-center bg-white px-5 py-2.5 rounded-full shadow-sm border-2 border-[#F2E8D9]">
                <i class="fa-solid fa-arrow-left mr-2"></i> Ke Halaman Toko
            </Link>
        </div>

    </div>
</template>