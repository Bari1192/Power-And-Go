<template>
    <div>
        <BaseHeader />
        <div class="custom-background-third bg-opacity-25">
            <main class="container min-w-full h-h-full mx-auto ">
                <slot />
            </main>
            <hullamInverz />
        </div>
        <BaseFooter />
    </div>
</template>

<script>
import BaseHeader from '@components/layout/BaseHeader.vue'
import BaseFooter from '@components/layout/BaseFooter.vue'
import hullamInverz from '@layouts/hullamInverz.vue';

export default {
    components: {
        BaseHeader,
        BaseFooter,
        hullamInverz,
    }
}
</script>

<style>
.custom-background-third {
    background:
        url('@img/Welcome/leaf1.png');
    background-position: right;
    background-repeat: round;
    overflow: hidden;
}
</style>