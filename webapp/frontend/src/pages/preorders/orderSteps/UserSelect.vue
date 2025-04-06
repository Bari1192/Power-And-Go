<!-- UserSelect.vue (gyermek komponens) -->
<template>
    <div class="my-6 flex justify-center mx-auto">
      <h1 class="text-2xl mx-auto font-bold">Felhasználók listája</h1>
    </div>
  
    <div class="my-6 grid grid-rows-2 justify-center mx-auto">
      <div class="row">
        <h1 class="text-2xl mx-auto font-bold">Keresés:</h1>
      </div>
      <div class="row">
        <FormKit
          type="text"
          name="userFilter"
          label="Felhasználó keresése"
          v-model="searchQuery"
          placeholder="Kezdj el gépelni a szűréshez..."
          help="Szűrés név alapján"
        />
      </div>
    </div>
  
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-2 xl:gap-6 justify-center mx-auto">
      <div
        v-for="user in filteredUsers"
        :key="user.id"
        @click="userSelect(user.id)"
        class="bg-lime-500 rounded-lg border-2 border-orange-300 shadow-xl p-4 cursor-pointer hover:bg-emerald-500 hover:text-lime-50 duration-200 ease-in-out w-full xl:min-w-[250px]"
      >
        <p class="font-medium">Felhasználó neve: {{ user.user_name || user.name }}</p>
        <p>Előfizetési csomag: {{ SelectSubScrip(user.sub_id) }}</p>
        <p>Egyenleg: {{ user.account_balance }}</p>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, watch } from 'vue';
  
  const props = defineProps({
    users: {
      type: Array,
      required: true
    }
  });
  
  const emit = defineEmits(["selectedUser"]);
  
  // Helyi keresés
  const searchQuery = ref('');
  
  // Számított tulajdonság a szűréshez "Azonnali"
  const filteredUsers = computed(() => {
    if (!searchQuery.value) {
      return props.users;
    }
    
    const query = searchQuery.value.toLowerCase();
    return props.users.filter(user => {
      const userName = (user.user_name || user.name || '').toLowerCase();
      return userName.includes(query);
    });
  });
  
  const userSelect = (userId) => {
    emit('selectedUser', userId);
  };
  
  const SelectSubScrip = (subid) => {
    switch(subid) {
      case 1: return 'Power';
      case 2: return 'Power-Plus';
      case 3: return 'Power-Premium';
      case 4: return 'Power-VIP';
      default: return 'N/A előfiz.';
    }
  };
  </script>