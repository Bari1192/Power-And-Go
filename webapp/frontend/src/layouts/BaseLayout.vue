<template>
    <BaseHeader />

    <main class="container h-auto mx-auto ">
        <slot />
    </main>
    <hullamInverz />
    <BaseFooter />
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