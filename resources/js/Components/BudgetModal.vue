<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    show: Boolean,
});

const emit = defineEmits(['close']);

const form = useForm({
    category: '',
    limit_amount: '',
    month: new Date().toISOString().split('T')[0].substring(0, 7), // YYYY-MM
    is_recurring: false,
});

const categories = ['Housing', 'Groceries', 'Food & Dining', 'Transport', 'Shopping', 'Bills & Utilities', 'Health & Fitness', 'Travel', 'Entertainment', 'Education', 'Personal Care', 'Investment', 'Insurance', 'Other'];

const submit = () => {
    form.post(route('budgets.store'), {
        onSuccess: () => {
            form.reset();
            emit('close');
        },
    });
};
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="emit('close')"></div>
        
        <div class="relative glass-card w-full max-w-md rounded-3xl p-6 sm:p-8 shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold tracking-tight">Set Budget</h3>
                <button @click="emit('close')" class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition-colors">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <label class="block text-sm font-bold text-slate-500 dark:text-slate-400 mb-2 px-1">Category</label>
                    <select 
                        v-model="form.category" 
                        class="w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl focus:ring-2 focus:ring-lime-500 font-bold"
                        required
                    >
                        <option value="">Select Category</option>
                        <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-500 dark:text-slate-400 mb-2 px-1">Budget Limit</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-slate-400">LKR</span>
                        <input 
                            v-model="form.limit_amount" 
                            type="number" 
                            step="0.01" 
                            placeholder="0.00"
                            class="w-full pl-14 pr-4 py-3.5 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl focus:ring-2 focus:ring-lime-500 font-bold text-lg"
                            required
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-500 dark:text-slate-400 mb-2 px-1">Month</label>
                    <input 
                        v-model="form.month" 
                        type="month" 
                        class="w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl focus:ring-2 focus:ring-lime-500 font-bold"
                        required
                    >
                </div>

                <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl">
                    <input 
                        v-model="form.is_recurring" 
                        type="checkbox" 
                        id="recurring"
                        class="w-5 h-5 rounded border-none bg-slate-200 dark:bg-slate-700 text-lime-500 focus:ring-lime-500"
                    >
                    <label for="recurring" class="text-sm font-bold text-slate-600 dark:text-slate-300 select-none cursor-pointer">
                        Repeat budget monthly
                    </label>
                </div>

                <button 
                    type="submit" 
                    :disabled="form.processing"
                    class="w-full py-4 bg-gradient-to-r from-lime-500 to-lime-600 text-white font-bold rounded-2xl shadow-lg shadow-lime-500/30 hover:shadow-lime-500/40 hover:scale-[1.01] transition-all disabled:opacity-50"
                >
                    Save Budget
                </button>
            </form>
        </div>
    </div>
</template>
