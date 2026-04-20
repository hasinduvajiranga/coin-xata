<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    show: Boolean,
    account: Object // Null if creating new
});

const emit = defineEmits(['close']);

const form = useForm({
    name: props.account?.name || '',
    balance: props.account?.balance || '',
    type: props.account?.type || 'checking',
});

const types = ['checking', 'savings', 'credit', 'cash', 'investment'];

const submit = () => {
    if (props.account) {
        form.put(route('accounts.update', props.account.id), {
            onSuccess: () => emit('close'),
        });
    } else {
        form.post(route('accounts.store'), {
            onSuccess: () => {
                form.reset();
                emit('close');
            },
        });
    }
};
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="emit('close')"></div>
        
        <div class="relative glass-card w-full max-w-md rounded-3xl p-6 sm:p-8 shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold tracking-tight">{{ account ? 'Edit' : 'Add' }} Account</h3>
                <button @click="emit('close')" class="w-10 h-10 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition-colors">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <label class="block text-sm font-bold text-slate-500 dark:text-slate-400 mb-2 px-1">Account Name</label>
                    <input 
                        v-model="form.name" 
                        type="text" 
                        placeholder="e.g. HNB Savings"
                        class="w-full px-4 py-3.5 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl focus:ring-2 focus:ring-lime-500 font-bold"
                        required
                    >
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-500 dark:text-slate-400 mb-2 px-1">Initial Balance</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-slate-400">LKR</span>
                        <input 
                            v-model="form.balance" 
                            type="number" 
                            step="0.01" 
                            placeholder="0.00"
                            class="w-full pl-14 pr-4 py-3.5 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl focus:ring-2 focus:ring-lime-500 font-bold text-lg"
                            required
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-500 dark:text-slate-400 mb-2 px-1">Account Type</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button 
                            v-for="type in types" 
                            :key="type"
                            type="button"
                            @click="form.type = type"
                            :class="[
                                'py-3 rounded-xl text-xs font-bold uppercase tracking-wider transition-all',
                                form.type === type ? 'bg-lime-500 text-white shadow-lg shadow-lime-500/30' : 'bg-slate-100 dark:bg-slate-800 text-slate-500'
                            ]"
                        >
                            {{ type }}
                        </button>
                    </div>
                </div>

                <button 
                    type="submit" 
                    :disabled="form.processing"
                    class="w-full py-4 bg-gradient-to-r from-lime-500 to-lime-600 text-white font-bold rounded-2xl shadow-lg shadow-lime-500/30 hover:shadow-lime-500/40 hover:scale-[1.01] transition-all disabled:opacity-50"
                >
                    {{ account ? 'Update Account' : 'Create Account' }}
                </button>
            </form>
        </div>
    </div>
</template>
