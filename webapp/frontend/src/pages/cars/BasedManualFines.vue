<template>
  <div
    class="w-4/5 sm:w-full h-[3px] mx-auto bg-gradient-to-r from-transparent via-purple-500/50 to-transparent my-16 rounded-full">
  </div>
  <div class="bg-slate-100/5 border border-purple-500/40 rounded-xl mb-8 shadow-2xl shadow-pink-500/10 p-8">
    <h3 class="text-xl font-bold text-purple-50 mb-4 tracking-wide">Büntetési tétel típusai:</h3>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

      <div class="flex flex-col justify-between align-middle h-full sticky top-8 space-y-4">
        <button v-for="penalty in penalties" :key="penalty.value" @click="selectPenalty(penalty.value)" :class="[
          'px-6 py-4 rounded-lg font-semibold transition-color duration-200 transform text-start border border-slate-500/30',
          selectedType === penalty.value ?
            'bg-emerald-600/75 text-white shadow-md shadow-emerald-600/30'
            : 'bg-slate-700 text-slate-200/85 hover:bg-slate-600 hover:text-slate-300 '
        ]">
          <i :class="getPenaltyIcon(penalty.value) + ' pr-2'"></i>
          {{ penalty.label }}
        </button>
      </div>

      <div class="h-fit w-full mx-auto">
        <div v-if="selectedType">
          <BaseCard :title="getPenaltyTitle">
            <form @submit.prevent="handleSubmit">
              <div class="flex flex-col justify-between font-semibold">
                <FormKit type="select" v-model="selectedItem" :options="currentPenaltyItems" label="Büntetés típusa:"
                  placeholder="Kérem válassza ki a tétel típusát!" label-class="block text-lg font-semibold mb-2 w-fit"
                  input-class="w-full px-4 py-2 rounded-lg border bg-slate-700 text-slate-100" />


                <div v-if="selectedType === 'comfort'" class="w-full h-[2px] bg-slate-400/35 mb-2 rounded-3xl">
                </div>
                <div v-if="selectedType === 'comfort'"
                  class="inline-flex justify-between items-center gap-1 rounded-lg">
                  <span class="text-lg font-extrabold text-indigo-100 tracking-wider">Késési idő:</span>
                  <div class="inline-flex items-center ">
                    <FormKit type="text" name="minutes" v-model="minutesQuantity" min="0" max="999" :sections-schema="{
                      outer: { $el: null },
                      wrapper: { $el: null },
                    }"
                      input-class="w-16 h-10 bg-slate-300/20 border border-slate-500 rounded-md text-white text-center font-semibold focus:outline-none" />
                    <span class="text-lg font-semibold text-white/90 mx-2">perc</span>
                  </div>
                </div>
                <div v-if="selectedType === 'comfort'" class="w-full h-[2px] bg-slate-400/35 my-2 rounded-3xl">
                </div>

                <!-- Felhasználó -->
                <FormKit type="select" v-model="selectedUser" :options="renterOptions" label="Felhasználó kiválasztása:"
                  placeholder="Felhasználó" label-class="block text-lg font-semibold my-2 w-fit"
                  input-class="px-3 py-2 rounded-lg border bg-slate-700 font-semibold text-emerald-400 w-fit hover:cursor-pointer" />

                <!-- További komment részletek -->
                <div class="flex flex-col justify-between mt-2">
                  <FormKit type="textarea" name="details" v-model="details" label="Büntetés részletezése"
                    placeholder="Kérem írja le a büntetés kiszabásának indoklását:" label-class="text-lg text-slte-100"
                    input-class="w-full bg-slate-700 text-slate-100 mt-2 max-h-28 min-h-16 w-full border border-gray-200 rounded-lg p-2" />
                  <div v-if="selectedItem" class="text-md font-bold text-purple-200">
                    Büntetés összege: {{ formatStore.formatToOneThousandPrice(getCurrentItemAmount()) }} Ft
                  </div>
                </div>

                <div class="flex justify-center pt-4">
                  <button type="submit" :disabled="!isFormValid"
                    class="px-8 py-3 bg-emerald-700 text-white font-bold rounded-lg hover:bg-emerald-800 transition-color duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm shadow-emerald-900/30">
                    <i class="fas fa-file-invoice pr-2"></i>
                    Számla kiállítása
                  </button>
                </div>
              </div>
            </form>
          </BaseCard>
        </div>
        <div v-else class="flex items-center justify-center min-h-[400px] animate-left-right">
          <div class="bg-slate-800 border-2 border-emerald-500/30 rounded-xl p-6 shadow-lg shadow-emerald-500/10">
            <p class="text-lg italic text-emerald-50 text-center">
              <i class="fas fa-hand-point-left pr-2"></i>
              Kérlek válaszd ki a menüpontot a számla kiállításának megkezdéséhez!
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div
    class="w-4/5 sm:w-full h-[3px] mx-auto bg-gradient-to-r from-transparent via-purple-500/50 to-transparent my-16 rounded-full">
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import BaseCard from '@layouts/BaseCard.vue';
import { useCarStore } from "@stores/carStore";
import { useFormatStore } from '@stores/Services/FormatHelperService';
const carStore = useCarStore();
const formatStore = useFormatStore();


const minutesQuantity = ref(0);

const props = defineProps({
  carUserRentsHistory: {
    type: [Object, Array],
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
      { id: 'power_sub', label: 'Power | Késedelmes átvevés | VW, Škoda | (Ft/perc)', amount: 149 },
      { id: 'power_sub', label: 'Power | Késedelmes átvevés | Kia, Opel | (Ft/perc)', amount: 200 },
      { id: 'power_plus_sub', label: 'P. Plus | Késedelmes átvevés | VW, Škoda (Ft/perc)', amount: 149 },
      { id: 'power_plus_sub', label: 'P. Plus | Késedelmes átvevés | Kia, Opel (Ft / perc)', amount: 200 },
      { id: 'power_premium_sub', label: 'P. Premium | Késedelmes átvevés | VW, Škoda (Ft / perc)', amount: 117 },
      { id: 'power_premium_sub', label: 'P. Premium | Késedelmes átvevés | Kia, Opel (Ft / perc)', amount: 180 },
      { id: 'power_vip_sub', label: 'P. VIP | Késedelmes átvevés | VW, Škoda (Ft / perc)', amount: 83 },
      { id: 'power_vip_sub', label: 'P. VIP | Késedelmes átvevés | Kia, Opel (Ft / perc)', amount: 170 },
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

// Menü-opciók mellett lévő fontawesome ikonok dinamikusan.
const getPenaltyIcon = (type) => {
  const iconMap = {
    "charging_violation": "fa-solid fa-pen-to-square text-pink-400",
    "lemondas": "fas fa-calendar-times text-sky-300",
    "comfort": "fas fa-clock text-yellow-400",
    "kiszallas": "fas fa-truck text-orange-400",
    "bad_close_method": "fas fa-ban text-red-500",
    "other_accessories": "fas fa-cogs text-gray-100"
  };
  return iconMap[type] || "fas fa-file-alt text-indigo-400";
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
  if (props.carUserRentsHistory.renters.length > 0) {
    return props.carUserRentsHistory.renters.map(r => ({
      value: r.user,
      label: r.user,
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

const getCurrentItemAmount = () => {
  if (!selectedType.value || !selectedItem.value) return 0;
  const item = PENALTY_TYPES[selectedType.value].items.find(
    item => item.id === selectedItem.value
  );
  return item ? item.amount + ((minutesQuantity.value - 1) * item.amount) : 0;
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
.formkit-outer {
  margin-bottom: 1rem;
}

.formkit-label {
  color: #34d399 !important;
  font-size: 1.125rem !important;
  font-weight: 600 !important;
  margin-bottom: 0.5rem !important;
  display: block !important;
}

.formkit-input {
  background-color: #1e293b !important;
  color: #303030 !important;
  border: 1px solid #10b98140 !important;
  border-radius: 0.5rem !important;
  padding: 0.75rem 1rem !important;
  font-weight: 500 !important;
}

.formkit-input:focus {
  background-color: #0f172a !important;
  border-color: #10b981 !important;
  box-shadow: 0 0 0 3px #10b98120 !important;
}

.formkit-input::placeholder {
  color: #64748b !important;
  padding-left: .5rem;
}

.formkit-option {
  background-color: #1e293b !important;
  color: #4a0404 !important;
}
</style>