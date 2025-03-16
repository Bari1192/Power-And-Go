<template>
    <BaseLayout>
        <PricesComponent />
</BaseLayout>
</template>

<script>
import BaseLayout from '@layouts/BaseLayout.vue';
import PricesComponent from '@pages/indexpage/PricesComponent.vue';
export default {
    components: {
        BaseLayout,
        PricesComponent,
    },
};
</script>
