<template>
    <!-- Kártya -->
    <div class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl
             hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
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

    <!-- Szerkesztési Űrlap -->
    <div v-if="isEditing" class="bg-gray-100 border-4 border-sky-800 rounded-lg mt-2 p-4">
        <h5 class="mb-2 text-xl font-bold text-sky-700">Adatmódosítás: {{ editableData.title }} </h5>
        <div class="flex flex-col space-y-2">
            <input v-model="editableData.title" type="text" class="border p-2 rounded" placeholder="Cím" />
            <p class="mt-3 font-normal text-gray-700 dark:text-gray-400">Teljesítmény:</p>
            <input v-model="editableData.teljesitmeny" type="number" class="border p-2 rounded"
                placeholder="Teljesítmény (kW)" />

            <p class="mt-t font-normal text-gray-700 dark:text-gray-400">Hatótáv:</p>
            <input v-model="editableData.vegsebesseg" type="number" class="border p-2 rounded"
                placeholder="Hatótáv (km/h)" />

            <p class="mt-3 font-normal text-gray-700 dark:text-gray-400">Végsebesség:</p>
            <input v-model="editableData.hatotav" type="number" class="border p-2 rounded"
                placeholder="Végsebesség (km/h)" />

            <p class="mt-3 font-normal text-gray-700 dark:text-gray-400">Abroncsméret:</p>
            <input v-model="editableData.abroncs" type="number" class="border p-2 rounded"
                placeholder="pl: 165|65-R15"/>
        </div>
        <div class="flex justify-end space-x-2 mt-4">
            <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" @click="saveChanges">
                Mentés
            </button>
            <button class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" @click="cancelEdit">
                Mégse
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
        teljesitmeny: [Number],
        vegsebesseg: [Number],
        hatotav: [Number],
        abroncs: [String],
    },
    data() {
        return {
            isEditing: false, // Szerkesztési állapot
            editableData: [], // Lokálisan szerkesztett adatok
        };
    },
    methods: {
        editFleet() {
            this.isEditing = !this.isEditing;
            if (this.isEditing) {
                this.editableData = {
                    title: this.title,
                    teljesitmeny: this.teljesitmeny,
                    vegsebesseg: this.vegsebesseg,
                    hatotav: this.hatotav,
                    abroncs: this.abroncs,
                };
            }
        },
        saveChanges() {
            this.$emit('update', this.src, this.editableData);
            this.isEditing = false;
        },
        cancelEdit() {
            this.isEditing = false;
        },
        deleteFleet() {
            this.$emit('delete', this.src);
        },
    },
};
</script>
