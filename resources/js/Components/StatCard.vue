<script setup>
defineProps({
    title: String,
    value: String,
    icon: String,
    color: String,
    trend: Number,
    trendText: String,
    progress: Number
});
</script>

<template>
    <div class="glass-card rounded-3xl p-6 hover-lift transition-all duration-300">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">{{ title }}</p>
                <h3 class="text-2xl sm:text-3xl font-bold tracking-tight">{{ value }}</h3>
            </div>
            <div :class="['w-12 h-12 rounded-2xl bg-gradient-to-br flex items-center justify-center shadow-lg', color]">
                <i :class="['ph', icon, 'text-white text-2xl']"></i>
            </div>
        </div>
        
        <div v-if="progress !== undefined" class="mt-4">
            <div class="w-full bg-slate-200 dark:bg-slate-700/50 rounded-full h-1.5 overflow-hidden">
                <div 
                    class="bg-gradient-to-r from-lime-400 to-lime-600 h-full rounded-full transition-all duration-1000" 
                    :style="{ width: `${progress}%` }"
                ></div>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 font-medium tracking-wide uppercase">{{ progress.toFixed(0) }}% OF BUDGET USED</p>
        </div>
        
        <div v-else-if="trend !== undefined" class="flex items-center gap-2 text-sm mt-2">
            <span 
                :class="[
                    'font-bold px-2 py-0.5 rounded-lg text-[10px]',
                    trend >= 0 ? 'bg-lime-500/10 text-lime-600 dark:text-lime-400' : 'bg-red-500/10 text-red-500'
                ]"
            >
                {{ trend >= 0 ? '+' : '' }}{{ trend.toFixed(1) }}%
            </span>
            <span class="text-slate-400 text-xs font-medium">{{ trendText || 'vs last month' }}</span>
        </div>
    </div>
</template>
