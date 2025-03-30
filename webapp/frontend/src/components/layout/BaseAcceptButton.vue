<template>
    <button class="flex items-center bg-amber-50 border-green-700 hover:border-green-600 hover:bg-green-100 border-2 text-sky-500 pr-2  hover:text-green-600
      tracking-wider font-bold rounded-lg transition-all duration-200 ease-in-out">
        <i class="fa-solid fa-check text-green-500 text-2xl p-2"></i>
        <span>{{buttonText}}</span>
      </button>
</template>

<script setup>

const props=defineProps({
    buttonText:{
        type:String,
        required:true,
    }
});
</script>