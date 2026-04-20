<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AccountModal from '@/Components/AccountModal.vue';

const props = defineProps({
    accounts: Array
});

const showModal = ref(false);
const editingAccount = ref(null);

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-LK', {
        style: 'currency',
        currency: 'LKR',
        minimumFractionDigits: 2
    }).format(value);
};

const getAccountIcon = (type) => {
    const icons = {
        'checking': 'ph-bank',
        'savings': 'ph-bank',
        'credit': 'ph-credit-card',
        'cash': 'ph-money',
        'investment': 'ph-chart-line-up'
    };
    return icons[type] || 'ph-wallet';
};

const editAccount = (account) => {
    editingAccount.value = account;
    showModal.value = true;
};

const openAddModal = () => {
    editingAccount.value = null;
    showModal.value = true;
};

const deleteAccount = (id) => {
    if (confirm('Are you sure you want to delete this account?')) {
        router.delete(route('accounts.destroy', id), {
            onError: (errors) => {
                if (errors.error) alert(errors.error);
            }
        });
    }
};
</script>

<template>
    <AppLayout>
        <Head title="Accounts" />

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Bank Accounts</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Manage your various financial accounts</p>
            </div>
            <button 
                @click="openAddModal"
                class="btn-primary flex items-center gap-2"
            >
                <i class="ph ph-plus"></i>
                <span>Add Account</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
                v-for="account in accounts" 
                :key="account.id"
                class="glass-card rounded-3xl p-6 hover-lift relative group overflow-hidden"
            >
                <div class="flex justify-between items-start mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 group-hover:bg-lime-500 group-hover:text-white transition-all duration-300">
                        <i :class="['ph', getAccountIcon(account.type), 'text-3xl']"></i>
                    </div>
                    <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button @click="editAccount(account)" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                            <i class="ph ph-pencil-simple text-lg text-slate-400"></i>
                        </button>
                        <button @click="deleteAccount(account.id)" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                            <i class="ph ph-trash text-lg text-red-400"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <h4 class="text-lg font-bold tracking-tight mb-1">{{ account.name }}</h4>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ account.type }}</p>
                    <div class="mt-4 flex items-baseline gap-2">
                        <span class="text-2xl sm:text-3xl font-bold tracking-tighter">{{ formatCurrency(account.balance) }}</span>
                    </div>
                </div>

                <!-- Subtle background icon -->
                <i :class="['ph', getAccountIcon(account.type), 'absolute -bottom-6 -right-6 text-9xl text-slate-100 dark:text-white/[0.02] -rotate-12 group-hover:rotate-0 transition-transform duration-700']"></i>
            </div>

            <!-- Empty State -->
            <button 
                v-if="accounts.length === 0"
                @click="openAddModal"
                class="border-2 border-dashed border-slate-200 dark:border-white/10 rounded-3xl p-10 flex flex-col items-center justify-center gap-4 text-slate-400 hover:border-lime-500/50 hover:text-lime-500 transition-all group"
            >
                <i class="ph ph-plus-circle text-5xl group-hover:scale-110 transition-transform"></i>
                <p class="font-bold">Add your first account</p>
            </button>
        </div>

        <!-- Account Modal -->
        <AccountModal 
            :show="showModal" 
            :account="editingAccount"
            @close="showModal = false"
        />
    </AppLayout>
</template>
