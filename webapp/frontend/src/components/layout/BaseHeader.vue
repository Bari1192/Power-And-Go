<template>
  <nav class="text-white bg-sky-950 border-b-4 border-sky-800">
    <div class="flex justify-between items-center p-3 border-b-2 flex-wrap dark:border-none">
      <RouterLink to="/" class="flex items-center mx-4" active-class="font-bold text-lime-500">
        <img src="../../assets/img/BaseEmail/logo_small.png" class="h-24 w-auto" alt="PowerAndGo Logo" />
        <span class="self-center text-2xl font-semibold whitespace-nowrap hover:text-lime-400">PowerAndGo</span>
      </RouterLink>

      <!-- Hamburger mobilon -->
      <button 
        class="block lg:hidden text-white bg-sky-800 hover:bg-sky-700 p-2 rounded mx-4"
        @click="toggleMenu"
      >
        <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
        </svg>
      </button>

      <!-- asztali nézet -->
      <div class="hidden lg:flex lg:items-center lg:space-x-6 lg:flex-grow lg:justify-center">
        <RouterLink to="/rents/renthistory" class="px-3 py-2" :class="getActiveClass('/rents/renthistory')">
          <span class="self-center text-xl font-semibold hover:text-lime-400">Lezárt Bérlések</span>
        </RouterLink>
        
        <RouterLink to="/bills/AllBills" class="px-3 py-2" :class="getActiveClass('/bills/AllBills')">
          <span class="self-center text-xl font-semibold hover:text-lime-400">Számlák</span>
        </RouterLink>
        
        <RouterLink to="/bills/fines" class="px-3 py-2" :class="getActiveClass('/bills/fines')">
          <span class="self-center text-xl font-semibold hover:text-lime-400">Bírságok</span>
        </RouterLink>
        
        <RouterLink to="/cars" class="px-3 py-2" :class="getActiveClass('/cars')">
          <span class="self-center text-xl font-semibold hover:text-lime-400">Autok</span>
        </RouterLink>
        
        <RouterLink to="/fleets/fleetIndex" class="px-3 py-2" :class="getActiveClass('/fleets/fleetIndex')">
          <span class="self-center text-xl font-semibold hover:text-lime-400">Flotta</span>
        </RouterLink>
        
        <RouterLink to="/users" class="px-3 py-2" :class="getActiveClass('/users')">
          <span class="self-center text-xl font-semibold hover:text-lime-400">Felhasználók</span>
        </RouterLink>
      </div>

      <div class="hidden lg:flex lg:items-center">
        <template v-if="!isLoggedIn">
          <RouterLink to="/logins/adminPage" class="px-3 py-2" :class="getActiveClass('/logins/adminPage')">
            <span class="self-center text-xl font-semibold hover:text-lime-500">
              <i class="fa-solid fa-arrow-right-to-bracket mr-1"></i>Bejelentkezés
            </span>
          </RouterLink>
          
          <RouterLink to="/registers/registerPage" class="px-3 py-2" :class="getActiveClass('/registers/registerPage')">
            <span class="self-center text-xl font-semibold hover:text-lime-400">
              <i class="fa-solid fa-user text-xl mr-1"></i>Regisztráció
            </span>
          </RouterLink>
        </template>
        
        <template v-else>
          <RouterLink to="/" class="px-3 py-2" :class="getActiveClass('/profile')">
            <span class="self-center text-xl font-semibold hover:text-lime-400">
              <i class="fa-solid fa-user-gear text-xl mr-1"></i>Profilom
            </span>
          </RouterLink>
          
          <RouterLink to="/" @click="handleLogout" class="px-3 py-2 cursor-pointer">
            <span class="self-center text-xl font-semibold hover:text-lime-500">
              <i class="fa-solid fa-right-from-bracket mr-1"></i>Kijelentkezés
            </span>
          </RouterLink>
        </template>
      </div>
    </div>

    <!-- Mobil menü -->
    <div 
      class="w-full bg-opacity-0 rounded-b overflow-hidden transition-all duration-300 ease-in-out"
      :class="menuOpen ? 'max-h-screen opacity-100' : 'max-h-0 opacity-0'"
    >
      <ul class="flex flex-col p-4 space-y-3">
        <li>
          <RouterLink to="/rents/renthistory" class="block py-2 px-3 w-full hover:bg-sky-800 rounded"
            :class="getActiveClass('/rents/renthistory')">
            <span class="font-semibold">Lezárt Bérlések</span>
          </RouterLink>
        </li>
        
        <li>
          <RouterLink to="/bills/AllBills" class="block py-2 px-3 w-full hover:bg-sky-800 rounded"
            :class="getActiveClass('/bills/AllBills')">
            <span class="font-semibold">Számlák</span>
          </RouterLink>
        </li>
        
        <li>
          <RouterLink to="/bills/fines" class="block py-2 px-3 w-full hover:bg-sky-800 rounded"
            :class="getActiveClass('/bills/fines')">
            <span class="font-semibold">Bírságok</span>
          </RouterLink>
        </li>
        
        <li>
          <RouterLink to="/cars" class="block py-2 px-3 w-full hover:bg-sky-800 rounded"
            :class="getActiveClass('/cars')">
            <span class="font-semibold">Autok</span>
          </RouterLink>
        </li>
        
        <li>
          <RouterLink to="/fleets/fleetIndex" class="block py-2 px-3 w-full hover:bg-sky-800 rounded"
            :class="getActiveClass('/fleets/fleetIndex')">
            <span class="font-semibold">Flotta</span>
          </RouterLink>
        </li>
        
        <li>
          <RouterLink to="/users" class="block py-2 px-3 w-full hover:bg-sky-800 rounded"
            :class="getActiveClass('/users')">
            <span class="font-semibold">Felhasználók</span>
          </RouterLink>
        </li>
        
        <!-- Bejelentkezés/Regisztráció vagy Profil/Kijelentkezés -->
        <template v-if="!isLoggedIn">
          <li>
            <RouterLink to="/logins/adminPage" class="block py-2 px-3 w-full hover:bg-sky-800 rounded"
              :class="getActiveClass('/logins/adminPage')">
              <span class="font-semibold">
                <i class="fa-solid fa-arrow-right-to-bracket mr-1"></i>Bejelentkezés
              </span>
            </RouterLink>
          </li>
          
          <li>
            <RouterLink to="/registers/registerPage" class="block py-2 px-3 w-full hover:bg-sky-800 rounded"
              :class="getActiveClass('/registers/registerPage')">
              <span class="font-semibold">
                <i class="fa-solid fa-user mr-1"></i>Regisztráció
              </span>
            </RouterLink>
          </li>
        </template>
        
        <template v-else>
          <li>
            <RouterLink to="/" class="block py-2 px-3 w-full hover:bg-sky-800 rounded"
              :class="getActiveClass('/profile')">
              <span class="font-semibold">
                <i class="fa-solid fa-user-gear mr-1"></i>Profilom
              </span>
            </RouterLink>
          </li>
          
          <li>
            <RouterLink to="/" @click="handleLogout" class="block py-2 px-3 w-full hover:bg-sky-800 rounded cursor-pointer">
              <span class="font-semibold">
                <i class="fa-solid fa-right-from-bracket mr-1"></i>Kijelentkezés
              </span>
            </RouterLink>
          </li>
        </template>
      </ul>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRoute } from 'vue-router';

const menuOpen = ref(false);
const isLoggedIn = ref(false);
const route = useRoute();

// Menü váltás
function toggleMenu() {
  menuOpen.value = !menuOpen.value;
}
function getActiveClass(path) {
  return {
    'font-bold text-lime-500': route.path === path,
    'text-white': route.path !== path
  };
}
// Kijelentkezés
function handleLogout() {

  userStore.logout() 
  menuOpen.value = false;
}
</script>