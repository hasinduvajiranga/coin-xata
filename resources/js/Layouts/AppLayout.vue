<script setup>
import { ref, onMounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import TopHeader from '@/Components/TopHeader.vue';
import TransactionModal from '@/Components/TransactionModal.vue';
import AccountModal from '@/Components/AccountModal.vue';
import BudgetModal from '@/Components/BudgetModal.vue';

const isSidebarOpen = ref(false);
const theme = ref('dark');
const showTransactionModal = ref(false);
const showAccountModal = ref(false);
const showBudgetModal = ref(false);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const toggleTheme = () => {
    theme.value = theme.value === 'dark' ? 'light' : 'dark';
    updateTheme();
    localStorage.setItem('theme', theme.value);
};

const updateTheme = () => {
    if (theme.value === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
};

onMounted(() => {
    const savedTheme = localStorage.getItem('theme') || 'dark';
    theme.value = savedTheme;
    updateTheme();
});
</script>

<template>
    <div class="flex h-screen overflow-hidden text-slate-900 dark:text-slate-100 selection:bg-lime-500/30">
        <!-- Sidebar -->
        <Sidebar :is-open="isSidebarOpen" @toggle="toggleSidebar" />

        <!-- Overlay for mobile sidebar -->
        <div 
            v-if="isSidebarOpen"
            class="fixed inset-0 bg-black/50 z-40 lg:hidden backdrop-blur-sm"
            @click="toggleSidebar"
        ></div>

        <main class="flex-1 flex flex-col h-screen overflow-hidden">
            <!-- Header -->
            <TopHeader 
                @toggle-sidebar="toggleSidebar" 
                @toggle-theme="toggleTheme" 
                @open-transaction-modal="showTransactionModal = true"
                :theme="theme"
            />

            <!-- Content Area -->
            <div id="content" class="flex-1 overflow-y-auto p-6 lg:p-10 space-y-6">
                <slot />
            </div>
        </main>

        <!-- Modals -->
        <TransactionModal 
            :show="showTransactionModal" 
            :accounts="$page.props.accounts || []"
            :budgets="$page.props.budgets || []"
            @close="showTransactionModal = false" 
        />
        <AccountModal 
            :show="showAccountModal" 
            @close="showAccountModal = false" 
        />
        <BudgetModal 
            :show="showBudgetModal" 
            @close="showBudgetModal = false" 
        />
    </div>
</template>
