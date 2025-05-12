<template>
    <div>
        <BaseHeader />
        <div class="background bg-opacity-25 w-full min-h-[screen]">
            <main class="container w-full h-full mx-auto ">
                <slot />
            </main>
            <hullamInverz />
        </div>
        <BaseFooter />
    </div>
</template>

<script setup>
import BaseHeader from '@components/layout/BaseHeader.vue'
import BaseFooter from '@components/layout/BaseFooter.vue'
import hullamInverz from '@layouts/hullamInverz.vue';
</script>

<style>
.background {
    background: url('@img/Welcome/blocks3.webp'); 
    background-position: center;
    background-repeat:repeat-y;
    background-size: contain;
}
</style>