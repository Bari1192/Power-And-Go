<template>
  <nav class="relative backdrop-blur-sm border-b border-emerald-800/30 text-bold md:text-xl z-50"
    style="font-family: 'Nunito', 'Arial';">
    <!-- Háttér effektek -->
    <div class="absolute inset-0">
      <div class="absolute w-full h-full bg-slate-900/95"></div>
      <div class="absolute right-0 w-1/3 h-full bg-emerald-600/5 blur-3xl"></div>
      <div class="absolute -left-1/4 w-1/2 h-full bg-emerald-400/5 blur-3xl"></div>
    </div>

    <div class="relative w-full mx-auto px-4">
      <div class="flex justify-between items-center h-24">
        <!-- Logo -->
        <RouterLink to="/" class="flex items-center" active-class="font-bold text-lime-500">
          <img src="../../assets/img/Models/eup.png" alt="Logo" class="h-24 w-auto" />
          <p class="text-emerald-500 md:text-3xl font-extrabold w-fit ">Power And Go</p>
          <div class="fixed left-0 w-1/12 top-1/2 h-1/6 bg-emerald-400 blur-3xl"></div>

        </RouterLink>

        <!-- Hamburger mobil nézeten -->
        <button
          class="block lg:hidden bg-emerald-600/80 hover:bg-emerald-500 p-2 rounded-lg transition-colors duration-200"
          @click="toggleMenu">
          <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M1 1h15M1 7h15M1 13h15" />
          </svg>
        </button>


        <!-- Asztali menü -->
        <div class="hidden lg:flex lg:items-center lg:space-x-4">
          <!-- Regisztráció Dropdown -->
          <div class="relative" @click.stop>
            <button @click="toggleRegistrationMenu" :class="isRegistrationMenuOpen ? 'bg-emerald-600/20' : 'bg-inherit'"
              class="px-4 py-2 rounded-lg transition-all duration-200 hover:bg-emerald-600/20 text-slate-100 flex items-center">
              <span class="font-medium lg:text-xl mr-2">Regisztráció</span>
              <i class="fa-solid fa-chevron-down text-xs text-slate-100"
                :class="{ 'transform rotate-180': isRegistrationMenuOpen }"></i>
            </button>

          </div>
          <div class="relative" @click.stop>

            <button @click="toggleMailSystemMenu" :class="isMailSystemMenuOpen ? 'bg-emerald-600/20' : 'bg-inherit'"
              class="px-4 py-2 rounded-lg transition-all duration-200 hover:bg-emerald-600/20 text-slate-100 flex items-center">
              <span class="font-medium lg:text-xl mr-2">Rendszer értesítések</span>
              <i class="fa-solid fa-chevron-down text-xs text-slate-100"
                :class="{ 'transform rotate-180': isMailSystemMenuOpen }"></i>
            </button>

            <div v-if="isMailSystemMenuOpen" class="fixed mt-4 mx-auto w-56 bg-slate-600/95 backdrop-blur-sm rounded-xl border border-emerald-500/20 shadow-lg 
              overflow-hidden" style="transform: translateX(0); top: 4rem;">
              <div class="relative">
                <RouterLink to="/rents/renthistory" @click="isMailSystemMenuOpen = false"
                  class="block px-4 py-3 hover:bg-emerald-800/60 transition-colors duration-200">
                  <span class="font-medium text-slate-100">Összes értesítő</span>
                </RouterLink>
              </div>
            </div>
          </div>

          <div class="hidden lg:flex lg:items-center lg:space-x-4">

            <div class="relative" @click.stop>
              <button @click="toggleCostumerSupportMenu"
                class="px-4 py-2 rounded-lg transition-all duration-200 hover:bg-emerald-600/20 flex items-center">
                <span class="font-medium lg:text-xl text-slate-100 hover:text-emerald-400 mr-2">Ügyfélszolgálat</span>
                <i class="fa-solid fa-chevron-down text-xs text-slate-100"
                  :class="{ 'transform rotate-180': isCostumerSupportMenuOpen }"></i>
              </button>

              <div v-if="isCostumerSupportMenuOpen"
                class="fixed mt-2 w-48 bg-slate-800/95 backdrop-blur-sm rounded-xl border border-emerald-500/20 shadow-lg overflow-hidden"
                style="transform: translateX(0); top: 4rem;">
                <div class="relative">
                  <RouterLink to="/admins" @click="isCostumerSupportMenuOpen = false"
                    class="block px-4 py-3 hover:bg-emerald-600/20 transition-colors duration-200"
                    :class="getActiveClass('/')">
                    <span class="font-medium text-slate-100">Flottakezelők</span>
                  </RouterLink>
                  <RouterLink to="/admins2" @click="isCostumerSupportMenuOpen = false"
                    class="block px-4 py-3 hover:bg-emerald-600/20 transition-colors duration-200"
                    :class="getActiveClass('/admin3')">
                    <span class="font-medium text-slate-100">Call-centeresek</span>
                  </RouterLink>
                  <RouterLink to="/rents/renthistory" @click="isCostumerSupportMenuOpen = false"
                    class="block px-4 py-3 hover:bg-emerald-600/20 transition-colors duration-200"
                    :class="getActiveClass('/admins4')">
                    <span class="font-medium text-slate-100">Supervisor Panel</span>
                  </RouterLink>
                </div>
              </div>
            </div>
          </div>


          <div class="relative" @click.stop>
            <button @click="toggleBillsMenu"
              class="px-4 py-2 rounded-lg transition-all duration-200 hover:bg-emerald-600/20 flex items-center">
              <span class="font-medium lg:text-xl text-slate-100 hover:text-emerald-400 mr-2">Kiállított
                Számlák</span>
              <i class="fa-solid fa-chevron-down text-xs text-slate-100"
                :class="{ 'transform rotate-180': isBillsMenuOpen }"></i>
            </button>

            <div v-if="isBillsMenuOpen"
              class="fixed mt-2 w-48 bg-slate-800/95 backdrop-blur-sm rounded-xl border border-emerald-500/20 shadow-lg overflow-hidden"
              style="transform: translateX(0); top: 4rem;">
              <div class="relative">
                <RouterLink to="/bills/AllBills" @click="isBillsMenuOpen = false"
                  class="block px-4 py-3 hover:bg-emerald-600/20 transition-colors duration-200"
                  :class="getActiveClass('/bills/AllBills')">
                  <span class="font-medium text-slate-100">Összes Számla</span>
                </RouterLink>
                <RouterLink to="/bills/fines" @click="isBillsMenuOpen = false"
                  class="block px-4 py-3 hover:bg-emerald-600/20 transition-colors duration-200"
                  :class="getActiveClass('/bills/fines')">
                  <span class="font-medium text-slate-100">Bírságok</span>
                </RouterLink>
                <RouterLink to="/rents/renthistory" @click="isBillsMenuOpen = false"
                  class="block px-4 py-3 hover:bg-emerald-600/20 transition-colors duration-200"
                  :class="getActiveClass('/rents/renthistory')">
                  <span class="font-medium text-slate-100">Lezárt Bérlések</span>
                </RouterLink>
              </div>
            </div>
          </div>

          <!-- Autók Dropdown -->
          <div class="relative" @click.stop>
            <button @click="toggleCarsMenu"
              class="px-4 py-2 rounded-lg transition-all duration-200 hover:bg-emerald-600/20 flex items-center">
              <span class="font-medium text-slate-100 hover:text-emerald-400 mr-2">Jármű Kezelések </span>
              <i class="fa-solid fa-chevron-down text-xs text-slate-100"
                :class="{ 'transform rotate-180': isCarsMenuOpen }"></i>
            </button>

            <div v-if="isCarsMenuOpen"
              class="fixed mt-2 w-48 bg-slate-800/95 backdrop-blur-sm rounded-xl border border-emerald-500/20 shadow-lg overflow-hidden"
              style="transform: translateX(0); top: 4rem;">
              <div class="relative">
                <RouterLink to="/cars" @click="isCarsMenuOpen = false"
                  class="block px-4 py-3 hover:bg-emerald-600/20 transition-colors duration-200"
                  :class="getActiveClass('/cars')">
                  <span class="font-medium text-slate-100">Gépjármű nyilvántartó</span>
                </RouterLink>
                <RouterLink to="/fleets/fleetIndex" @click="isCarsMenuOpen = false"
                  class="block px-4 py-3 hover:bg-emerald-600/20 transition-colors duration-200"
                  :class="getActiveClass('/fleets/fleetIndex')">
                  <span class="font-medium text-slate-100">Flottakezelés</span>
                </RouterLink>
                <RouterLink to="/orders" @click="isCarsMenuOpen = false"
                  class="block px-4 py-3 hover:bg-emerald-600/20 transition-colors duration-200"
                  :class="getActiveClass('/orders')">
                  <span class="font-medium text-slate-100">Előrendelések</span>
                </RouterLink>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Mobil menü -->
    <div v-if="menuOpen"
      class="lg:hidden fixed top-24 left-0 right-0 bg-slate-900/95 border-t border-emerald-500/30 z-50">
      <div class="w-full mx-auto px-4 py-4 space-y-4">
        <!-- Számlák Szekció -->
        <div class="space-y-2">
          <div class="px-4 py-2 font-medium text-slate-100 bg-emerald-700/30 w-fit rounded-lg">Kiállított Számlák</div>
          <div class="space-y-1 pl-2">
            <RouterLink to="/bills/AllBills" @click="menuOpen = false"
              class="block px-4 py-2 rounded-lg hover:bg-emerald-600/20 transition-colors duration-200">
              <span class="font-medium text-slate-100">Összes Számla</span>
            </RouterLink>
            <RouterLink to="/bills/fines" @click="menuOpen = false"
              class="block px-4 py-2 rounded-lg hover:bg-emerald-600/20 transition-colors duration-200">
              <span class="font-medium text-slate-100">Bírságok</span>
            </RouterLink>
            <RouterLink to="/rents/renthistory" @click="menuOpen = false"
              class="block px-4 py-2 rounded-lg hover:bg-emerald-600/20 transition-colors duration-200">
              <span class="font-medium text-slate-100">Lezárt Bérlések</span>
            </RouterLink>
          </div>
        </div>

        <!-- Autók Szekció -->
        <div class="space-y-2">
          <div class="px-4 py-2 font-medium text-slate-100 bg-emerald-700/30 w-fit rounded-lg">Autók</div>
          <div class="space-y-1 pl-2">
            <RouterLink to="/cars" @click="menuOpen = false"
              class="block px-4 py-2 rounded-lg hover:bg-emerald-600/20 transition-colors duration-200">
              <span class="font-medium text-slate-100">Jármű lekérdezés</span>
            </RouterLink>
            <RouterLink to="/fleets/fleetIndex" @click="menuOpen = false"
              class="block px-4 py-2 rounded-lg hover:bg-emerald-600/20 transition-colors duration-200">
              <span class="font-medium text-slate-100">Flottakezelés</span>
            </RouterLink>
            <RouterLink to="/orders" @click="menuOpen = false"
              class="block px-4 py-2 rounded-lg hover:bg-emerald-600/20 transition-colors duration-200">
              <span class="font-medium text-slate-100">Előrendelések</span>
            </RouterLink>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@stores/AuthenticationStore';

const authStore = useAuthStore();
const menuOpen = ref(false);
const isBillsMenuOpen = ref(false);
const isCarsMenuOpen = ref(false);
const isMailSystemMenuOpen = ref(false);
const isRegistrationMenuOpen = ref(false);
const isCostumerSupportMenuOpen = ref(false);
const router = useRouter();

function toggleMenu() {
  menuOpen.value = !menuOpen.value;
}

function toggleCostumerSupportMenu() {
  isCostumerSupportMenuOpen.value = !isCostumerSupportMenuOpen.value;
  if (isCostumerSupportMenuOpen.value) {
    isCarsMenuOpen.value = false;
    isMailSystemMenuOpen.value = false;
    isBillsMenuOpen.value = false;
    isRegistrationMenuOpen.value = false;
  }
}
function toggleBillsMenu() {
  isBillsMenuOpen.value = !isBillsMenuOpen.value;
  if (isBillsMenuOpen.value) {
    isCarsMenuOpen.value = false;
    isMailSystemMenuOpen.value = false;
    isCostumerSupportMenuOpen.value = false;
    isRegistrationMenuOpen.value = false;
  }
}
function toggleMailSystemMenu() {
  isMailSystemMenuOpen.value = !isMailSystemMenuOpen.value;
  if (isMailSystemMenuOpen.value) {
    isCarsMenuOpen.value = false;
    isBillsMenuOpen.value = false;
    isCostumerSupportMenuOpen.value = false;
    isRegistrationMenuOpen.value = false;
  }
}
function toggleRegistrationMenu() {
  isRegistrationMenuOpen.value = !isRegistrationMenuOpen.value;
  if (isRegistrationMenuOpen.value) {
    isCarsMenuOpen.value = false;
    isBillsMenuOpen.value = false;
    isCostumerSupportMenuOpen.value = false;
    isMailSystemMenuOpen.value = false;
  }
}

function toggleCarsMenu() {
  isCarsMenuOpen.value = !isCarsMenuOpen.value;
  if (isCarsMenuOpen.value) {
    isBillsMenuOpen.value = false;
    isMailSystemMenuOpen.value = false;
    isCostumerSupportMenuOpen.value = false;
    isRegistrationMenuOpen.value = false;
  }
}

function getActiveClass(path) {
  return {
    'bg-emerald-600/30 text-emerald-400': router.currentRoute.value.path === path,
  };
}

// Kattintás figyelése a dokumentumon
function handleClickOutside(event) {
  if (!event.target.closest('.relative')) {
    isBillsMenuOpen.value = false;
    isCarsMenuOpen.value = false;
    isCostumerSupportMenuOpen.value = false;
    isMailSystemMenuOpen.value = false;
  }
}
onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>
