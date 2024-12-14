<template>
    <div class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl
         hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700" @click="toggleButtons">
        <img class="object-cover w-64 h-32 ml-3 rounded-md"
            :src="`http://backend.vm1.test/storage/carsImages/${src}.png`" :alt="imgalt">
        <div class="flex flex-col justify-between p-4 leading-normal">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                {{ title }}
            </h5>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                <slot />
            </p>
        </div>
    </div>
    <div v-if="isSelected" class="cols-2 mb-8">
        <div class="flex justify-end ml-4">
            <button class="w-1/4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 my-2 mx-4 rounded"
                @click.stop="editFleet">
                Módosítás
            </button>
            <button class="w-1/4 bg-red-500 hover:bg-red-700 text-white font-bold p-1 my-2 rounded"
                @click.stop="deleteFleet">
                Törlés
            </button>
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
    },
    data() {
        return {
            isSelected: false,
        };
    },
    methods: {
        toggleButtons() {
            this.isSelected = !this.isSelected;
        },
        editFleet() {
            this.$emit('edit', this.src);
        },
        deleteFleet() {
            this.$emit('delete', this.src);
        },
    },

}

</script>