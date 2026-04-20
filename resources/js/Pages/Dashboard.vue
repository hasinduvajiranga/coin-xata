<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import StatCard from '@/Components/StatCard.vue';

const props = defineProps({
    accounts: Array,
    recentTransactions: Array,
    budgets: Array,
    stats: Object
});

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
</script>

<template>
    <AppLayout>
        <Head title="Dashboard" />

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <StatCard 
                title="Total Balance" 
                :value="formatCurrency(stats.totalBalance)" 
                icon="ph-wallet"
                color="from-lime-400 to-lime-600"
                :trend="12.5"
            />
            <StatCard 
                title="Monthly Income" 
                :value="formatCurrency(stats.monthlyIncome)" 
                icon="ph-arrow-down-left"
                color="from-blue-400 to-blue-600"
                :trend="8.2"
            />
            <StatCard 
                title="Monthly Expenses" 
                :value="formatCurrency(stats.monthlyExpenses)" 
                icon="ph-arrow-up-right"
                color="from-red-400 to-red-600"
                :trend="-4.1"
            />
            <StatCard 
                title="Savings Rate" 
                :value="stats.savingsRate + '%'" 
                icon="ph-piggy-bank"
                color="from-purple-400 to-purple-600"
                :progress="stats.savingsRate"
            />
        </div>

        <!-- Middle Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Transactions -->
            <div class="lg:col-span-2 space-y-6">
                <div class="glass-card rounded-3xl p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Recent Transactions</h3>
                        <Link :href="route('transactions.index')" class="text-sm font-semibold text-lime-600 hover:text-lime-700 transition-colors">View All</Link>
                    </div>
                    
                    <div class="space-y-4">
                        <div 
                            v-for="tx in recentTransactions" 
                            :key="tx.id"
                            class="flex items-center justify-between p-4 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-all border border-transparent hover:border-slate-100 dark:hover:border-white/5 group"
                        >
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 group-hover:bg-white dark:group-hover:bg-slate-700 transition-colors">
                                    <i :class="['ph', getTransactionIcon(tx.category), 'text-2xl']"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 dark:text-slate-100">{{ tx.category }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">{{ tx.transaction_date }} • {{ tx.account?.name }}</p>
                                </div>
                            </div>
                            <p :class="['font-bold text-lg', tx.type === 'expense' ? 'text-red-500' : 'text-lime-600']">
                                {{ tx.type === 'expense' ? '-' : '+' }} {{ formatCurrency(tx.amount) }}
                            </p>
                        </div>

                        <div v-if="recentTransactions.length === 0" class="text-center py-10 opacity-50">
                            <i class="ph ph-mask-sad text-4xl mb-2"></i>
                            <p class="text-sm font-medium">No recent transactions found.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accounts Summary -->
            <div class="space-y-6">
                <div class="glass-card rounded-3xl p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Active Accounts</h3>
                        <Link :href="route('accounts.index')" class="text-sm font-semibold text-lime-600 hover:text-lime-700 transition-colors">Manage</Link>
                    </div>

                    <div class="space-y-4">
                        <div 
                            v-for="account in accounts" 
                            :key="account.id"
                            class="p-4 rounded-2xl bg-slate-50/50 dark:bg-slate-800/30 border border-slate-100 dark:border-white/5 hover:border-lime-500/30 transition-all group"
                        >
                            <div class="flex justify-between items-center mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10  h-10 rounded-xl bg-white dark:bg-slate-700 flex items-center justify-center text-slate-500 dark:text-slate-400 shadow-sm">
                                        <i :class="['ph', getAccountIcon(account.type), 'text-xl']"></i>
                                    </div>
                                    <span class="font-bold text-sm tracking-tight capitalize text-slate-900 dark:text-slate-100">{{ account.name }}</span>
                                </div>
                                <span class="bg-lime-500/10 text-lime-600 dark:text-lime-400 text-[10px] font-bold px-2 py-0.5 rounded-lg uppercase tracking-wider">{{ account.type }}</span>
                            </div>
                            <p class="text-xl font-bold tracking-tight text-slate-900 dark:text-slate-100">{{ formatCurrency(account.balance) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Ad/Promo Card or secondary goal -->
                <div class="rounded-3xl bg-gradient-to-br from-indigo-600 to-purple-600 p-6 text-white relative overflow-hidden group">
                    <div class="relative z-10">
                        <h4 class="text-lg font-bold mb-2">Join Premium</h4>
                        <p class="text-white/80 text-sm mb-4">Unlock advanced charts and multi-device sync.</p>
                        <button class="px-4 py-2 bg-white text-indigo-600 rounded-xl font-bold text-sm hover:bg-slate-50 transition-colors">Coming Soon</button>
                    </div>
                    <i class="ph ph-crown absolute -bottom-4 -right-4 text-8xl text-white/10 rotate-12 group-hover:scale-110 group-hover:rotate-0 transition-all duration-700"></i>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
