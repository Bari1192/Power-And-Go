<template>
    <div class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl
         hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
        <img class="object-cover w-64 h-32 ml-3 rounded-md"
            :src="`http://backend.vm1.test/storage/carsImages/${src}.png`" :alt="imgalt">
        <div class="flex flex-col justify-between p-4 leading-normal">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                {{ title }}
            </h5>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                <!-- {{ details}}  -->
                <slot />
            </p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        src: [String, Number],
        imgalt: [String, Number],
        title: [String, Number],
        // details: [String, Number]
    }

}

</script>