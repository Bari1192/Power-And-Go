<template>
  <div v-if="isEditing" class="bg-lime-400 max-w-fit border-4 border-lime-800/75 rounded-lg mt-2 p-4">

    <FormKit type="form" id="modifyFleetCar" :form-class="submitted ? 'hide' : 'show'" submit-label="Küldés"
      @submit="submitHandler" :actions="false" #default="{ value }" :validation="'required'" :validation-messages="{
        required: 'Kérjük minden adatot töltsön ki!'
      }">
      <div class="flex justify-center align-middle mb-2">
        <h5
          class="mb-2 text-2xl font-bold text-lime-50 bg-amber-500/85 rounded-xl p-3 tracking-wider border-b-4 border-spacing-8 border-lime-100 w-fit">
          Adatok módosítása</h5>
      </div>
      <div class="flex flex-col space-y-2">
        <FormKit name="manufacturer" type="text" label="Gyártó:" v-model="carData.manufacturer"
          :validation="'required|alpha|length:2,30'" :validation-messages="{
            alpha: 'Kizárólag betűket tartalmazhat!',
            length: 'A gyártó neve 2 és 30 karakter között lehet!',
            required: 'Kötelező kitölteni!'
          }" label-class="mt-3 pl-1 font-semibold text-lime-900 "
          input-class="appearance-none w-full border p-2 rounded-md text-green-800/50 font-semibold border focus:outline-none focus:border-lime-500 focus:border-2" />

      </div>
      <div class="flex flex-col mt-4">
        <FormKit name="carmodel" type="text" label="Modell:" v-model=localData.carmodel
          :validation="'required|string|length:2,30'" :validation-messages="{
            alpha: 'Kizárólag betűket tartalmazhat!',
            length: 'A model neve 2 és 30 karakter között lehet!',
            required: 'Kötelező kitölteni!'
          }" label-class="mt-3 pl-1 font-semibold text-lime-900 "
          input-class="appearance-none w-full  border p-2 rounded-md text-green-800/50 font-semibold border focus:outline-none focus:border-lime-500 focus:border-2" />
      </div>

      <div class="flex flex-col mt-4">
        <FormKit name="motorpower" type="text" label="Teljesítmény:" v-model=localData.motor_power
          :validation="'required|number|max:500|min:18'" :validation-messages="{
            alpha: 'Kizárólag számokat tartalmazhat!',
            min: '18 kw-nál nem lehet kevesebb!',
            max: '500 kw-nál nem lehet nagyobb!',
            required: 'Kötelező kitölteni!'
          }" label-class="mt-3 pl-1 font-semibold text-lime-900 "
          input-class="appearance-none w-full  border p-2 rounded-md text-green-800/50 font-semibold border focus:outline-none focus:border-lime-500 focus:border-2" />
      </div>

      <div class="flex flex-col mt-4">
        <FormKit name="topspeed" type="text" label="Végsebesség:" v-model=localData.top_speed
          :validation="'required|number|max:300|min:130'" :validation-messages="{
            alpha: 'Kizárólag számokat tartalmazhat!',
            min: '130 km/h-nál nem lehet kevesebb!',
            max: '300 km/h-nál nem lehet több!',
            required: 'Kötelező kitölteni!'
          }" label-class="mt-3 pl-1 font-semibold text-lime-900 "
          input-class="appearance-none w-full border p-2 rounded-md text-green-800/50 font-semibold border focus:outline-none focus:border-lime-500 focus:border-2" />
      </div>

      <div class="flex flex-col mt-4">
        <FormKit name="drivingrange" type="text" label="Hatótáv:" v-model=localData.driving_range
          :validation="'required|number|max:1000 |min:125'" :validation-messages="{
            alpha: 'Kizárólag számokat tartalmazhat!',
            max: '1000 km-nél nem lehet (még) magasabb!',
            min: '125 km-nél nem lehet kevesebb!',
            required: 'Kötelező kitölteni!'
          }" label-class="mt-3 pl-1 font-semibold text-lime-900 "
          input-class="appearance-none w-full border p-2 rounded-md text-green-800/50 font-semibold border focus:outline-none focus:border-lime-500 focus:border-2" />
      </div>

      <div class="flex flex-col mt-4">
        <FormKit name="tiresize" type="text" label="Gumiméret:" v-model=localData.tire_size
          :validation="'required|matches:/^\d{3}\|\d{2}-R\d{2}$/'" :validation-messages="{
            required: 'A gumiabroncs méret megadása kötelező!',
            matches: 'A gumiabroncs méret formátuma helytelen! Pl: 205|55-R16'
          }" label-class="mt-3 pl-1 font-semibold text-lime-900 "
          input-class="border p-2 w-full rounded-md text-green-800/50 font-semibold border focus:outline-none focus:border-lime-500 focus:border-2" />
      </div>
    </FormKit>
    <div class="flex justify-between items-center align-middle space-x-2 mt-8 mb-4">
      <FormKit type="submit">
        <BaseAcceptButton :buttonText="'Megerősítés'" @click="onSave" />
      </FormKit>
      <FormKit type="submit">
        <BaseDeclineButton :buttonText="'Visszavonás'" @click="onCancel" />
      </FormKit>
    </div>

  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import BaseAcceptButton from '@layout/BaseAcceptButton.vue'
import BaseDeclineButton from '@layout/BaseDeclineButton.vue'

const localData = ref({});
const submitted = ref(false);

// mivel visszavárjuk a gyermek komponenstől a választ:
const emit = defineEmits(['save', 'cancel']);

const props = defineProps({
  isEditing: {
    type: Boolean,
    required: true
  },
  carData: {
    type: Object,
    required: true
  },
  carId: {
    type: Number,
    required: true
  }
});

watch(() => props.carData, (newVal) => {
  if (newVal) {
    localData.value = JSON.parse(JSON.stringify(newVal));
  }
},
  { immediate: true }
);

const onSave = () => {
  emit('save', props.carId, localData.value);
};
const onCancel = () => {
  emit('cancel');
};
const submitHandler = (formData) => {
  submitted.value = true;
  emit('save', props.carId, formData);
  
  // 3 másodperc után visszaállítjuk a submitted értékét
  setTimeout(() => {
    submitted.value = false;
  }, 3000);
};
</script>

<style>
.formkit-message[data-message-type="validation"] {
  color: red;
  background-color: rgb(253, 253, 59);
  max-width: fit-content;
  letter-spacing: 1px;
  padding: 0 5px;
  border-radius: 5px;
  margin-top: 5px;
  font-style: italic;
}
</style>