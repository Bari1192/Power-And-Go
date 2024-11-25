<template>
  <div>
    <nav
      class="bg-gradient-to-b from-[#09283a] via-[#1e4d65] to-[#0b2a3d] border-b-4 border-b-green-500 flex-row flex align-middle space-x-6 pr-3 pl-2 items-center"
    >
      <RouterLink to="/">
        <img
          src="@/assets/header/kisautoKesz.webp"
          alt="Kis autó"
          loading="lazy"
          class="flex justify-start"
        />
      </RouterLink>

      <RouterLink
        to="/"
        class="self-center text-2xl text-white hover:text-sky-500 transition-colors"
      >
        Főoldal
      </RouterLink>
      <RouterLink
        to="/admin/adminpage"
        class="self-center text-2xl text-white hover:text-sky-500 transition-colors"
      >
        Admin
      </RouterLink>
      <RouterLink
        to="map"
        class="self-center text-2xl text-white hover:text-sky-500 transition-colors"
      >
        Térkép
      </RouterLink>
      <RouterLink
        to="/aboutus"
        class="self-center text-2xl text-white hover:text-sky-500 transition-colors"
      >
        Rólunk
      </RouterLink>
      <RouterLink
        to="/coupons"
        class="self-center text-2xl text-white hover:text-sky-500 transition-colors"
      >
        Kedvezmények
      </RouterLink>
      <RouterLink
        to="/rents"
        class="self-center text-2xl text-white hover:text-sky-500 transition-colors"
      >
        Foglalás
      </RouterLink>
      <RouterLink
        to="/companies"
        class="self-center text-2xl text-white hover:text-sky-500 transition-colors"
      >
        Cégeknek
      </RouterLink>
    </nav>
  </div>
</template>

<script setup>
import { ref } from "vue";

const menuOpen = ref(false);

function toggleMenu() {
  menuOpen.value = !menuOpen.value;
}
</script>

<style scoped>
.menu {
  @apply flex flex-col p-4;
  @apply md:flex-row md:p-0 md:space-x-8;
}
.menuitem {
  @apply block py-2 px-3 text-gray-500;
  @apply hover:bg-blue-400 hover:text-white rounded p-2;
}

.menuitem:has(.active) {
  @apply text-blue-500;
  @apply hover:text-white;
}
</style>
