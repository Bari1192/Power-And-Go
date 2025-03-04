<template>
  <div class="w-4/5 sm:w-full mt-10 mx-auto border-b-8 border-indigo-800 rounded-xl opacity-60"></div>
  <div class="w-4/5 sm:w-full my-8 mx-auto">
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 space-x-8 min-h-[500px]">
      <div class="flex flex-col justify-around align-middle h-full sticky top-0">
        <button v-for="(penalty, index) in penalties" :key="penalty.value" @click="selectPenalty(penalty.value)" :class="[
          'text-lg bg-slate-500 rounded-full px-4 py-3 border-4 border-sky-600 hover:bg-slate-700 text-sky-100 font-semibold transition-all duration-300 ease-in-out',
          {
            'text-emerald-700 bg-slate-100 shadow-md transition-all duration-200 ease-in-out': buttonColors[index],
            'bg-slate-500 text-white': !buttonColors[index]
          },
          selectedType === penalty.value ? 'bg-indigo-700' : ''
        ]">
          {{ penalty.label }}
        </button>
      </div>

      <div class="md:col-span-2 max-h-[500px]">
        <div v-if="selectedType">
          <BaseCard :title="getPenaltyTitle">
            <form @submit.prevent="handleSubmit" class="space-y-6">
              <div class="flex flex-col space-y-4">
                <!-- Büntetés tétel választó -->
                <FormKit type="select" v-model="selectedItem" :options="currentPenaltyItems" label="Büntetés típusa:"
                  placeholder="Kérem válassza ki a tétel típusát!"
                  label-class="max-w-fit text-sky-400 text-xl font-semibold"
                  input-class="max-w-fit text-sky-800 font-semibold border border-gray-200 rounded py-2 mt-3 px-1" />

                <!-- Felhasználó -->
                <FormKit type="select" v-model="selectedUser" :options="renterOptions" label="Felhasználó kiválasztása:"
                  placeholder="Felhasználó" label-class="flex inline-block text-sky-400 text-xl font-semibold mb-2"
                  input-class="text-sky-800 font-semibold border border-gray-200 rounded py-2 px-1" />

                <!-- További komment részletek -->
                <div class="flex flex-col space-y-2">
                  <FormKit type="textarea" name="details" v-model="details" label="Büntetés részletezése"
                    placeholder="Kérem írja le a büntetés kiszabásának indoklását:"
                    label-class="text-sky-400 text-xl mt-4 font-semibold mb-2"
                    input-class="mt-2 max-h-28 min-h-16 w-full bg-gray-100 text-sky-800 font-semibold border border-gray-200 rounded-md py-3 px-4 focus:outline-none focus:bg-white focus:border-gray-500" />
                  <div v-if="selectedItem" class="text-white text-lg font-semibold">
                    Büntetés összege: {{ formatAmount(getCurrentItemAmount()) }} Ft
                  </div>
                </div>

                <button type="submit" :disabled="!isFormValid"
                  class="bg-lime-600 max-w-fit m-auto hover:bg-lime-700 text-white font-bold rounded-lg border-2 border-lime-700 py-2 px-4 transition-all duration-300 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed">
                  Számla kiállítása
                </button>
              </div>
            </form>
          </BaseCard>
        </div>
        <div v-else @click="pulseButtonColors"
          class="flex items-center text-xl italic justify-center text-white text-center min-h-full animate-left-right">
          <p class="p-2 bg-sky-500 rounded-xl border-2 border-sky-300 cursor-pointer">
            Kérlek válassz egy büntetési típust a kitöltéshez!
          </p>
        </div>
      </div>
    </div>
  </div>
  <div class="w-4/5 sm:w-full pt-14 mb-10 mx-auto border-b-8 border-indigo-800 rounded-xl opacity-60"></div>
</template>

<script setup>
import { ref, computed } from 'vue';
import BaseCard from '@layouts/BaseCard.vue';

const props = defineProps({
  carRentHistory: {
    type: Object,
    required: true
  }
});

const PENALTY_TYPES = {
  charging_violation: {
    label: 'Adminisztráci / Szolgáltatások',
    items: [
      { id: 'reg_fee', label: 'Regisztrációs Díj', amount: 2490 }, // Ez majd hozzáadódik az account_balanchoz backenden (levezethető)
      { id: 'prebooking_mod', label: 'Elő-foglalás Módosítási Díj', amount: 5000 },
      { id: 'delivery_declared', label: 'Házhozszállítási Díj (nyilatkozott)', amount: 2990 },
      { id: 'delivery_undeclared', label: 'Házhozszállítási Díj (nem nyilatkozott)', amount: 3990 },
      { id: 'highway_ticket', label: 'Autópályamatrica Díj (VW,Skoda)', amount: 500 },
      { id: 'Priority_Person authorization', label: 'Rendkívüli Ügyfélazonosítási Díj', amount: 2900 },
      { id: 'insurance_limiter', label: 'Önrészcsökkentési Díj (Ft / nap)', amount: 1300 },
    ]
  },
  lemondas: {
    label: 'Lemondások / Mulasztások',
    items: [
      { id: 'cancel_reserve_VW_Skoda', label: 'Lemondási Díj (VW / Škoda)', amount: 4990 },
      { id: 'cancel_reserve_niro_vivaro', label: 'Lemondási Díj (Kia / Opel)', amount: 8000 },
      { id: 'omission_VW_Skoda', label: 'Mulasztási Díj (VW / Škoda)', amount: 7990 },
      { id: 'omission_niro_vivaro', label: 'Mulasztási Díj (Kia / Opel)', amount: 13000 },
      { id: 'no_photos', label: 'Fénykép készítés/továbbítás elmulasztása', amount: 15000 },

    ]
  },
  comfort: {
    label: 'Comfort - Késedelmek',
    items: [
      { id: 'leeway_power_sub', label: 'Power - Késedelmes átvevés - VW,Škoda (Ft / perc)', amount: 149 },
      { id: 'leeway_power_sub', label: 'Power - Késedelmes átvevés - Kia / Opel (Ft / perc)', amount: 200 },
      { id: 'leeway_power_plus_sub', label: 'Power-Plus - Késedelmes átvevés - VW,Škoda (Ft / perc)', amount: 149 },
      { id: 'leeway_power_plus_sub', label: 'Power-Plus - Késedelmes átvevés - Kia / Opel (Ft / perc)', amount: 200 },
      { id: 'leeway_power_premium_sub', label: 'Power-Premium - Késedelmes átvevés - VW,Škoda (Ft / perc)', amount: 117 },
      { id: 'leeway_power_premium_sub', label: 'Power-Premium - Késedelmes átvevés - Kia / Opel (Ft / perc)', amount: 180 },
      { id: 'leeway_power_vip_sub', label: 'Power-VIP - Késedelmes átvevés - VW,Škoda (Ft / perc)', amount: 83 },
      { id: 'leeway_power_vip_sub', label: 'Power-VIP - Késedelmes átvevés - Kia / Opel (Ft / perc)', amount: 170 },
    ]
  },

  kiszallas: {
    label: 'Kiszállások / Szállítások',
    items: [
      { id: 'onsite_fee', label: 'Helyszíni Ügyintézési Díj', amount: 8000 },
      { id: 'delivery_bp', label: 'Kiszállási Díj (Budapesten belül)', amount: 6000 },
      { id: 'delivery_out', label: 'Kiszállási Díj (Budapesten kívül)', amount: 12000 },
      { id: 'towtruck_service', label: 'Vontatási / Trailer költség', amount: 100000 },
      { id: 'car_delivery', label: 'Személygépkocsi szállítása', amount: 5000 },
    ]
  },
  other_accessories: {
    label: 'Tartozékok / Pótlások',
    items: [
      { id: 'phone_holder', label: 'Telefontartó Pótlása', amount: 50000 },
      { id: 'modify_document', label: 'Módosítási Díj', amount: 5000 },
      { id: 'charging_cable', label: 'Töltőkábel igénylés', amount: 1000 },
      { id: 'registration', label: 'Forgalmi Engedély pótlása', amount: 2000 }
    ]
  },
  bad_close_method: {
    label: 'Kötbérek / Eljárások',
    items: [
      { id: 'smoking', label: 'Dohányzás', amount: 100000 },
      { id: 'cleaning', label: 'Takarítási díj', amount: 30000 },
      { id: 'pet', label: 'Háziállat/élőállat szállítása', amount: 30000 },
      { id: 'bad_close', label: 'Nem megfelelő lezárás', amount: 7000 },
      { id: 'speed', label: 'Sebességkorlátozás túllépése', amount: 30000 },
      { id: 'damage_minor', label: 'Karosszéria-Károkozás (enyhébb)', amount: 20000 },
      { id: 'damage_major', label: 'Karosszéria-Károkozás (súlyos)', amount: 40000 }
    ]
  },
  felszolitas: {
    label: 'Felszólítások / Mulasztások',
    items: [
      { id: 'payment_notice', label: 'Fizetési Felszólítás', amount: 5000 },
      { id: 'insurance_delay', label: 'Biztosítási ügyintézés hátráltatása', amount: 25000 },
      { id: 'damage_report', label: 'Káreseményről való értesítés elmulasztása', amount: 50000 }
    ]
  }
};

const penalties = [
  { value: 'charging_violation', label: 'Adminisztrációs Díjak' },
  { value: 'kiszallas', label: 'Kiszállási / Szállítási Díjak' },
  { value: 'lemondas', label: 'Lemondások / Mulasztások' },
  { value: 'comfort', label: 'Comfort - Késedelmek' },
  { value: 'other_accessories', label: 'Tartozékok' },
  { value: 'bad_close_method', label: 'Kötbérek / Eljárások' },
  { value: 'felszolitas', label: 'Felszólítások / Mulasztások' },
];
const buttonColors = ref(penalties.map(() => false));

const pulseButtonColors = async () => {
  let activeButtons = new Array(penalties.length).fill(false);

  for (let i = 0; i < penalties.length; i++) {
    const buttonIndex = i % penalties.length;
    if (!activeButtons[buttonIndex]) {
      buttonColors.value[buttonIndex] = true;
      activeButtons[buttonIndex] = true;
    }

    const prevIndex = (buttonIndex - 1 + penalties.length) % penalties.length;
    if (activeButtons[prevIndex]) {
      buttonColors.value[prevIndex] = false;
      activeButtons[prevIndex] = false;
    }

    await new Promise(r => setTimeout(r, 650));
  }
  buttonColors.value[penalties.length - 1] = false;
};
const emit = defineEmits(['submit']);

const selectedType = ref(null);
const selectedItem = ref(null);
const selectedUser = ref(null);
const details = ref('');

const currentPenaltyItems = computed(() => {
  if (!selectedType.value) return [];
  return PENALTY_TYPES[selectedType.value].items.map(item => ({
    value: item.id,
    label: item.label
  }));
});

const renterOptions = computed(() => {
  if (props.carRentHistory?.renters) {
    return props.carRentHistory.renters.map(r => ({
      value: r.user,
      label: r.user
    }));
  }
  return [];
});

const getPenaltyTitle = computed(() => {
  if (!selectedType.value) return '';
  return PENALTY_TYPES[selectedType.value].label;
});

const isFormValid = computed(() => {
  return selectedType.value &&
    selectedItem.value &&
    selectedUser.value &&
    details.value.length > 0;
});
const formatAmount = (amount) => {
  return new Intl.NumberFormat('hu-HU').format(amount);
};

const getCurrentItemAmount = () => {
  if (!selectedType.value || !selectedItem.value) return 0;
  const item = PENALTY_TYPES[selectedType.value].items.find(
    item => item.id === selectedItem.value
  );
  return item ? item.amount : 0;
};

const selectPenalty = (value) => {
  selectedType.value = value;
  selectedItem.value = null;
  selectedUser.value = null;
  details.value = '';
};

const handleSubmit = () => {
  if (!isFormValid.value) return;

  const penaltyItem = PENALTY_TYPES[selectedType.value].items.find(
    item => item.id === selectedItem.value
  );

  emit('submit', {
    type: selectedType.value,
    itemId: selectedItem.value,
    userId: selectedUser.value,
    description: penaltyItem.label,
    amount: penaltyItem.amount,
    details: details.value,
    timestamp: new Date().toISOString()
  });

  // Reset form
  selectedType.value = null;
  selectedItem.value = null;
  selectedUser.value = null;
  details.value = '';
};
</script>

<style scoped>
@keyframes leftRight {
  0% {
    transform: translateX(-25px);
  }

  50% {
    transform: translateX(25px);
  }

  100% {
    transform: translateX(-25px);
  }
}

.animate-left-right {
  animation: leftRight 4s ease-in-out infinite;
}
</style>