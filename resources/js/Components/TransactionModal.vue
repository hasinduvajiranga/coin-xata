<script setup>
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    show: Boolean,
    accounts: Array,
    budgets: Array
});

const emit = defineEmits(['close']);

const form = useForm({
    type: 'expense',
    amount: '',
    category: '',
    account_id: '',
    budget_id: '',
    transaction_date: new Date().toISOString().split('T')[0],
    recurring: 'one-time',
    description: '',
});

const categories = {
    expense: ['Housing', 'Groceries', 'Food & Dining', 'Transport', 'Shopping', 'Bills & Utilities', 'Health & Fitness', 'Travel', 'Entertainment', 'Education', 'Personal Care', 'Investment', 'Insurance', 'Other'],
    income: ['Salary', 'Freelance Project', 'Investment', 'Loan Income', 'Gift', 'Other']
};

const submit = () => {
    form.post(route('transactions.store'), {
        onSuccess: () => {
            form.reset();
            emit('close');
        },
    });
};

const setType = (type) => {
    form.type = type;
    form.category = '';
};
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="emit('close')"></div>
        
        <!-- Modal Content -->
        <div class="relative glass-card w-full max-w-lg rounded-3xl p-6 sm:p-8 overflow-y-auto max-h-[90vh] shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Add Transaction</h3>
                <button @click="emit('close')" class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition-colors">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <!-- Type Toggle -->
                <div class="flex gap-3">
                    <button 
                        type="button" 
                        @click="setType('expense')"
                        :class="[
                            'flex-1 py-3 rounded-xl text-sm font-bold transition-all border-2', 
                            form.type === 'expense' ? 'bg-red-500 border-red-500 text-white shadow-lg shadow-red-500/30' : 'border-slate-200 dark:border-slate-700 text-slate-500'
                        ]"
                    >
                        Expense
                    </button>
                    <button 
                        type="button" 
                        @click="setType('income')"
                        :class="[
                            'flex-1 py-3 rounded-xl text-sm font-bold transition-all border-2', 
                            form.type === 'income' ? 'bg-green-500 border-green-500 text-white shadow-lg shadow-green-500/30' : 'border-slate-200 dark:border-slate-700 text-slate-500'
                        ]"
                    >
                        Income
                    </button>
                </div>

                <!-- Amount -->
                <div>
                    <label class="block text-sm font-bold text-slate-500 dark:text-slate-400 mb-2 px-1">Amount</label>
                    <div class="relative">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 font-bold text-slate-400">LKR</span>
                        <input 
                            v-model="form.amount" 
                            type="number" 
                            step="0.01" 
                            placeholder="0.00"
                            class="w-full pl-16 pr-6 py-3.5 bg-slate-50 dark:bg-slate-800 border-none rounded-xl focus:ring-2 focus:ring-lime-500 font-bold text-lg dark:text-white"
                            required
                        >
                    </div>
                    <div v-if="form.errors.amount" class="text-red-500 text-xs mt-1 px-1">{{ form.errors.amount }}</div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-bold text-slate-500 dark:text-slate-400 mb-2 px-1">Category</label>
                        <select 
                            v-model="form.category" 
                            class="w-full px-6 py-3.5 bg-slate-50 dark:bg-slate-800 border-none rounded-xl focus:ring-2 focus:ring-lime-500 font-semibold appearance-none dark:text-white"
                            required
                        >
                            <option value="" class="dark:bg-slate-900">Select Category</option>
                            <option v-for="cat in categories[form.type]" :key="cat" :value="cat" class="dark:bg-slate-900">{{ cat }}</option>
                        </select>
                    </div>

                    <!-- Account -->
                    <div>
                        <label class="block text-sm font-bold text-slate-500 dark:text-slate-400 mb-2 px-1">Account</label>
                        <select 
                            v-model="form.account_id" 
                            class="w-full px-6 py-3.5 bg-slate-50 dark:bg-slate-800 border-none rounded-xl focus:ring-2 focus:ring-lime-500 font-semibold appearance-none dark:text-white"
                        >
                            <option value="" class="dark:bg-slate-900">No Account</option>
                            <option v-for="acc in accounts" :key="acc.id" :value="acc.id" class="dark:bg-slate-900">{{ acc.name }}</option>
                        </select>
                    </div>

                    <!-- Date -->
                    <div>
                        <label class="block text-sm font-bold text-slate-500 dark:text-slate-400 mb-2 px-1">Date</label>
                        <input 
                            v-model="form.transaction_date" 
                            type="date" 
                            class="w-full px-6 py-3.5 bg-slate-50 dark:bg-slate-800 border-none rounded-xl focus:ring-2 focus:ring-lime-500 font-semibold dark:text-white"
                            required
                        >
                    </div>

                    <!-- Budget -->
                    <div v-if="form.type === 'expense'">
                        <label class="block text-sm font-bold text-slate-500 dark:text-slate-400 mb-2 px-1">Budget (Optional)</label>
                        <select 
                            v-model="form.budget_id" 
                            class="w-full px-6 py-3.5 bg-slate-50 dark:bg-slate-800 border-none rounded-xl focus:ring-2 focus:ring-lime-500 font-semibold appearance-none"
                        >
                            <option value="">No Budget</option>
                            <option v-for="budget in budgets" :key="budget.id" :value="budget.id">
                                {{ budget.category }} ({{ budget.month }})
                            </option>
                        </select>
                    </div>

                    <!-- Recurring -->
                    <div :class="{ 'sm:col-span-2': form.type === 'income' }">
                        <label class="block text-sm font-bold text-slate-500 dark:text-slate-400 mb-2 px-1">Recurring</label>
                        <select 
                            v-model="form.recurring" 
                            class="w-full px-6 py-3.5 bg-slate-50 dark:bg-slate-800 border-none rounded-xl focus:ring-2 focus:ring-lime-500 font-semibold appearance-none dark:text-white"
                        >
                            <option value="one-time" class="dark:bg-slate-900">One-time</option>
                            <option value="daily" class="dark:bg-slate-900">Daily</option>
                            <option value="weekly" class="dark:bg-slate-900">Weekly</option>
                            <option value="monthly" class="dark:bg-slate-900">Monthly</option>
                        </select>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-bold text-slate-500 dark:text-slate-400 mb-2 px-1">Description</label>
                    <textarea 
                        v-model="form.description" 
                        rows="2"
                        placeholder="Optional notes..."
                        class="w-full px-6 py-3.5 bg-slate-50 dark:bg-slate-800 border-none rounded-xl focus:ring-2 focus:ring-lime-500 font-medium resize-none text-sm"
                    ></textarea>
                </div>

                <!-- Submit -->
                <button 
                    type="submit" 
                    :disabled="form.processing"
                    class="w-full py-4 bg-gradient-to-r from-lime-500 to-lime-600 text-white font-bold rounded-xl shadow-lg shadow-lime-500/30 hover:shadow-lime-500/40 hover:scale-[1.01] transition-all disabled:opacity-50 disabled:hover:scale-100"
                >
                    <span v-if="form.processing">Processing...</span>
                    <span v-else>Save Transaction</span>
                </button>
            </form>
        </div>
    </div>
</template>
