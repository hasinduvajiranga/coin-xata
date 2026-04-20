<script setup>
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Bar, Pie } from 'vue-chartjs';
import { 
    Chart as ChartJS, 
    Title, 
    Tooltip, 
    Legend, 
    BarElement, 
    CategoryScale, 
    LinearScale, 
    ArcElement 
} from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement);

const props = defineProps({
    transactions: Array,
    accounts: Array
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-LK', {
        style: 'currency',
        currency: 'LKR',
        minimumFractionDigits: 0
    }).format(value);
};

// Calculations
const totalIncome = computed(() => {
    return props.transactions
        .filter(t => t.type === 'income')
        .reduce((sum, t) => sum + parseFloat(t.amount), 0);
});

const totalExpenses = computed(() => {
    return props.transactions
        .filter(t => t.type === 'expense')
        .reduce((sum, t) => sum + parseFloat(t.amount), 0);
});

const categoryData = computed(() => {
    const categories = {};
    props.transactions
        .filter(t => t.type === 'expense')
        .forEach(t => {
            categories[t.category] = (categories[t.category] || 0) + parseFloat(t.amount);
        });

    return {
        labels: Object.keys(categories),
        datasets: [{
            data: Object.values(categories),
            backgroundColor: [
                '#84cc16', '#3b82f6', '#ef4444', '#8b5cf6', '#f59e0b', 
                '#10b981', '#f43f5e', '#6366f1', '#ec4899', '#06b6d4'
            ],
            borderWidth: 0
        }]
    };
});

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom',
            labels: {
                padding: 20,
                usePointStyle: true,
                font: {
                    family: "'Plus Jakarta Sans', sans-serif",
                    size: 11,
                    weight: 'bold'
                }
            }
        }
    }
};
</script>

<template>
    <AppLayout>
        <Head title="Analytics" />

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Summary Stats -->
            <div class="glass-card rounded-3xl p-8 flex flex-col justify-center">
                <h3 class="text-xl font-bold mb-8">Financial Overview</h3>
                <div class="space-y-8">
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Income</span>
                            <span class="text-2xl font-bold text-lime-600">{{ formatCurrency(totalIncome) }}</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 h-3 rounded-full overflow-hidden">
                            <div class="bg-lime-500 h-full w-full"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Expenses</span>
                            <span class="text-2xl font-bold text-red-500">{{ formatCurrency(totalExpenses) }}</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 h-3 rounded-full overflow-hidden">
                            <div 
                                class="bg-red-500 h-full transition-all duration-1000" 
                                :style="{ width: totalIncome > 0 ? `${(totalExpenses/totalIncome)*100}%` : '0%' }"
                            ></div>
                        </div>
                    </div>
                    <div class="pt-6 border-t border-slate-100 dark:border-white/5">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold">Net Savings</span>
                            <span class="text-3xl font-extrabold tracking-tighter" :class="totalIncome - totalExpenses >= 0 ? 'text-slate-900 dark:text-white' : 'text-red-500'">
                                {{ formatCurrency(totalIncome - totalExpenses) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Spending by Category -->
            <div class="glass-card rounded-3xl p-8 min-h-[400px]">
                <h3 class="text-xl font-bold mb-6">Spending by Category</h3>
                <div class="h-[300px]">
                    <Pie :data="categoryData" :options="chartOptions" />
                </div>
            </div>
        </div>

        <!-- Monthly Trends Placeholder -->
        <div class="glass-card rounded-3xl p-8 mt-6">
            <h3 class="text-xl font-bold mb-6">Monthly Trends</h3>
            <div class="h-[300px] flex items-center justify-center border-2 border-dashed border-slate-100 dark:border-white/5 rounded-2xl">
                <div class="text-center opacity-30">
                    <i class="ph ph-chart-bar-horizontal text-5xl mb-2"></i>
                    <p class="font-bold">Bar Chart Coming Soon</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
