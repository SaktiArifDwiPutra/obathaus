<template>
    <div class="max-w-md mx-auto bg-[#FFFDF8] min-h-screen relative pb-32 font-sans text-[#4A3B32] shadow-2xl overflow-hidden">
        
        <div v-if="customModal.show" class="fixed inset-0 z-[120] flex items-center justify-center bg-[#4A3B32]/40 backdrop-blur-sm px-5">
            <div class="bg-white rounded-[2rem] p-6 w-full max-w-sm text-center shadow-2xl transform transition-all border-4 border-[#F2E8D9]">
                <div class="text-5xl mb-4">
                    <span v-if="customModal.type === 'loading'" class="animate-spin inline-block">⏳</span>
                    <span v-else-if="customModal.type === 'success'">✔️</span>
                    <span v-else>🙈</span>
                </div>
                <h3 class="font-black text-xl text-[#4A3B32] mb-2">{{ customModal.title }}</h3>
                <p class="text-sm font-bold text-gray-500 mb-6">{{ customModal.message }}</p>
                <button v-if="customModal.type !== 'loading'" @click="closeModal" class="w-full bg-[#D98A59] hover:bg-[#C47545] text-white font-black py-3 rounded-2xl transition shadow-md">
                    Oke, Mengerti!
                </button>
            </div>
        </div>

        <div v-if="showFeedbackModal" class="fixed inset-0 z-[110] flex items-center justify-center bg-[#4A3B32]/50 backdrop-blur-sm px-5">
            <div class="bg-white rounded-[2.5rem] p-8 w-full max-w-sm shadow-2xl border-4 border-[#F2E8D9] relative overflow-hidden">
                <div class="absolute -right-5 -top-5 w-20 h-20 bg-[#748E63]/10 rounded-full"></div>
                
                <h3 class="font-black text-2xl text-[#4A3B32] mb-1 text-center">Beri Rating 🧉</h3>
                <p class="text-[10px] font-bold text-gray-400 text-center uppercase tracking-widest mb-6">Hanya untuk yang sudah beli</p>
                
                <form @submit.prevent="submitFeedback" class="space-y-4">
                    <input v-model="feedbackForm.customer_name" type="text" required placeholder="Nama kamu" 
                           class="w-full border-2 border-[#F2E8D9] bg-[#FFFDF8] rounded-2xl focus:ring-0 focus:border-[#748E63] px-4 py-3 text-sm font-bold">

                    <input v-model="feedbackForm.whatsapp_number" type="tel" required placeholder="Nomor WA (untuk validasi)" 
                           class="w-full border-2 border-[#F2E8D9] bg-[#FFFDF8] rounded-2xl focus:ring-0 focus:border-[#748E63] px-4 py-3 text-sm font-bold">
                    <p class="text-[9px] font-bold text-gray-400 mt-1 ml-2">*Wajib sama dengan nomor saat memesan</p>

                    <div class="flex justify-center gap-2 py-2">
                        <button v-for="star in 5" :key="star" type="button" @click="feedbackForm.rating = star" class="text-3xl transition transform hover:scale-110">
                            {{ feedbackForm.rating >= star ? '⭐' : '☆' }}
                        </button>
                    </div>

                    <textarea v-model="feedbackForm.message" required rows="3" placeholder="Apa kata kamu soal rasanya?" 
                              class="w-full border-2 border-[#F2E8D9] bg-[#FFFDF8] rounded-2xl focus:ring-0 focus:border-[#748E63] px-4 py-3 text-sm font-bold"></textarea>

                    <div class="flex gap-2 pt-2">
                        <button type="button" @click="showFeedbackModal = false" class="flex-1 bg-gray-100 text-gray-400 font-black py-3 rounded-2xl">Batal</button>
                        <button type="submit" :disabled="feedbackForm.processing" class="flex-2 bg-[#748E63] text-white font-black py-3 px-6 rounded-2xl shadow-md disabled:opacity-50">
                            Kirim 🚀
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="relative bg-[#D98A59] pt-12 pb-16 text-center z-10">
            <div class="w-24 h-24 mx-auto bg-white rounded-full p-1 shadow-lg border-[5px] border-[#FFFDF8] mb-3 flex items-center justify-center relative z-20">
                <img 
                    :src="logo" 
                    alt="Logo Obat Haus"
                    class="w-full h-full object-cover rounded-full"
                />
            </div>

            <h1 class="text-3xl font-black tracking-widest font-serif mt-2 text-white drop-shadow-md">
                OBAT HAUS
            </h1>

            <p class="text-sm font-bold text-[#FFFDF8] mt-1 opacity-90 bg-black/10 inline-block px-4 py-1 rounded-full">
                resep by noona
            </p>

            <svg class="absolute -bottom-1 left-0 w-full pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#FFFDF8" fill-opacity="1" d="M0,192L48,197.3C96,203,192,213,288,208C384,203,480,181,576,176C672,171,768,181,864,202.7C960,224,1056,256,1152,261.3C1248,267,1344,245,1392,234.7L1440,224L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>

        <div v-show="!showCheckoutForm" class="px-5 relative z-20 -mt-2">
            <div class="bg-[#748E63] rounded-[2rem] p-6 text-center text-white shadow-xl shadow-[#748E63]/30 mb-8 relative overflow-hidden">
                <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-full -mr-10 -mt-10"></div>
                <h2 class="text-2xl font-black mb-1 drop-shadow-sm">EFEK SAMPING: NAGIH!</h2>
                <p class="text-xs opacity-90 mb-3">A Perfect Blend of ThaiTea, Kopi & GreenTea.</p>
                <div class="inline-block bg-white text-[#748E63] text-xs px-4 py-1.5 rounded-full font-black shadow-sm">
                    Isi Bersih 250ML 🍼
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3 mb-8">
                <div class="bg-white rounded-2xl p-3 text-center shadow-sm border-2 border-[#F2E8D9]">
                    <div class="text-2xl mb-1">✨</div>
                    <div class="text-[10px] font-black text-[#D98A59]">Bahan<br>Premium</div>
                </div>
                <div class="bg-white rounded-2xl p-3 text-center shadow-sm border-2 border-[#F2E8D9]">
                    <div class="text-2xl mb-1">🍯</div>
                    <div class="text-[10px] font-black text-[#D98A59]">Manisnya<br>Pas</div>
                </div>
                <div class="bg-white rounded-2xl p-3 text-center shadow-sm border-2 border-[#F2E8D9]">
                    <div class="text-2xl mb-1">💰</div>
                    <div class="text-[10px] font-black text-[#D98A59]">Ramah<br>Kantong</div>
                </div>
            </div>

            <h3 class="font-black text-xl text-[#4A3B32] mb-4 pl-2">Pilih Menu Favoritmu</h3>
            
            <div class="space-y-4">
                <div v-for="product in products" :key="product.id" class="bg-white p-3 rounded-[2rem] shadow-sm border-2 border-[#F2E8D9] flex items-center transition hover:shadow-md">
                    <div class="w-24 h-24 bg-[#FFFDF8] rounded-3xl mr-4 flex-shrink-0 flex items-center justify-center border-2 border-[#F2E8D9]">
                        <span class="text-4xl">🥤</span>
                    </div>
                    <div class="flex-grow flex flex-col justify-between h-24 py-2 pr-2">
                        <div>
                            <h4 class="font-black text-[#4A3B32] text-md">{{ product.name }}</h4>
                            <p class="text-[#D98A59] font-black text-sm mt-1">Rp {{ Number(product.price).toLocaleString('id-ID') }}</p>
                        </div>
                        <div class="flex justify-end items-center mt-auto">
                            <button v-if="getQty(product.id) === 0" @click="addToCart(product)" 
                                    class="text-xs font-black text-[#748E63] border-2 border-[#748E63] px-5 py-2 rounded-full hover:bg-[#748E63] hover:text-white transition shadow-sm">
                                + Tambah
                            </button>
                            <div v-else class="flex items-center gap-3 bg-[#F2E8D9] rounded-full px-2 py-1">
                                <button @click="removeFromCart(product)" class="w-8 h-8 rounded-full bg-white text-[#4A3B32] shadow-sm font-black flex items-center justify-center">-</button>
                                <span class="font-black text-sm text-[#4A3B32] w-4 text-center">{{ getQty(product.id) }}</span>
                                <button @click="addToCart(product)" class="w-8 h-8 rounded-full bg-[#748E63] text-white shadow-sm font-black flex items-center justify-center">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-12 mb-10">
                <div class="bg-white p-6 rounded-[2rem] border-4 border-dashed border-[#F2E8D9] text-center shadow-sm">
                    <p class="font-bold text-[#4A3B32] mb-3 text-sm">Gimana rasa Obat Haus hari ini? ✨</p>
                    <button @click="showFeedbackModal = true" class="bg-[#748E63] text-white px-6 py-2 rounded-full font-black text-xs shadow-md hover:scale-105 transition">
                        Tulis Ulasan Kamu
                    </button>
                </div>
            </div>

            <div class="h-40"></div> 
        </div>

        <div v-show="showCheckoutForm" class="px-5 relative z-20 pt-6">
            <button @click="showCheckoutForm = false" class="mb-6 bg-white text-[#D98A59] font-black px-5 py-2.5 rounded-full shadow-sm border-2 border-[#F2E8D9] hover:bg-gray-50 flex items-center">
                <i class="fa-solid fa-arrow-left mr-2"></i> Menu
            </button>
            
            <h2 class="font-black text-2xl text-[#4A3B32] mb-6 pl-2">Detail Pengiriman</h2>
            
            <form @submit.prevent="submitOrder" class="space-y-5 bg-white p-6 rounded-[2rem] shadow-xl border-2 border-[#F2E8D9]">
                <div>
                    <label class="block text-sm font-black text-[#748E63] mb-2 pl-2">Nama Pemesan</label>
                    <input v-model="form.customer_name" type="text" required placeholder="Atas nama siapa?" 
                           class="w-full border-2 border-[#F2E8D9] bg-[#FFFDF8] rounded-2xl focus:ring-0 focus:border-[#D98A59] px-4 py-3 font-bold text-[#4A3B32]">
                </div>

                <div>
                    <label class="block text-sm font-black text-[#748E63] mb-2 pl-2">Nomor WhatsApp Aktif</label>
                    <input v-model="form.whatsapp_number" type="tel" required placeholder="081234567..." 
                           class="w-full border-2 border-[#F2E8D9] bg-[#FFFDF8] rounded-2xl focus:ring-0 focus:border-[#D98A59] px-4 py-3 font-bold text-[#4A3B32]">
                </div>

                <div>
                    <div class="flex justify-between items-end mb-2 pl-2 pr-1">
                        <label class="block text-sm font-black text-[#748E63]">Lokasi Pengantaran</label>
                        <i class="fa-solid fa-map-location-dot text-[#D98A59]"></i>
                    </div>
                    <div id="map" class="w-full h-48 bg-[#F2E8D9] rounded-2xl border-2 border-[#F2E8D9] z-10 overflow-hidden shadow-inner relative"></div>
                    <div class="flex justify-end mt-3">
                        <button type="button" @click="locateMe" class="text-xs font-black text-[#D98A59] bg-[#FFFDF8] border-2 border-[#D98A59] px-4 py-2 rounded-full hover:bg-[#D98A59] hover:text-white transition flex items-center shadow-sm">
                            <i class="fa-solid fa-crosshairs mr-2"></i> Titik Lokasi Saya
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-black text-[#748E63] mb-2 pl-2 mt-2">Alamat Lengkap</label>
                    <textarea v-model="form.address_detail" rows="2" required placeholder="Detail patokan (Cth: Pagar hitam)" 
                              class="w-full border-2 border-[#F2E8D9] bg-[#FFFDF8] rounded-2xl focus:ring-0 focus:border-[#D98A59] px-4 py-3 font-bold text-[#4A3B32]"></textarea>
                </div>

                <div class="bg-[#FFFDF8] p-4 rounded-2xl border-2 border-[#F2E8D9] mt-6">
                    <div class="flex justify-between font-black text-xl text-[#4A3B32]">
                        <span class="text-sm self-center text-gray-500">Total Tagihan:</span>
                        <span>Rp {{ cartTotal.toLocaleString('id-ID') }}</span>
                    </div>
                </div>

                <button type="submit" :disabled="form.processing" class="w-full bg-[#D98A59] hover:bg-[#C47545] text-white font-black py-4 rounded-2xl mt-4 disabled:opacity-50 transition shadow-lg shadow-[#D98A59]/40 text-lg text-center">
                    Pesan Sekarang
                </button>
            </form>
            <div class="h-80"></div> 
        </div>

        <div class="absolute bottom-0 w-full z-30">
            <svg class="w-full pointer-events-none -mb-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#D98A59" fill-opacity="1" d="M0,64L48,80C96,96,192,128,288,144C384,160,480,160,576,144C672,128,768,96,864,85.3C960,75,1056,85,1152,106.7C1248,128,1344,160,1392,176L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
            <div class="bg-[#D98A59] pb-8 pt-4 px-5 text-center relative z-40">
                <div class="bg-white/20 inline-block px-6 py-2 rounded-full mb-6 pointer-events-none">
                    <p class="font-black text-white text-sm">Terkoneksi Bersama Kami</p>
                </div>
                <div class="flex justify-center gap-5 mb-8">
                    <a href="https://www.instagram.com/obathaus.co?igsh=NWNqcmI4NjltaWw5" target="_blank" class="w-14 h-14 bg-white text-[#D98A59] rounded-[1.5rem] flex items-center justify-center text-2xl shadow-lg hover:scale-110 hover:rotate-3 transition transform border-4 border-white/50 cursor-pointer"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://www.tiktok.com/@obathaus.co?_r=1&_t=ZS-96DtqUHWwQH" target="_blank" class="w-14 h-14 bg-white text-[#D98A59] rounded-[1.5rem] flex items-center justify-center text-2xl shadow-lg hover:scale-110 hover:-rotate-3 transition transform border-4 border-white/50 cursor-pointer"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="https://wa.me/08981224117" target="_blank" class="w-14 h-14 bg-[#748E63] text-white rounded-[1.5rem] flex items-center justify-center text-2xl shadow-lg hover:scale-110 hover:rotate-3 transition transform border-4 border-white/50 cursor-pointer"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
                <p class="text-[11px] text-white/70 font-bold uppercase tracking-widest">© 2026 Obat Haus - Resep by Noona</p>
            </div>
        </div>

        <div v-if="cart.length > 0 && !showCheckoutForm" class="fixed bottom-6 left-0 right-0 px-5 max-w-md mx-auto z-50">
            <div class="bg-[#748E63] text-white rounded-full p-3 pl-6 flex justify-between items-center shadow-2xl shadow-[#748E63]/40 border-[3px] border-white">
                <div>
                    <p class="text-[11px] font-black opacity-80 uppercase">{{ cartTotalItems }} Menu Terpilih</p>
                    <p class="font-black text-xl">Rp {{ cartTotal.toLocaleString('id-ID') }}</p>
                </div>
                <button @click="openCheckout" class="bg-white text-[#748E63] px-8 py-3 rounded-full font-black transition shadow-sm text-sm hover:bg-gray-50 text-center">
                    Checkout
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, nextTick } from 'vue';
import { useForm } from '@inertiajs/vue3';
const logo = "/logonya.png";

const props = defineProps({ products: Array });

const showCheckoutForm = ref(false);
const cart = ref([]);

// State Custom Modal
const customModal = ref({ show: false, title: '', message: '', type: 'info' });

const showModal = (title, message, type = 'info') => {
    customModal.value = { show: true, title, message, type };
};
const closeModal = () => { customModal.value.show = false; };

let leafletMap = null;
let leafletMarker = null;

const addToCart = (product) => {
    const item = cart.value.find(i => i.id === product.id);
    if (item) item.qty++;
    else cart.value.push({ id: product.id, name: product.name, price: product.price, qty: 1 });
};

const removeFromCart = (product) => {
    const item = cart.value.find(i => i.id === product.id);
    if (item) {
        item.qty--;
        if (item.qty === 0) cart.value = cart.value.filter(i => i.id !== product.id);
    }
};

const getQty = (productId) => {
    const item = cart.value.find(i => i.id === productId);
    return item ? item.qty : 0;
};

const cartTotalItems = computed(() => cart.value.reduce((sum, item) => sum + item.qty, 0));
const cartTotal = computed(() => cart.value.reduce((sum, item) => sum + (item.price * item.qty), 0));

const form = useForm({
    customer_name: '',
    whatsapp_number: '',
    latitude: '-6.840742', 
    longitude: '107.510902',
    address_detail: '',
    cart: []
});

const openCheckout = () => {
    showCheckoutForm.value = true;
    nextTick(() => {
        if (typeof window.L !== 'undefined') {
            const container = window.L.DomUtil.get('map');
            if(container != null) container._leaflet_id = null;

            leafletMap = window.L.map('map').setView([form.latitude, form.longitude], 15);
            window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(leafletMap);
            leafletMarker = window.L.marker([form.latitude, form.longitude], {draggable: true}).addTo(leafletMap);

            leafletMarker.on('dragend', function() {
                const position = leafletMarker.getLatLng();
                form.latitude = position.lat;
                form.longitude = position.lng;
            });
            setTimeout(() => { leafletMap.invalidateSize(); }, 300);
        }
    });
};

const locateMe = () => {
    if (navigator.geolocation && leafletMap && leafletMarker) {
        showModal('Mencari Lokasi 🛰️', 'Sedang melacak lokasi Anda. Pastikan GPS aktif!', 'loading'); 
        
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                form.latitude = lat;
                form.longitude = lng;
                leafletMap.setView([lat, lng], 17);
                leafletMarker.setLatLng([lat, lng]);
                
                showModal('Berhasil', 'Lokasi Anda sudah ditemukan.', 'success');
                setTimeout(() => closeModal(), 1500); 
            },
            (error) => {
                showModal('Gagal Melacak 📍', 'Pastikan GPS aktif dan browser tidak memblokir akses.', 'error');
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
    } else {
        showModal('Oops! 🙈', 'Browser Anda tidak mendukung fitur pelacakan lokasi.', 'error');
    }
};

const submitOrder = () => {
    showModal('Memproses... ⏳', 'Tunggu sebentar, resepmu sedang dicatat.', 'loading');

    form.cart = cart.value; 
    form.post('/checkout', {
        onSuccess: (page) => {
            cart.value = []; 
            showCheckoutForm.value = false; 
            form.reset(); 
            showModal('Berhasil Dipesan!', page.props.flash.success || 'Pesanan berhasil dibuat! Kami akan segera mengirimkannya.', 'success');
        },
        onError: () => {
            showModal('Gagal Memesan 🙈', 'Mohon periksa kembali kelengkapan data diri dan pesananmu.', 'error');
        }
    });
};

// --- LOGIKA FEEDBACK ---
const showFeedbackModal = ref(false);
const feedbackForm = useForm({
    customer_name: '',
    whatsapp_number: '',
    rating: 5,
    message: ''
});

const submitFeedback = () => {
    showModal('Mengirim... ⏳', 'Sedang memvalidasi nomor WA kamu.', 'loading');
    
    feedbackForm.post('/feedback', {
        onSuccess: () => {
            showFeedbackModal.value = false;
            feedbackForm.reset();
            showModal('Terima Kasih! 💖', 'Ulasan kamu sudah terbit di sistem kami.', 'success');
        },
        onError: (errors) => {
            showModal('Gagal Mengirim 🙈', errors.whatsapp_number || 'Pastikan data terisi benar.', 'error');
        }
    });
};
</script>