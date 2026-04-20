<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const settings = [
    { title: 'Currency', value: 'LKR (Sri Lankan Rupee)', icon: 'ph-money' },
    { title: 'Language', value: 'English (US)', icon: 'ph-translate' },
    { title: 'Data Privacy', value: 'AES-256-GCM Encryption Active', icon: 'ph-shield-check' },
    { title: 'Theme', value: 'System / Dark', icon: 'ph-palette' },
];
</script>

<template>
    <AppLayout>
        <Head title="Settings" />

        <div class="max-w-4xl mx-auto space-y-6">
            <div class="glass-card rounded-3xl p-8">
                <h3 class="text-xl font-bold mb-8">General Settings</h3>
                <div class="space-y-4">
                    <div
                        v-for="item in settings"
                        :key="item.title"
                        class="flex justify-between items-center p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 group hover:bg-white dark:hover:bg-slate-800 transition-all border border-transparent hover:border-slate-100 dark:hover:border-white/5"
                    >
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white dark:bg-slate-700 flex items-center justify-center text-slate-500 dark:text-slate-400 group-hover:text-lime-500 transition-colors shadow-sm">
                                <i :class="['ph', item.icon, 'text-xl']"></i>
                            </div>
                            <span class="font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider text-xs">{{ item.title }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">{{ item.value }}</span>
                            <i class="ph ph-caret-right text-slate-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-3xl p-8 border-2 border-dashed border-red-500/20">
                <h3 class="text-xl font-bold mb-4 text-red-500">Danger Zone</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Irreversible actions that affect your entire account data.</p>

                <div class="flex flex-col sm:flex-row gap-4">
                    <button class="px-6 py-3 rounded-2xl bg-red-500/10 text-red-500 font-bold text-sm hover:bg-red-500 hover:text-white transition-all">
                        Clear All Data
                    </button>
                    <button class="px-6 py-3 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-bold text-sm hover:bg-slate-200 transition-all">
                        Export Backup
                    </button>
                </div>
            </div>

            <div class="flex flex-col items-center text-center py-10 gap-1">
                <img src="images/logo.png" class="w-48">
                <p class="text-[10px] italic">Made with ❤️ for professional financial tracking</p>
            </div>
        </div>
    </AppLayout>
</template>
