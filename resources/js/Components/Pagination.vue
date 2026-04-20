<script setup>
import { computed } from 'vue';

const props = defineProps({
    currentPage: {
        type: Number,
        required: true
    },
    totalPages: {
        type: Number,
        required: true
    },
    from: {
        type: Number,
        required: true
    },
    to: {
        type: Number,
        required: true
    },
    total: {
        type: Number,
        required: true
    }
});

const emit = defineEmits(['page-changed']);

const pages = computed(() => {
    const pages = [];
    const current = props.currentPage;
    const last = props.totalPages;
    
    // Always show first page
    if (last >= 1) pages.push(1);
    
    // Show pages around current page
    let start = Math.max(2, current - 2);
    let end = Math.min(last - 1, current + 2);
    
    // Add ellipsis if needed
    if (start > 2) {
        pages.push('...');
    }
    
    for (let i = start; i <= end; i++) {
        if (i > 1 && i < last) {
            pages.push(i);
        }
    }
    
    // Add ellipsis if needed
    if (end < last - 1) {
        pages.push('...');
    }
    
    // Always show last page
    if (last >= 2 && last !== 1) pages.push(last);
    
    return pages;
});

const goToPage = (page) => {
    if (page >= 1 && page <= props.totalPages && page !== props.currentPage) {
        emit('page-changed', page);
    }
};

const goToPrevious = () => {
    if (props.currentPage > 1) {
        goToPage(props.currentPage - 1);
    }
};

const goToNext = () => {
    if (props.currentPage < props.totalPages) {
        goToPage(props.currentPage + 1);
    }
};
</script>

<template>
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4">
        <!-- Results info -->
        <div class="text-sm font-medium text-slate-600 dark:text-slate-400">
            Showing 
            <span class="font-bold text-lime-600 dark:text-lime-400">{{ from }}</span>
            to 
            <span class="font-bold text-lime-600 dark:text-lime-400">{{ to }}</span>
            of 
            <span class="font-bold text-lime-600 dark:text-lime-400">{{ total }}</span>
            results
        </div>
        
        <!-- Pagination controls -->
        <div class="flex items-center gap-2">
            <!-- Previous button -->
            <button
                @click="goToPrevious"
                :disabled="currentPage === 1"
                class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all
                       bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400
                       hover:bg-lime-100 dark:hover:bg-lime-900/30 hover:text-lime-700 dark:hover:text-lime-300
                       disabled:opacity-50 disabled:cursor-not-allowed
                       border border-slate-200 dark:border-white/10"
            >
                <i class="ph ph-caret-left"></i>
            </button>
            
            <!-- Page numbers -->
            <div class="flex items-center gap-1">
                <button
                    v-for="page in pages"
                    :key="page"
                    @click="goToPage(page)"
                    :disabled="page === '...'"
                    class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all
                           border"
                    :class="page === currentPage 
                        ? 'bg-gradient-to-r from-lime-500 to-lime-600 text-white border-lime-500 shadow-lg shadow-lime-500/25' 
                        : page === '...' 
                            ? 'bg-transparent text-slate-400 cursor-default border-transparent'
                            : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-white/10 hover:bg-lime-100 dark:hover:bg-lime-900/30 hover:text-lime-700 dark:hover:text-lime-300'"
                >
                    {{ page }}
                </button>
            </div>
            
            <!-- Next button -->
            <button
                @click="goToNext"
                :disabled="currentPage === totalPages"
                class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all
                       bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400
                       hover:bg-lime-100 dark:hover:bg-lime-900/30 hover:text-lime-700 dark:hover:text-lime-300
                       disabled:opacity-50 disabled:cursor-not-allowed
                       border border-slate-200 dark:border-white/10"
            >
                <i class="ph ph-caret-right"></i>
            </button>
        </div>
    </div>
</template>
