<!-- EditFleetCar.vue - Átformázott megjelenés -->
<template>
  <div v-if="isEditing" class="bg-slate-900 border-2 border-emerald-500/50 rounded-xl my-2 p-6 shadow-2xl">
    <FormKit type="form" id="modifyFleetCar" :form-class="submitted ? 'hide' : 'show'" submit-label="Mentés"
      @submit="submitHandler" :actions="false" :validation="'required'"
      :validation-messages="{ required: 'Kérjük minden adatot töltsön ki!' }">
      <!-- Fejléc -->
      <div class="flex flex-col justify-center items-center mx-auto mb-6">
        <span class="text-2xl font-bold text-slate-100 tracking-wide">
          {{ localData.manufacturer }} {{ localData.carmodel }}
        </span>
        <p class="text-2xl font-bold text-emerald-400 tracking-wide">
          adatainak módosítása
        </p>

      </div>

      <div class="border-b-2 border-sky-700/30 mb-6"></div>

      <!-- Form mezők - 2 oszlopos elrendezés -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-11/12 mx-auto">
        <!-- Gyártó -->
        <FormKit name="manufacturer" type="text" label="Gyártó" v-model="localData.manufacturer"
          :validation="'required|alpha|length:2,30'" :validation-messages="{
            alpha: 'Kizárólag betűket tartalmazhat!',
            length: 'A gyártó neve 2 és 30 karakter között lehet!',
            required: 'Kötelező kitölteni!'
          }" label-class="block text-md font-semibold text-slate-100 mb-2 tracking-wide"
          input-class="w-full bg-slate-800 border border-slate-600 rounded-lg px-4 py-2 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all" />

        <!-- Modell -->
        <FormKit name="carmodel" type="text" label="Modell" v-model="localData.carmodel"
          :validation="'required|string|length:2,30'" :validation-messages="{
            required: 'Kötelező kitölteni!',
            length: 'A modell neve 2 és 30 karakter között lehet!'
          }" label-class="block text-md font-semibold text-slate-100 mb-2 tracking-wide"
          input-class="w-full bg-slate-800 border border-slate-600 rounded-lg px-4 py-2 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all" />

        <!-- Teljesítmény -->
        <FormKit name="motorpower" type="number" label="Teljesítmény (kW)" v-model="localData.motor_power"
          :validation="'required|number|max:500|min:18'" :validation-messages="{
            number: 'Kizárólag számokat adhat meg!',
            min: 'Az érték legalább 18 kW lehet!',
            max: '500 kW-nál nem lehet magasabb!',
            required: 'Teljesítményt mindenképp adjon meg!'
          }" label-class="block text-md font-semibold text-slate-100 mb-2 tracking-wide"
          input-class="w-full bg-slate-800 border border-slate-600 rounded-lg px-4 py-2 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all" />

        <!-- Végsebesség -->
        <FormKit name="topspeed" type="number" label="Végsebesség (km/h)" v-model="localData.top_speed"
          :validation="'required|number|max:300|min:130'" :validation-messages="{
            number: 'Kizárólag számokat tartalmazhat!',
            min: '130 km/h-nál nem lehet kevesebb!',
            max: '300 km/h-nál nem lehet több!',
            required: 'Kötelező kitölteni!'
          }" label-class="block text-md font-semibold text-slate-100 mb-2 tracking-wide"
          input-class="w-full bg-slate-800 border border-slate-600 rounded-lg px-4 py-2 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all" />

        <!-- Hatótáv -->
        <FormKit name="drivingrange" type="number" label="Hatótáv (km)" v-model="localData.driving_range"
          :validation="'required|number|max:1000|min:125'" :validation-messages="{
            number: 'Kizárólag számokat tartalmazhat!',
            max: '1000 km-nél nem lehet (még) magasabb!',
            min: '125 km-nél nem lehet kevesebb!',
            required: 'Kötelező kitölteni!'
          }" label-class="block text-md font-semibold text-slate-100 mb-2 tracking-wide"
          input-class="w-full bg-slate-800 border border-slate-600 rounded-lg px-4 py-2 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all" />

        <!-- Gumiméret -->
        <FormKit name="tiresize" type="text" label="Gumiméret" v-model="localData.tire_size"
          :validation="'required|matches:/^\\d{3}\\|\\d{2}-R\\d{2}$/'" :validation-messages="{
            required: 'A gumiabroncs méret megadása kötelező!',
            matches: 'A gumiabroncs méret formátuma helytelen! Pl: 205|55-R16'
          }" label-class="block text-md font-semibold text-slate-100 mb-2 tracking-wide"
          input-class="w-full bg-slate-800 border border-slate-600 rounded-lg px-4 py-2 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all" />
      </div>
    </FormKit>

    <!-- Akciógombok -->
    <div class="flex justify-center items-center gap-6 mt-8 py-6 border-t-2 border-sky-700/30">
      <button @click="onCancel"
        class="px-6 py-2.5 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-lg transition-all duration-200 border border-slate-400">
        Mégsem
      </button>
      <button @click="submitHandler"
        class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg border border-teal-400 transition-all duration-200 shadow-lg shadow-emerald-700/25">
        Módosítások mentése
      </button>
    </div>
  </div>
  <div
    class="w-4/5 sm:w-full h-[3px] mx-auto bg-gradient-to-r from-transparent via-indigo-400/80 to-transparent my-4 rounded-full">
  </div>
</template>

<script setup>
import { ref, watch } from "vue";

const props = defineProps({
  isEditing: { type: Boolean, required: true },
  carData: { type: Object, required: true },
  carId: { type: Number, required: true },
});

const emit = defineEmits(["save", "cancel"]);
const localData = ref({ ...props.carData });
const submitted = ref(false);

watch(
  () => props.carData,
  (newVal) => {
    if (newVal) {
      localData.value = JSON.parse(JSON.stringify(newVal));
    }
  },
  { immediate: true }
);

const onSave = () => {
  emit("save", props.carId, localData.value);
};

const onCancel = () => {
  emit("cancel");
};

const submitHandler = () => {
  submitted.value = true;
  emit("save", props.carId, localData.value);
  setTimeout(() => {
    submitted.value = false;
  }, 3000);
};
</script>

<style scoped>
/* Egyedi FormKit hibaüzenet stílusok */
:deep(.formkit-message[data-message-type="validation"]) {
  color: #f87171;
  background-color: rgba(239, 68, 68, 0.1);
  border: 1px solid rgba(239, 68, 68, 0.3);
  padding: 0.25rem 0.75rem;
  border-radius: 0.375rem;
  margin-top: 0.5rem;
  font-size: 0.875rem;
}
</style>