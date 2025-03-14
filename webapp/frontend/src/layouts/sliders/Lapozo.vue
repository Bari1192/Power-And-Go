<template>
    <div>
        <div class="pagination flex justify-center space-x-4 mb-6">
            <button @click="changePage(1)" :disabled="currentPage === 1" class="flipped pagination-button">➤➤</button>
            <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1" class="flipped pagination-button">➤</button>

            <button v-if="currentPage > 1" @click="changePage(currentPage - 1)" class="pagination-number">
                {{ currentPage - 1 }}
            </button>

            <button class="pagination-current">{{ currentPage }}</button>

            <button v-if="currentPage < totalPages" @click="changePage(currentPage + 1)" class="pagination-number">
                {{ currentPage + 1 }}
            </button>

            <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages" class="pagination-button">➤</button>
            <button @click="changePage(totalPages)" :disabled="currentPage === totalPages" class="pagination-button">➤➤</button>
        </div>

        <slot :items="paginatedItems"></slot>
    </div>
</template>

<script setup>
import { ref, computed, nextTick } from 'vue';

const props = defineProps({
    items: { type: Array, required: true },
    itemsPerPage: { type: Number, default: 5 }
});

const currentPage = ref(1);
const transitionDirection = ref('forward');

const totalPages = computed(() => Math.ceil(props.items.length / props.itemsPerPage) || 1);

const paginatedItems = computed(() => {
    const start = (currentPage.value - 1) * props.itemsPerPage;
    const end = start + props.itemsPerPage;
    return props.items.slice(start, end);
});

async function changePage(page) {
    if (page >= 1 && page <= totalPages.value) {
        transitionDirection.value = page > currentPage.value ? 'forward' : 'backward';
        currentPage.value = page;
        await nextTick();
    }
}

defineExpose({ transitionDirection });
</script>

<style scoped>
.flipped {
    display: inline-block;
    transform: scaleX(-1);
}

.pagination-button {
    @apply py-1 px-5 rounded text-lime-300 text-xl border-2 border-sky-700 hover:border-sky-800 transition-all ease-in-out duration-200 bg-slate-600 text-center disabled:cursor-not-allowed disabled:opacity-60;
}

.pagination-number {
    @apply py-1 px-5 rounded min-w-[48px] bg-lime-700 hover:bg-lime-600 text-white text-lg;
}

.pagination-current {
    @apply py-1 px-5 rounded min-w-[48px] bg-lime-500 text-white font-semibold text-xl;
}
</style>
