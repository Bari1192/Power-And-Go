<template>

    <BaseLayout>
        <Slider />
    </BaseLayout>

</template>

<script>
import BaseLayout from '@layouts/BaseLayout.vue';
import Slider from '@layouts/sliders/Slider.vue';

export default {
    components: {
        BaseLayout,
        Slider,
    },
};
</script>
