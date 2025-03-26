<template>
  <nav class="text-white bg-sky-950 border-b-4 border-sky-800">
    <div class="flex justify-between align-middle p-3 border-b-2 flex-wrap dark:border-none">
      <RouterLink to="/" class="flex items-center  mx-auto" active-class="font-bold text-lime-500">
        <img src="../../assets/img/BaseEmail/logo_small.png" class="h-24 w-full" alt="Power And Go Logo" />
        <span class="self-center text-2xl font-semibold whitespace-nowrap  hover:text-lime-400">Power
          And Go</span>
      </RouterLink>
      <RouterLink to="/rents/renthistory" class="flex mx-auto " active-class="text-lime-500"
        exact-active-class="text-lime-500">
        <span class="self-center text-2xl font-semibold hover:text-lime-400">Lezárt
          Bérlések</span>
      </RouterLink>
      <RouterLink to="/bills/AllBills" class="flex mx-auto " active-class="font-bold text-lime-500">
        <span class="self-center text-2xl font-semibold hover:text-lime-400">Számlák</span>
      </RouterLink>
      <RouterLink to="/bills/fines" class="flex mx-auto " active-class="font-bold text-lime-500">
        <span class="self-center text-2xl font-semibold hover:text-lime-400">Bírságok</span>
      </RouterLink>
      <RouterLink to="/cars/cars" class="flex mx-auto " active-class="font-bold text-lime-500">
        <span class="self-center text-2xl font-semibold  hover:text-lime-400">Autok</span>
      </RouterLink>
      <RouterLink to="/fleets/fleetIndex" class="flex mx-auto " active-class="font-bold text-lime-500">
        <span class="self-center text-2xl font-semibold  hover:text-lime-400">Flotta</span>
      </RouterLink>
      <RouterLink v-if="!isLoggedIn" to="/logins/adminPage" class="flex mx-auto "
        active-class="font-bold text-lime-500">
        <span class="self-center text-2xl font-semibold  hover:text-lime-500">
          <i class="fa-solid fa-arrow-right-to-bracket">
          </i> Bejelentkezés</span>
      </RouterLink>
      <router-link v-else to="/" class="flex mx-auto" active-class="font-bold text-lime-500">
        <span class="self-center text-2xl font-semibold hover:text-lime-400">
          <i class="fa-solid fa-user-gear text-2xl"></i> Profilom
        </span>
      </router-link>

      

      <RouterLink v-if="!isLoggedIn" to="/registers/registerPage" class="flex mx-auto "
        active-class="font-bold text-lime-500">
        <span class="self-center text-2xl font-semibold  hover:text-lime-400"><i class="fa-solid fa-user text-2xl"></i>
          Regisztráció</span>
      </RouterLink>
      <router-link v-else @click="handleLogout" class="flex mx-auto cursor-pointer" to="/">
        <span class="self-center text-2xl font-semibold hover:text-lime-500">
          <i class="fa-solid fa-right-from-bracket"></i> Kijelentkezés
        </span>
      </router-link>
      <button class="block md:hidden" @click="toggleMenu">
        <svg class="w-5 h-5 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M1 1h15M1 7h15M1 13h15" />
        </svg>
      </button>
      <div class="w-full md:block md:w-auto" :class="{ hidden: !menuOpen }">
        <ul class="menu">
          <li class="menuitem">
            <!-- <RouterLink to="#">1. oldal</RouterLink> -->
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref } from 'vue'
import { toast } from 'vue3-toastify'

const menuOpen = ref(false)

function toggleMenu() {
  menuOpen.value = !menuOpen.value
}
</script>
<script>
export default {
  data() {
    return {
      isLoggedIn: false,
      userData: null
    }
  },

  created() {
    //Bejelentkezéskor
    this.checkAuthStatus();

    //LocalStorage változás (böngészőablak között)
    window.addEventListener('storage', this.checkAuthStatus);
  },
  beforeUnmount() {
    // Eltávolítjuk utána
    window.removeEventListener('storage', this.checkAuthStatus);
  },

  methods: {
    checkAuthStatus() {
      const token = localStorage.getItem('token');
      const user = localStorage.getItem('user');

      this.isLoggedIn = !!token;
      this.userData = user ? JSON.parse(user) : null;
    },

    handleLogout() {
      // Töröljük a bejelentkezési adatokat
      localStorage.removeItem('token');
      localStorage.removeItem('user');

      // Frissítjük a komponens állapotát
      this.isLoggedIn = false;
      this.userData = null;

      // Értesítjük a felhasználót
      toast.success('Sikeres kijelentkezés!', {
        autoClose: 2000,
        position: toast.POSITION.TOP_CENTER,
        transition: toast.TRANSITIONS.BOUNCE,
      });
    }
  },
  mounted() {
    if (this.$eventBus) {
      this.$eventBus.on('login-success', this.checkAuthStatus);
      this.$eventBus.on('logout', this.checkAuthStatus);
    }
  },

  unmounted() {
    if (this.$eventBus) {
      this.$eventBus.off('login-success', this.checkAuthStatus);
      this.$eventBus.off('logout', this.checkAuthStatus);
    }
  }
}
</script>

<style scoped>
.menu {
  @apply flex flex-col p-4;
  @apply md:flex-row md:p-0 md:space-x-8;
}

.menuitem {
  @apply block py-2 px-3 text-lime-500;
  @apply hover:bg-blue-400 hover:text-sky-100 rounded p-2;
}

.menuitem:has(.active) {
  @apply text-sky-300;
  @apply font-semibold;
  @apply hover:text-sky-100;
}
</style>
