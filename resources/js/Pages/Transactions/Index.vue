<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import TransactionModal from '@/Components/TransactionModal.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    transactions: Array,
    accounts: Array,
    budgets: Array,
    filters: Object,
    pagination: Object
});

const showModal = ref(false);

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-LK', {
        style: 'currency',
        currency: 'LKR',
        minimumFractionDigits: 2
    }).format(value);
};

const getTransactionIcon = (category) => {
    const icons = {
        'Food & Dining': 'ph-hamburger',
        'Shopping': 'ph-shopping-cart',
        'Transport': 'ph-car',
        'Entertainment': 'ph-game-controller',
        'Bills & Utilities': 'ph-lightning',
        'Income': 'ph-money',
        'Salary': 'ph-briefcase',
        'Investment': 'ph-chart-pie',
        'Other': 'ph-dots-three-circle'
    };
    return icons[category] || 'ph-receipt';
};

const deleteTransaction = (id) => {
    if (confirm('Are you sure you want to delete this transaction?')) {
        router.delete(route('transactions.destroy', id));
    }
};

const handlePageChange = (page) => {
    router.get(route('transactions.index'), {
        ...props.filters,
        page: page
    }, {
        preserveState: true,
        preserveScroll: true
    });
};
</script>

<template>
    <AppLayout>
        <Head title="Transactions" />

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Transaction History</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Manage and track your financial activities</p>
            </div>
            <button 
                @click="showModal = true"
                class="btn-primary flex items-center gap-2"
            >
                <i class="ph ph-plus"></i>
                <span>Add Transaction</span>
            </button>
        </div>

        <!-- Filters Bar (Simplified for now) -->
        <div class="glass-card rounded-2xl p-4 flex flex-wrap gap-4 items-center">
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input 
                        type="text" 
                        placeholder="Search transactions..."
                        class="w-full pl-12 pr-4 py-2.5 bg-slate-100/50 dark:bg-slate-800/50 border-none rounded-xl focus:ring-2 focus:ring-lime-500 text-sm font-medium"
                    >
                </div>
            </div>
            <div class="flex gap-3">
                <button class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-white/5 text-sm font-bold hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <i class="ph ph-funnel mr-2"></i>Filter
                </button>
                <button class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-white/5 text-sm font-bold hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <i class="ph ph-export mr-2"></i>Export
                </button>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="glass-card rounded-3xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-white/5">
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Transaction</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Category</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Account</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Date</th>
                            <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Amount</th>
                            <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        <tr 
                            v-for="tx in transactions" 
                            :key="tx.id"
                            class="hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition-colors group"
                        >
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 group-hover:bg-white dark:group-hover:bg-slate-700 transition-colors shadow-sm">
                                        <i :class="['ph', getTransactionIcon(tx.category), 'text-xl']"></i>
                                    </div>
                                    <span class="font-bold tracking-tight text-slate-700 dark:text-slate-200">
                                        {{ tx.description_encrypted || tx.category }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-lg text-xs font-bold text-slate-600 dark:text-slate-400">
                                    {{ tx.category }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-500 dark:text-slate-400">
                                {{ tx.account?.name || 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-500 dark:text-slate-400">
                                {{ tx.transaction_date }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-lg" :class="tx.type === 'expense' ? 'text-red-500' : 'text-lime-600'">
                                {{ tx.type === 'expense' ? '-' : '+' }} {{ formatCurrency(tx.amount) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button class="p-2 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-400 hover:text-blue-500 transition-all">
                                        <i class="ph ph-pencil-simple text-lg"></i>
                                    </button>
                                    <button 
                                        @click="deleteTransaction(tx.id)"
                                        class="p-2 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-400 hover:text-red-500 transition-all"
                                    >
                                        <i class="ph ph-trash text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        
                        <tr v-if="transactions.length === 0">
                            <td colspan="6" class="px-6 py-20 text-center opacity-40">
                                <i class="ph ph-receipt text-5xl mb-3"></i>
                                <p class="text-sm font-bold">No transactions found for this period.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="pagination && pagination.total_pages > 1" class="glass-card rounded-3xl overflow-hidden shadow-sm">
            <Pagination 
                :current-page="pagination.current_page"
                :total-pages="pagination.total_pages"
                :from="pagination.from"
                :to="pagination.to"
                :total="pagination.total"
                @page-changed="handlePageChange"
            />
        </div>

        <!-- Add Modal -->
        <TransactionModal 
            :show="showModal" 
            :accounts="accounts" 
            :budgets="budgets"
            @close="showModal = false"
        />
    </AppLayout>
</template>
