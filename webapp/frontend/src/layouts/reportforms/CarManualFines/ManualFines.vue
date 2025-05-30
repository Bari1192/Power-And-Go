<template>
  <div class="container mx-auto px-4 py-8">
    <!-- Elválasztó vonal -->
    <div class="w-full h-[2px] bg-gradient-to-r from-transparent via-emerald-500/50 to-transparent my-8 rounded-full">
    </div>

    <div class="bg-slate-900 rounded-2xl border border-emerald-500/30 shadow-2xl shadow-emerald-500/10 p-8">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Penalty típus választó gombok -->
        <div class="flex flex-col justify-around align-middle h-full sticky top-8 space-y-4">
          <h3 class="text-xl font-bold text-emerald-400 mb-4">Büntetési típusok</h3>
          <button v-for="(penalty, index) in penalties" :key="penalty.value" @click="selectPenalty(penalty.value)"
            :class="[
              'px-6 py-4 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105',
              selectedType === penalty.value
                ? getPenaltyButtonStyle(penalty.value)
                : buttonColors[index]
                  ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-500/30'
                  : 'bg-slate-800 text-slate-400 hover:bg-slate-700 border border-slate-600/30'
            ]">
            <i :class="getPenaltyIcon(penalty.value) + ' pr-2'"></i>
            {{ penalty.label }}
          </button>
        </div>

        <!-- Form terület -->
        <div class="lg:col-span-2">
          <div v-if="selectedType" class="bg-slate-800 border border-emerald-500/20 rounded-xl p-6 shadow-inner">
            <h3 class="text-2xl font-bold mb-6" :class="getPenaltyTitleColor(selectedType)">
              {{ getPenaltyTitle }}
            </h3>

            <form @submit.prevent="handleSubmit" class="space-y-6">
              <div class="space-y-6">
                <!-- Büntetés tétel választó -->
                <div class="form-group">
                  <FormKit type="select" v-model="selectedItem" :options="currentPenaltyItems" label="Büntetés típusa:"
                    placeholder="Kérem válassza ki a tétel típusát!" />
                </div>

                <!-- Felhasználó választó -->
                <div class="form-group">
                  <FormKit type="select" v-model="selectedUser" :options="renterOptions"
                    label="Felhasználó kiválasztása:" placeholder="Felhasználó" />
                </div>

                <!-- Részletek -->
                <div class="form-group">
                  <FormKit type="textarea" name="details" v-model="details" label="Büntetés részletezése"
                    placeholder="Kérem írja le a büntetés kiszabásának indoklását:" :config="{
                      classes: {
                        input: 'min-h-[100px] max-h-[200px] resize-y'
                      }
                    }" />
                </div>

                <!-- Összeg megjelenítése -->
                <div v-if="selectedItem" class="bg-slate-900/50 rounded-lg p-4 border border-emerald-500/20">
                  <p class="text-sm text-slate-400 mb-1">Büntetés összege:</p>
                  <p class="text-2xl font-bold text-emerald-400">
                    {{ formatAmount(getCurrentItemAmount()) }} Ft
                  </p>
                </div>

                <!-- Submit gomb -->
                <div class="flex justify-center pt-4">
                  <button type="submit" :disabled="!isFormValid"
                    class="px-8 py-3 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700 transition-all duration-300 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 shadow-lg shadow-emerald-500/30">
                    <i class="fas fa-file-invoice pr-2"></i>
                    Számla kiállítása
                  </button>
                </div>
              </div>
            </form>
          </div>

          <!-- Üres állapot -->
          <div v-else @click="pulseButtonColors"
            class="flex items-center justify-center min-h-[400px] animate-left-right">
            <div
              class="bg-slate-800 border-2 border-emerald-500/30 rounded-xl p-6 cursor-pointer hover:border-emerald-500/50 transition-all duration-300 shadow-lg shadow-emerald-500/10">
              <p class="text-xl italic text-emerald-400 text-center">
                <i class="fas fa-hand-point-left pr-2"></i>
                Kérlek válassz egy büntetési típust a kitöltéshez!
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Alsó elválasztó -->
    <div class="w-full h-[2px] bg-gradient-to-r from-transparent via-emerald-500/50 to-transparent my-8 rounded-full">
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";

defineEmits(["submit"]);

const PENALTY_TYPES = {
  charging_violation: {
    label: "Adminisztrációs Díjak",
    items: [
      { id: "reg_fee", label: "Regisztrációs Díj", amount: 2490 },
      { id: "prebooking_mod", label: "Elő-foglalás Módosítási Díj", amount: 5000 },
      { id: "delivery_declared", label: "Házhozszállítási Díj (nyilatkozott)", amount: 2990 },
      { id: "delivery_undeclared", label: "Házhozszállítási Díj (nem nyilatkozott)", amount: 3990 },
      { id: "highway_ticket", label: "Autópálya matrica (VW/Skoda)", amount: 500 },
      { id: "Priority_Personauthorization", label: "Rendkívüli Ügyfél azonosítási Díj", amount: 2900 },
      { id: "insurance_limiter", label: "Önrész csökkentési Díj (Ft/nap)", amount: 1300 },
    ],
  },
  lemondas: {
    label: "Lemondások/Mulasztások",
    items: [
      { id: "cancel_reserve_VW_Skoda", label: "Lemondási Díj (VW/Škoda)", amount: 4990 },
      { id: "cancel_reserve_niro_vivaro", label: "Lemondási Díj (Kia/Opel)", amount: 8000 },
      { id: "omission_VW_Skoda", label: "Mulasztási Díj (VW/Škoda)", amount: 7990 },
      { id: "omission_niro_vivaro", label: "Mulasztási Díj (Kia/Opel)", amount: 13000 },
      { id: "no_photos", label: "Fényképkészítés elmulasztása", amount: 15000 },
    ],
  },
  comfort: {
    label: "Comfort - Késedelmek",
    items: [
      { id: "leeway_power_sub", label: "Power - Késedelmesátvevés VW/Škoda (Ft/perc)", amount: 149 },
      { id: "leeway_power_sub2", label: "Power - Késedelmesátvevés Kia/Opel (Ft/perc)", amount: 200 },
      { id: "leeway_power_plus_sub", label: "Power Plus VW/Škoda (Ft/perc)", amount: 149 },
      { id: "leeway_power_plus_sub2", label: "Power Plus Kia/Opel (Ft/perc)", amount: 200 },
    ],
  },
  kiszallas: {
    label: "Kiszállás/Szállítás",
    items: [
      { id: "onsite_fee", label: "Helyszíni ügyintézési díj", amount: 8000 },
      { id: "delivery_bp", label: "Kiszállási díj (Budapesten belül)", amount: 6000 },
      { id: "delivery_out", label: "Kiszállási díj (Budapesten kívül)", amount: 12000 },
      { id: "towtruck_service", label: "Vontatási költség", amount: 100000 },
      { id: "car_delivery", label: "Gépkocsi szállítási díj", amount: 5000 },
    ],
  },
  other_accessories: {
    label: "Tartozékok/Pótlások",
    items: [
      { id: "phone_holder", label: "Telefontartó pótlása", amount: 50000 },
      { id: "charging_cable", label: "Töltőkábel újraigénylés", amount: 1000 },
    ],
  },
  bad_close_method: {
    label: "Kötbérek/Eljárások",
    items: [
      { id: "smoking", label: "Dohányzás", amount: 100000 },
      { id: "cleaning", label: "Takarítás", amount: 30000 },
      { id: "pet", label: "Háziállatszállítás", amount: 30000 },
    ],
  },
};

const penalties = ref([
  { value: "charging_violation", label: "Adminisztrációs Díjak" },
  { value: "lemondas", label: "Lemondások/Mulasztások" },
  { value: "comfort", label: "Comfort - Késedelem" },
  { value: "kiszallas", label: "Kiszállási Szállítás" },
  { value: "bad_close_method", label: "Kötbér" },
  { value: "other_accessories", label: "Tartozékok" },
]);

const selectedType = ref(null);
const selectedItem = ref(null);
const selectedUser = ref(null);
const details = ref("");
const buttonColors = ref(penalties.value.map(() => false));

const pulseButtonColors = async () => {
  for (let i = 0; i < penalties.value.length; i++) {
    buttonColors.value[i] = true;
    await new Promise((resolve) => setTimeout(resolve, 1000));
    buttonColors.value[i] = false;
  }
};

const currentPenaltyItems = computed(() => {
  if (!selectedType.value) return [];
  return PENALTY_TYPES[selectedType.value]?.items || [];
});

// Example implementation for getPenaltyIcon
const getPenaltyIcon = (type) => {
  const iconMap = {
    "charging_violation": "fas fa-bolt",
    "lemondas": "fas fa-calendar-times",
    "comfort": "fas fa-clock",
    "kiszallas": "fas fa-truck",
    "bad_close_method": "fas fa-ban",
    "other_accessories": "fas fa-cogs"
  };
  return iconMap[type] || "fas fa-file-alt"; // Return default icon if type is unknown
};

const isFormValid = computed(() => {
  return selectedType.value && selectedItem.value && selectedUser.value && details.value.length > 0;
});

const formatAmount = (amount) => {
  return new Intl.NumberFormat("hu-HU").format(amount || 0);
};

const getCurrentItemAmount = computed(() => {
  if (!selectedType.value || !selectedItem.value) return 0;
  const selected = PENALTY_TYPES[selectedType.value]?.items.find((el) => el.id === selectedItem.value);
  return selected?.amount || 0;
});

const selectPenalty = (type) => {
  selectedType.value = type;
  selectedItem.value = null;
  selectedUser.value = null;
  details.value = "";
};

const handleSubmit = () => {
  const selectedPenalty = PENALTY_TYPES[selectedType.value]?.items.find(
    (item) => item.id === selectedItem.value
  );

  emit("submit", {
    ...selectedPenalty,
    description: details.value,
    timestamp: new Date().toISOString(),
  });

  // Reset form on submit
  selectedType.value = null;
  selectedItem.value = null;
  selectedUser.value = null;
  details.value = "";
};

const getPenaltyButtonStyle = (type) => {
  return selectedType.value === type
    ? "bg-emerald-600 text-white shadow-lg shadow-emerald-500/30"
    : "bg-slate-800 text-slate-400 hover:bg-slate-700 border border-slate-600/30";
};


</script>
<style>
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
  animation: leftRight 8s ease-in-out infinite;
}

/* FormKit custom styling */
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
  color: #e2e8f0 !important;
  border: 1px solid #10b98140 !important;
  border-radius: 0.5rem !important;
  padding: 0.75rem 1rem !important;
  font-weight: 500 !important;
  transition: all 0.3s !important;
}

.formkit-input:focus {
  background-color: #0f172a !important;
  border-color: #10b981 !important;
  box-shadow: 0 0 0 3px #10b98120 !important;
}

.formkit-input::placeholder {
  color: #64748b !important;
}

.formkit-option {
  background-color: #1e293b !important;
  color: #e2e8f0 !important;
}
</style>
