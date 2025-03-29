<template>
  <div v-if="isEditing" class="bg-lime-400 border-4  border-lime-800/75 rounded-lg mt-2 p-4">
    <h5 class="mb-2 text-2xl font-bold text-lime-900 tracking-wide">Adatmódosítás:</h5>
    <div class="flex flex-col space-y-2">
      <p class="mt-3 font-semibold text-lime-800 ">Gyártó:</p>
      <input v-model="localData.manufacturer" type="text" class="border p-2 rounded" placeholder="Gyártó" />

      <p class="mt-3 font-semibold text-lime-900 tracking-wide">Modell:</p>
      <input v-model="localData.carmodel" type="text" class="border p-2 rounded" placeholder="Modell" />

      <p class="mt-3 font-semibold text-lime-900 tracking-wide">Teljesítmény:</p>
      <input v-model="localData.motor_power" type="number" class="border p-2 rounded" placeholder="Teljesítmény (kW)" />

      <p class="mt-3 font-semibold text-lime-900 tracking-wide">Végsebesség:</p>
      <input v-model="localData.top_speed" type="number" class="border p-2 rounded" placeholder="Végsebesség (km/h)" />

      <p class="mt-3 font-semibold text-lime-900 tracking-wide">Hatótáv:</p>
      <input v-model="localData.driving_range" type="number" class="border p-2 rounded" placeholder="Hatótáv (km)" />

      <p class="mt-3 font-semibold text-lime-900 tracking-wide">Gumiméret:</p>
      <input v-model="localData.tire_size" type="text" class="border p-2 rounded" placeholder="pl: 165|65-R15" />
    </div>
    <div class="flex justify-end space-x-2 mt-4">
      <button class="bg-green-500 hover:bg-green-700 text-lime-900 tracking-wide font-bold py-2 px-4 rounded" @click="onSave">
        Mentés
      </button>
      <button class="bg-gray-500 hover:bg-gray-700 text-lime-900 tracking-wide font-bold py-2 px-4 rounded" @click="onCancel">
        Mégse
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'EditFleetCar',
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