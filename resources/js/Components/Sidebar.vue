<script setup>
import { Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    isOpen: Boolean
});

const emit = defineEmits(['toggle']);

const page = usePage();

const navItems = [
    { name: 'Dashboard', icon: 'ph-squares-four', route: 'dashboard', href: route('dashboard') },
    { name: 'Transactions', icon: 'ph-arrows-left-right', route: 'transactions.index', href: route('transactions.index') },
    { name: 'Analytics', icon: 'ph-chart-line-up', route: 'analytics', href: route('analytics') },
    { name: 'Budget', icon: 'ph-target', route: 'budgets.index', href: route('budgets.index') },
    { name: 'Accounts', icon: 'ph-bank', route: 'accounts.index', href: route('accounts.index') },
];

const isActive = (routeName) => {
    return route().current(routeName + '*');
};
</script>

<template>
    <aside
        id="sidebar"
        :class="[
            'fixed lg:static inset-y-0 left-0 z-50 w-24 glass border-r border-slate-200/50 dark:border-white/5 transition-all duration-300 transform',
            isOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
        ]"
    >
        <div class="flex flex-col h-full py-8 px-3">
            <!-- Logo -->
            <div class="flex flex-col items-center gap-2 mb-10">
                <div class="w-14 h-14 rounded-2xl  justify-center ">
                    <img src="images/icon.png">
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 space-y-6">
                <Link
                    v-for="item in navItems"
                    :key="item.name"
                    :href="item.href"
                    class="nav-item group"
                    :class="{ 'active': isActive(item.route) }"
                >
                    <div class="nav-icon">
                        <i :class="['ph', item.icon, 'text-2xl']"></i>
                    </div>
                    <span class="text-xs font-semibold">{{ item.name }}</span>
                </Link>
            </nav>

            <!-- Bottom Settings -->
            <div class="mt-auto space-y-6">
                <Link
                    :href="route('settings')"
                    class="nav-item group"
                    :class="{ 'active': isActive('settings') }"
                >
                    <div class="nav-icon">
                        <i class="ph ph-gear text-2xl"></i>
                    </div>
                    <span class="text-xs font-semibold">Settings</span>
                </Link>
            </div>
        </div>
    </aside>
</template>
