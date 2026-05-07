<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    stats: Object,
    orders: Array,
    feedbacks: Array,
});

// State Sistem Tab & Sidebar
const activeTab = ref('pesanan'); // pesanan, history, suara, statistik
const isSidebarOpen = ref(false); 
const selectedOrder = ref(null);

// State Custom Modal Admin
const adminModal = ref({ show: false, title: '', message: '', type: 'info', action: null });

const showAdminModal = (title, message, type, action = null) => {
    adminModal.value = { show: true, title, message, type, action };
};

const closeAdminModal = () => {
    adminModal.value.show = false;
};

// Form Action Laravel
const formAction = useForm({});

// Fungsi Konfirmasi Selesai
const confirmComplete = (id) => {
    showAdminModal(
        'Selesaikan Pesanan? ✅',
        'Pastikan pelanggan sudah membayar dan menerima pesanan.',
        'confirm',
        () => {
            formAction.transform((data) => ({
                ...data,
                status: 'completed', 
            })).patch(route('orders.updateStatus', id), {
                onSuccess: () => {
                    closeAdminModal();
                },
                onError: (err) => {
                    console.error("Gagal update status:", err);
                },
                preserveScroll: true
            });
        }
    );
};

// Fungsi Konfirmasi Hapus
const confirmDelete = (id) => {
    showAdminModal(
        'Hapus Pesanan? 🗑️',
        'Data pesanan akan hilang selamanya dari database.',
        'danger',
        () => {
            formAction.delete(route('orders.destroy', id), {
                onSuccess: () => closeAdminModal(),
                preserveScroll: true
            });
        }
    );
};

// Filter Pesanan Aktif
const filterDate = ref('');
const filterName = ref('');
const filterSortPrice = ref('terbaru');

const pendingOrders = computed(() => {
    let result = props.orders.filter(o => o.status !== 'completed');
    if (filterDate.value) result = result.filter(o => o.created_at.startsWith(filterDate.value));
    if (filterName.value) result = result.filter(o => o.customer_name.toLowerCase().startsWith(filterName.value.toLowerCase()));
    
    if (filterSortPrice.value === 'termahal') result.sort((a, b) => b.total_price - a.total_price);
    else if (filterSortPrice.value === 'termurah') result.sort((a, b) => a.total_price - b.total_price);
    else result.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    
    return result;
});

// Filter History Selesai
const historyOrders = computed(() => props.orders.filter(o => o.status === 'completed'));

// Filter Kotak Suara
const filterStar = ref('all');
const filteredFeedbacks = computed(() => {
    if (filterStar.value === 'all') return props.feedbacks;
    return props.feedbacks.filter(f => f.rating === parseInt(filterStar.value));
});

// WhatsApp API Helper
const openWA = (number, name) => {
    const cleanNumber = number.replace(/\D/g, '');
    const formatted = cleanNumber.startsWith('0') ? '62' + cleanNumber.slice(1) : cleanNumber;
    const text = encodeURIComponent(`Halo Kak ${name}, ini dari Admin Obat Haus. Pesanan Kakak sedang kami proses ya!`);
    window.open(`https://api.whatsapp.com/send?phone=${formatted}&text=${text}`, '_blank');
};
</script>

<template>
    <Head title="Admin Dashboard" />

    <div class="min-h-screen bg-[#FFFDF8] flex font-sans text-[#4A3B32]">
        
        <div v-if="adminModal.show" class="fixed inset-0 z-[150] flex items-center justify-center bg-[#4A3B32]/40 backdrop-blur-sm px-5">
            <div class="bg-white rounded-[2.5rem] p-8 w-full max-w-sm text-center shadow-2xl border-4 border-[#F2E8D9]">
                <div class="text-5xl mb-4">
                    <span v-if="adminModal.type === 'confirm'">🤔</span>
                    <span v-else-if="adminModal.type === 'danger'">⚠️</span>
                    <span v-else>ℹ️</span>
                </div>
                <h3 class="font-black text-xl mb-2">{{ adminModal.title }}</h3>
                <p class="text-sm font-bold text-gray-400 mb-8">{{ adminModal.message }}</p>
                <div class="flex gap-3">
                    <button @click="closeAdminModal" class="flex-1 bg-gray-100 text-gray-400 font-black py-3 rounded-2xl">Batal</button>
                    <button @click="adminModal.action" 
                            :class="adminModal.type === 'danger' ? 'bg-red-500 text-white' : 'bg-[#748E63] text-white'"
                            class="flex-1 font-black py-3 rounded-2xl shadow-md transition active:scale-95">
                        Ya, Lanjut!
                    </button>
                </div>
            </div>
        </div>

        <aside :class="['fixed lg:static inset-y-0 left-0 z-50 w-64 bg-white border-r-4 border-[#F2E8D9] p-6 flex flex-col transition-transform duration-300 lg:translate-x-0', isSidebarOpen ? 'translate-x-0' : '-translate-x-full']">
            <div class="flex items-center justify-between mb-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#D98A59] text-white rounded-xl flex items-center justify-center text-xl border-2 border-[#F2E8D9]">🧉</div>
                    <h2 class="font-black text-lg">Admin Haus</h2>
                </div>
                <button @click="isSidebarOpen = false" class="lg:hidden text-2xl">×</button>
            </div>
            <nav class="space-y-2 flex-grow text-sm font-black uppercase tracking-wider">
                <button @click="activeTab = 'pesanan'; isSidebarOpen = false" :class="activeTab === 'pesanan' ? 'bg-[#D98A59] text-white shadow-md' : 'text-gray-400 hover:bg-[#F2E8D9]'" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl transition text-left">
                    <i class="fa-solid fa-motorcycle w-5"></i> Pesanan Aktif
                </button>
                <button @click="activeTab = 'history'; isSidebarOpen = false" :class="activeTab === 'history' ? 'bg-[#748E63] text-white shadow-md' : 'text-gray-400 hover:bg-[#F2E8D9]'" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl transition text-left">
                    <i class="fa-solid fa-clock-rotate-left w-5"></i> History Selesai
                </button>
                <button @click="activeTab = 'suara'; isSidebarOpen = false" :class="activeTab === 'suara' ? 'bg-[#C59B76] text-white shadow-md' : 'text-gray-400 hover:bg-[#F2E8D9]'" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl transition text-left">
                    <i class="fa-solid fa-comment w-5"></i> Kotak Suara
                </button>
                <button @click="activeTab = 'statistik'; isSidebarOpen = false" :class="activeTab === 'statistik' ? 'bg-[#4A3B32] text-white shadow-md' : 'text-gray-400 hover:bg-[#F2E8D9]'" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl transition text-left">
                    <i class="fa-solid fa-chart-line w-5"></i> Statistik
                </button>
            </nav>
            <Link :href="route('logout')" method="post" as="button" class="mt-auto flex items-center gap-3 px-4 py-3 rounded-2xl font-black text-red-400 hover:bg-red-50 transition text-sm text-left"><i class="fa-solid fa-power-off"></i> Logout</Link>
        </aside>

        <main class="flex-1 min-w-0 flex flex-col h-screen overflow-hidden">
            <header class="lg:hidden bg-white border-b-4 border-[#F2E8D9] p-4 flex items-center justify-between">
                <button @click="isSidebarOpen = true" class="text-xl px-2"><i class="fa-solid fa-bars text-[#D98A59]"></i></button>
                <h1 class="font-black text-[#D98A59] text-sm tracking-widest uppercase italic">Admin Dashboard</h1>
                <div class="w-8"></div>
            </header>

            <div class="flex-1 overflow-y-auto p-4 lg:p-10 scroll-smooth">
                <div class="max-w-6xl mx-auto">
                    
                    <div v-if="activeTab === 'pesanan'" class="animate-fade-in space-y-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <h1 class="text-3xl font-black">Pesanan Aktif 🛵</h1>
                            <div class="flex gap-2 w-full sm:w-auto overflow-x-auto pb-2 sm:pb-0">
                                <input type="date" v-model="filterDate" class="text-xs font-bold border-2 border-[#F2E8D9] rounded-xl px-3 py-2 bg-white">
                                <input type="text" v-model="filterName" placeholder="Cari Nama..." class="text-xs font-bold border-2 border-[#F2E8D9] rounded-xl px-3 py-2 bg-white min-w-[120px]">
                                <select v-model="filterSortPrice" class="text-xs font-bold border-2 border-[#F2E8D9] rounded-xl px-3 py-2 bg-white">
                                    <option value="terbaru">Terbaru</option>
                                    <option value="termahal">Termahal</option>
                                    <option value="termurah">Termurah</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            <div v-for="order in pendingOrders" :key="order.id" class="bg-white rounded-[2rem] border-4 border-[#F2E8D9] p-5 shadow-sm hover:border-[#D98A59] transition group">
                                <div class="flex justify-between items-start mb-4">
                                    <div @click="selectedOrder = order" class="cursor-pointer">
                                        <h3 class="font-black text-lg group-hover:text-[#D98A59] transition">{{ order.customer_name }}</h3>
                                        <p class="text-[10px] font-bold text-gray-400 tracking-widest">{{ new Date(order.created_at).toLocaleTimeString() }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <button @click="openWA(order.whatsapp_number, order.customer_name)" class="w-9 h-9 bg-green-500 text-white rounded-xl flex items-center justify-center shadow-md transition hover:scale-110"><i class="fa-brands fa-whatsapp text-lg"></i></button>
                                        <button @click="confirmComplete(order.id)" class="w-9 h-9 bg-[#748E63] text-white rounded-xl flex items-center justify-center shadow-md transition hover:scale-110"><i class="fa-solid fa-check"></i></button>
                                        <button @click="confirmDelete(order.id)" class="w-9 h-9 bg-red-50 text-red-400 rounded-xl flex items-center justify-center transition hover:bg-red-500 hover:text-white"><i class="fa-solid fa-trash-can text-sm"></i></button>
                                    </div>
                                </div>
                                <div @click="selectedOrder = order" class="bg-[#F8F3EA] p-3 rounded-2xl text-xs font-bold mb-3 cursor-pointer line-clamp-1 border border-[#F2E8D9]">
                                    <i class="fa-solid fa-location-dot text-red-400 mr-1"></i> {{ order.address_detail }}
                                </div>
                                <div class="flex justify-between items-center font-black pt-2 border-t-2 border-dashed border-[#F2E8D9]">
                                    <span class="text-[10px] text-gray-400 uppercase tracking-widest">{{ order.items.length }} Item</span>
                                    <span class="text-[#748E63]">Rp {{ Number(order.total_price).toLocaleString('id-ID') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="activeTab === 'history'" class="animate-fade-in space-y-6">
                        <h1 class="text-3xl font-black text-[#748E63]">Arsip Pesanan ✅</h1>
                        <div class="bg-white rounded-[2.5rem] border-4 border-[#F2E8D9] overflow-hidden shadow-sm overflow-x-auto">
                            <table class="w-full text-left min-w-[600px]">
                                <thead class="bg-[#F8F3EA] text-[10px] font-black uppercase text-gray-500 tracking-widest border-b-4 border-[#F2E8D9]">
                                    <tr>
                                        <th class="p-5">Tanggal</th>
                                        <th class="p-5">Pelanggan</th>
                                        <th class="p-5">WhatsApp</th>
                                        <th class="p-5 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm font-bold">
                                    <tr v-for="order in historyOrders" :key="order.id" class="border-b border-gray-50 hover:bg-[#FFFDF8] transition">
                                        <td class="p-5 text-gray-400">{{ new Date(order.created_at).toLocaleDateString('id-ID') }}</td>
                                        <td class="p-5">{{ order.customer_name }}</td>
                                        <td class="p-5 text-gray-400">{{ order.whatsapp_number }}</td>
                                        <td class="p-5 text-right text-[#748E63] font-black italic">Rp {{ Number(order.total_price).toLocaleString('id-ID') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div v-if="activeTab === 'suara'" class="animate-fade-in space-y-6">
                        <h1 class="text-3xl font-black text-[#C59B76]">Ulasan Pelanggan 💌</h1>
                        <div class="flex overflow-x-auto gap-2 pb-2 scrollbar-hide">
                            <button @click="filterStar = 'all'" :class="filterStar === 'all' ? 'bg-[#748E63] text-white shadow-md' : 'bg-white text-gray-400'" class="px-6 py-2 rounded-full font-black text-xs transition border-2 border-[#F2E8D9]">Semua</button>
                            <button v-for="i in 5" :key="i" @click="filterStar = i.toString()" :class="filterStar === i.toString() ? 'bg-[#D98A59] text-white shadow-md' : 'bg-white text-gray-400'" class="px-5 py-2 rounded-full font-black text-xs transition border-2 border-[#F2E8D9]">⭐ {{ i }}</button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            <div v-for="fb in filteredFeedbacks" :key="fb.id" class="bg-white p-6 rounded-[2rem] border-4 border-[#F2E8D9] shadow-sm relative overflow-hidden">
                                <div class="absolute -right-2 -top-2 text-4xl opacity-10 italic font-serif">"</div>
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="font-black text-[#D98A59]">{{ fb.customer_name }}</h3>
                                        <p class="text-[10px] font-bold text-gray-300 tracking-widest">{{ fb.whatsapp_number }}</p>
                                    </div>
                                    <div class="text-xs text-yellow-500 font-black">⭐ {{ fb.rating }}</div>
                                </div>
                                <p class="text-sm font-bold leading-relaxed text-gray-500 italic">"{{ fb.message }}"</p>
                            </div>
                        </div>
                    </div>

                    <div v-if="activeTab === 'statistik'" class="animate-fade-in space-y-8">
                        <h1 class="text-3xl font-black">Analisa Klinik 📊</h1>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="bg-[#D98A59] p-8 rounded-[2rem] text-white shadow-lg relative overflow-hidden">
                                <div class="absolute -right-5 -bottom-5 w-24 h-24 bg-white/10 rounded-full"></div>
                                <p class="text-[10px] font-black uppercase tracking-widest opacity-80">Total Pemasukan</p>
                                <p class="text-2xl font-black mt-1">Rp {{ Number(stats.revenue).toLocaleString('id-ID') }}</p>
                            </div>
                            <div class="bg-[#748E63] p-8 rounded-[2rem] text-white shadow-lg relative overflow-hidden">
                                <div class="absolute -right-5 -bottom-5 w-24 h-24 bg-white/10 rounded-full"></div>
                                <p class="text-[10px] font-black uppercase tracking-widest opacity-80">Total Transaksi</p>
                                <p class="text-2xl font-black mt-1">{{ stats.total_orders }} <span class="text-xs">TRX</span></p>
                            </div>
                            <div class="bg-[#C59B76] p-8 rounded-[2rem] text-white shadow-lg relative overflow-hidden">
                                <div class="absolute -right-5 -bottom-5 w-24 h-24 bg-white/10 rounded-full"></div>
                                <p class="text-[10px] font-black uppercase tracking-widest opacity-80">Botol Terjual</p>
                                <p class="text-2xl font-black mt-1">{{ stats.total_bottles }} <span class="text-xs">PC</span></p>
                            </div>
                            <div class="bg-[#4A3B32] p-8 rounded-[2rem] text-white shadow-lg relative overflow-hidden">
                                <div class="absolute -right-5 -bottom-5 w-24 h-24 bg-white/10 rounded-full"></div>
                                <p class="text-[10px] font-black uppercase tracking-widest opacity-80">Rating Pasien</p>
                                <p class="text-2xl font-black mt-1">⭐ {{ Number(stats.avg_rating).toFixed(1) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <div v-if="selectedOrder" class="fixed inset-0 z-[100] flex items-center justify-center bg-[#4A3B32]/50 backdrop-blur-sm p-4">
            <div class="bg-white rounded-[2.5rem] w-full max-w-lg shadow-2xl border-4 border-[#F2E8D9] overflow-hidden flex flex-col max-h-[85vh] animate-fade-in">
                <div class="p-6 border-b-4 border-[#F2E8D9] flex justify-between items-center bg-[#F8F3EA] flex-shrink-0">
                    <h2 class="font-black text-lg text-[#D98A59] tracking-widest">RACIKAN PESANAN</h2>
                    <button @click="selectedOrder = null" class="w-10 h-10 bg-white rounded-full font-black text-gray-400 hover:text-red-400 transition shadow-sm border border-gray-100">×</button>
                </div>
                <div class="p-6 overflow-y-auto space-y-6">
                    <div class="flex justify-between items-end border-b-2 border-dashed border-[#F2E8D9] pb-3 px-2">
                        <div>
                            <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest mb-1">Nama Pelanggan</p>
                            <p class="font-black text-xl">{{ selectedOrder.customer_name }}</p>
                        </div>
                        <p class="text-xs font-black text-[#748E63] italic">{{ selectedOrder.whatsapp_number }}</p>
                    </div>

                    <div class="px-2">
                        <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest mb-3">Rincian Menu</p>
                        <ul class="space-y-2">
                            <li v-for="item in selectedOrder.items" :key="item.id" class="flex justify-between text-sm font-bold bg-[#FFFDF8] p-3 rounded-2xl border-2 border-[#F2E8D9]">
                                <span>{{ item.quantity }}x {{ item.product?.name || 'Produk' }}</span>
                                <span class="text-[#D98A59]">Rp {{ Number(item.subtotal).toLocaleString('id-ID') }}</span>
                            </li>
                        </ul>
                    </div>

                    <div class="px-2">
                        <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest mb-2">Alamat Pengiriman</p>
                        <p class="text-xs font-bold bg-gray-50 p-4 rounded-2xl italic border border-gray-100">"{{ selectedOrder.address_detail }}"</p>
                    </div>

                    <a :href="`https://www.google.com/maps/dir/?api=1&destination=${selectedOrder.latitude},${selectedOrder.longitude}`" 
                       target="_blank" 
                       class="mx-2 py-4 bg-[#D98A59] text-white rounded-2xl font-black text-center block shadow-lg hover:bg-[#C47545] transition uppercase">
                        <i class="fa-solid fa-diamond-turn-right mr-2"></i> BUKA RUTE GOOGLE MAPS
                    </a>
                </div>
                <div class="p-6 bg-[#F8F3EA] text-center border-t-2 border-dashed border-[#F2E8D9]">
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-1">Total Bayar</p>
                    <p class="text-2xl font-black text-[#748E63]">Rp {{ Number(selectedOrder.total_price).toLocaleString('id-ID') }}</p>
                </div>
            </div>
        </div>

    </div>
</template>

<style>
.animate-fade-in { animation: fadeIn 0.3s ease-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>