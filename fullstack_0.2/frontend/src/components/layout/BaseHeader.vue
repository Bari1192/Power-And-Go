<template>
  <nav class="bg-white dark:bg-gray-800 dark:text-white">
    <div class="flex justify-between align-middle p-3 border-b-2 flex-wrap dark:border-none">
      <RouterLink to="/" class="flex items-center space-x-3 mx-auto" active-class="font-bold text-lime-500">
        <img src="../../assets/img/BaseEmail/logo_small.png" class="h-24 w-full" alt="Power And Go Logo" />
        <span class="self-center text-2xl font-semibold whitespace-nowrap  dark:hover:text-lime-500">Power
          And Go</span>
      </RouterLink>
      <RouterLink to="/rents/renthistory" class="flex mx-auto space-x-3" active-class="text-lime-500"
        exact-active-class="text-lime-500">
        <span class="self-center text-2xl font-semibold dark:hover:text-lime-500">Lezárt
          Bérlések</span>
      </RouterLink>
      <RouterLink to="/bills/AllBills" class="flex mx-auto space-x-3" active-class="font-bold text-lime-500">
        <span class="self-center text-2xl font-semibold dark:hover:text-lime-500">Számlák</span>
      </RouterLink>
      <RouterLink to="/bills/fines" class="flex mx-auto space-x-3" active-class="font-bold text-lime-500">
        <span class="self-center text-2xl font-semibold dark:hover:text-lime-500">Bírságok</span>
      </RouterLink>
      <RouterLink to="/cars/cars" class="flex mx-auto space-x-3" active-class="font-bold text-lime-500">
        <span class="self-center text-2xl font-semibold  dark:hover:text-lime-500">Autok</span>
      </RouterLink>
      <RouterLink to="/fleets/fleets" class="flex mx-auto space-x-3" active-class="font-bold text-lime-500">
        <span class="self-center text-2xl font-semibold  dark:hover:text-lime-500">Flotta</span>
      </RouterLink>
      <RouterLink to="/logins/adminPage" class="flex mx-auto space-x-3" active-class="font-bold text-lime-500">
        <span class="self-center text-2xl font-semibold  dark:hover:text-lime-500">Bejelentkezés</span>
      </RouterLink>
      <button class="block md:hidden" @click="toggleMenu">
        <svg class="w-5 h-5 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M1 1h15M1 7h15M1 13h15" />
        </svg>
      </button>
      <div class="w-full md:block md:w-auto" :class="{ hidden: !menuOpen }">
        <ul class="menu">
          <li class="menuitem">
            <RouterLink to="#">1. oldal</RouterLink>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue'

const menuOpen = ref(false)

function toggleMenu() {
  menuOpen.value = !menuOpen.value
}
</script>

<style scoped>
.menu {
  @apply flex flex-col p-4;
  @apply md:flex-row md:p-0 md:space-x-8;
}

.menuitem {
  @apply block py-2 px-3 text-gray-500;
  @apply hover:bg-blue-400 hover:text-sky-100 rounded p-2;
}

.menuitem:has(.active) {
  @apply text-blue-500;
  @apply hover:text-sky-100;
}
</style>
