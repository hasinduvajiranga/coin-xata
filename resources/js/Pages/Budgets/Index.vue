<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import BudgetModal from '@/Components/BudgetModal.vue';

const props = defineProps({
    budgets: Array,
    currentMonth: String
});

const showModal = ref(false);

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-LK', {
        style: 'currency',
        currency: 'LKR',
        minimumFractionDigits: 2
    }).format(value);
};

const getCategoryIcon = (category) => {
    const icons = {
        'Housing': 'ph-house-line',
        'Groceries': 'ph-shopping-bag',
        'Food & Dining': 'ph-hamburger',
        'Transport': 'ph-car',
        'Shopping': 'ph-shopping-cart',
        'Bills & Utilities': 'ph-lightning',
        'Health & Fitness': 'ph-heartbeat',
        'Travel': 'ph-airplane',
        'Entertainment': 'ph-game-controller',
        'Education': 'ph-graduation-cap',
        'Personal Care': 'ph-sparkles',
        'Investment': 'ph-chart-pie',
        'Insurance': 'ph-shield-check',
        'Other': 'ph-dots-three-circle'
    };
    return icons[category] || 'ph-receipt';
};

const deleteBudget = (id) => {
    if (confirm('Are you sure you want to delete this budget?')) {
        router.delete(route('budgets.destroy', id));
    }
};

const changeMonth = (e) => {
    router.get(route('budgets.index'), { month: e.target.value }, { preserveState: true });
};
</script>

<template>
    <AppLayout>
        <Head title="Budgets" />

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Monthly Budgets</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Plan and track your spending limits</p>
            </div>
            <div class="flex items-center gap-4">
                <input 
                    type="month" 
                    :value="currentMonth"
                    @change="changeMonth"
                    class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-white/5 bg-white dark:bg-slate-900 text-sm font-bold focus:ring-2 focus:ring-lime-500"
                >
                <button 
                    @click="showModal = true"
                    class="btn-primary flex items-center gap-2"
                >
                    <i class="ph ph-plus"></i>
                    <span>Set Budget</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
                v-for="budget in budgets" 
                :key="budget.id"
                class="glass-card rounded-3xl p-6 group relative overflow-hidden"
            >
                <div class="flex justify-between items-start mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-500 dark:text-slate-400 group-hover:bg-lime-500 group-hover:text-white transition-all duration-300 shadow-sm">
                        <i :class="['ph', getCategoryIcon(budget.category), 'text-3xl']"></i>
                    </div>
                    <button @click="deleteBudget(budget.id)" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-red-500 transition-all opacity-0 group-hover:opacity-100">
                        <i class="ph ph-trash text-lg"></i>
                    </button>
                </div>

                <div>
                    <h4 class="text-lg font-bold tracking-tight mb-1">{{ budget.category }}</h4>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ currentMonth }} • {{ budget.is_recurring ? 'Recurring' : 'One-time' }}</p>
                    
                    <div class="mt-6">
                        <div class="flex justify-between text-sm font-bold mb-2">
                            <span class="text-slate-500 dark:text-slate-400">Limit</span>
                            <span>{{ formatCurrency(budget.limit_amount) }}</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2">
                            <div class="bg-lime-500 h-full rounded-full w-[0%] transition-all duration-1000"></div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase">0% Spent</p>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <button 
                v-if="budgets.length === 0"
                @click="showModal = true"
                class="border-2 border-dashed border-slate-200 dark:border-white/10 rounded-3xl p-12 flex flex-col items-center justify-center gap-4 text-slate-400 hover:border-lime-500/50 hover:text-lime-500 transition-all group"
            >
                <i class="ph ph-target text-5xl group-hover:scale-110 transition-transform"></i>
                <p class="font-bold">Set your first budget for this month</p>
            </button>
        </div>

        <!-- Budget Modal -->
        <BudgetModal :show="showModal" @close="showModal = false" />
    </AppLayout>
</template>
