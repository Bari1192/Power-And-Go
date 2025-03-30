<template>
    <button class="flex items-center bg-amber-50 border-green-700 hover:bg-red-100/90 border-2 hover:border-red-500/75 text-sky-500 pr-2  hover:text-red-500
  tracking-wider font-bold rounded-lg transition-all duration-200 ease-in-out">
        <i class="fa-solid fa-xmark text-red-600 text-2xl p-2"></i>
        <span>{{ buttonText }}</span>
    </button>
</template>

<script setup>
const props = defineProps({
    buttonText: {
        type: String,
        required: true,
    }
});
</script>