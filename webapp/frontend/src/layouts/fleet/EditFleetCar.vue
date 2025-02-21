<template>
    <div v-if="isEditing" class="bg-gray-300 border-4 text-slate-700 border-sky-800 rounded-lg mt-2 p-4">
        <h5 class="mb-2 text-xl font-bold text-sky-700">Adatmódosítás:</h5>
        <div class="flex flex-col space-y-2">
            <input v-model="localData.title" type="text" class="border p-2 rounded" placeholder="Cím" />
            <p class="mt-3 font-normal text-gray-700 dark:text-gray-400">Teljesítmény:</p>
            <input v-model="localData.motor_power" type="number" class="border p-2 rounded"
                placeholder="Teljesítmény (kW)" />

            <p class="mt-t font-normal text-gray-700 dark:text-gray-400">Hatótáv:</p>
            <input v-model="localData.top_speed" type="number" class="border p-2 rounded"
                placeholder="Hatótáv (km/h)" />

            <p class="mt-3 font-normal text-gray-700 dark:text-gray-400">Végsebesség:</p>
            <input v-model="localData.driving_range" type="number" class="border p-2 rounded"
                placeholder="Végsebesség (km/h)" />

            <p class="mt-3 font-normal text-gray-700 dark:text-gray-400">Abroncsméret:</p>
            <input v-model="localData.abroncs" type="number" class="border p-2 rounded"
                placeholder="pl: 165|65-R15"/>
        </div>
        <div class="flex justify-end space-x-2 mt-4">
            <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" @click="onSave">
                Mentés
            </button>
            <button class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" @click="onCancel">
                Mégse
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        isEditing: Boolean,
        carData: Object,
        carId: [String, Number]
    },
    data() {
        return {
            localData: {}
        };
    },
    watch: {
        carData: {
            immediate: true,
            handler(val) {
                if (val) {
                    this.localData = JSON.parse(JSON.stringify(val));
                }
            }
        }
    },
    methods: {
        onSave() {
            this.$emit('save', this.carId, this.localData);
        },
        onCancel() {
            this.$emit('cancel');
        }
    }
};
</script>