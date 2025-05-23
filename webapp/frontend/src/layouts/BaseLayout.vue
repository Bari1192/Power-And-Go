<template>
    <div>
        <BaseHeader />
        <slot />
        <BaseFooter />
    </div>
</template>

<script setup>
import BaseHeader from '@components/layout/BaseHeader.vue'
import BaseFooter from '@components/layout/BaseFooter.vue'
</script>
