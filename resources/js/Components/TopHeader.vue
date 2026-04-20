<script setup>
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    theme: String
});

const emit = defineEmits(['toggle-sidebar', 'toggle-theme', 'open-transaction-modal']);

const page = usePage();

const getPageTitle = () => {
    const titles = {
        'Dashboard': { title: 'Welcome to CoinXata!', subtitle: 'Your personal expense manager' },
        'Transactions/Index': { title: 'Transactions', subtitle: 'Manage your income and expenses' },
        'Analytics/Index': { title: 'Analytics', subtitle: 'Track your financial trends' },
        'Budgets/Index': { title: 'Budget Planning', subtitle: 'Set and monitor your budgets' },
        'Accounts/Index': { title: 'Bank Accounts', subtitle: 'Manage your accounts' },
        'Settings/Index': { title: 'Settings', subtitle: 'Configure your preferences' }
    };

    return titles[page.component] || titles['Dashboard'];
};
</script>

<template>
    <header class="h-20 flex items-center justify-between px-6 lg:px-10 shrink-0">
        <div class="flex items-center gap-4">
            <button @click="emit('toggle-sidebar')" class="lg:hidden p-2 rounded-xl hover:bg-white/50 dark:hover:bg-white/5 text-slate-500">
                <i class="ph ph-list text-2xl"></i>
            </button>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ getPageTitle().title }}</h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium hidden sm:block">{{ getPageTitle().subtitle }}</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <!-- Universal Add Button -->
            <button 
                @click="emit('open-transaction-modal')" 
                class="hidden md:flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-lime-500 to-lime-600 text-white font-semibold hover:shadow-lg hover:shadow-lime-500/30 transition-all"
            >
                <i class="ph ph-plus"></i>
                <span>Add Transaction</span>
            </button>
            
            <button @click="emit('toggle-theme')" class="w-10 h-10 flex items-center justify-center rounded-full glass-card hover:scale-110 transition-transform">
                <i v-if="theme === 'dark'" class="ph ph-sun text-xl text-amber-500"></i>
                <i v-else class="ph ph-moon text-xl text-slate-600"></i>
            </button>

            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-800 flex items-center justify-center border border-white/20">
                <i class="ph ph-user text-xl text-slate-500"></i>
            </div>
        </div>
    </header>
</template>
