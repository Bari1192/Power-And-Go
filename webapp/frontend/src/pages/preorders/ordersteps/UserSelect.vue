<template>
    <div class="w-full max-w-[1200px] mx-auto">

        <div
            class="bg-emerald-500/70 max-w-[900px] mx-auto my-8 border-4 mb-6 border-emerald-600/75 rounded-lg shadow-xl p-4">
            <FormKit type="form" :actions="false" #default="{ value }" :validation="'required'" :validation-messages="{
                required: 'Kérjük minden adatot töltsön ki!'
            }">
                <div class="mb-4">
                    <p class=" bg-emerald-700 mx-auto text-center py-2 rounded-lg mb-4">
                    <h1 class="text-3xl text-white  mx-auto font-bold">Felhasználók listája</h1>
                    </p>
                    <h5
                        class="mb-2 text-2xl font-bold text-lime-50 bg-amber-500/85 rounded-xl p-3 tracking-wider border-b-4 border-spacing-8 border-lime-100 w-fit">
                        Gyors Keresés
                    </h5>
                </div>

                <div class="flex flex-col mx-auto space-y-2">
                    <FormKit name="searchUser" type="text" label-class="mt-3 pl-1 font-semibold text-lime-900"
                        outer-class="w-full" v-model="searchTerm" @input="updateSearch"
                        input-class="appearance-none w-1/3 border p-2 rounded-md text-emerald-800 font-medium border focus:outline-none focus:border-emerald-700 focus:border-2"
                        placeholder="Ide írja a felhasználó nevét..." help-class="text-slate-300 px-2 italic" />
                </div>
            </FormKit>
        </div>
        <div class="border-b-8 mx-auto border-b-emerald-900/25 w-full lg:w-11/12 my-12 rounded-t-3xl shadow-md">

        </div>

        <div v-if="isLoading" class="bg-yellow-100 p-3 rounded-lg mb-4 text-center">
            <span class="inline-block animate-spin mr-2">⟳</span> Felhasználók betöltése...
        </div>

        <div v-else-if="displayedUsers.length === 0" class="bg-yellow-100 p-3 rounded-lg mb-4 text-center">
            <span v-if="searchTerm">Nincs a keresésnek megfelelő felhasználó.</span>
            <span v-else>Nincsenek megjeleníthető felhasználók.</span>
        </div>

        <!-- Felhasználók listája -->
        <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-4 w-full 
        
        ">
            <div v-for="user in displayedUsers" :key="user.id" @click="userSelect(user.id)"
                class="bg-lime-500 rounded-lg text-slate-100 border-2 border-orange-300 shadow-xl p-4 cursor-pointer hover:bg-emerald-500 hover:text-lime-50 duration-200 ease-in-out">
                <p><span class="font-medium text-slate-600">Felhasználó neve: </span>{{ user.user_name || 'ismeretlen' }}</p>
                <p><span class="text-slate-600">Előfizetési csomag: </span>{{ SelectSubScrip(user.sub_id) }}</p>
                <p><span class="text-slate-600">Aktuális Egyenleg: </span>{{ user.account_balance || 0}} Ft</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useUserStore } from '@/stores/UserStore';
import { storeToRefs } from 'pinia';
import { useAuthStore } from '@/stores/AuthenticationStore';

const authStore = useAuthStore();
const userStore = useUserStore();
const isLoading = ref(true);
const searchTerm = ref('');

// Store-ból referenciaként kivett értékek
const { users: storeUsers, userSearch } = storeToRefs(userStore);

// Emitterek beállítása
const emit = defineEmits(["selectedUser"]);

// Számított tulajdonság a megjelenítendő felhasználókhoz
const displayedUsers = computed(() => {
    if (!searchTerm.value || searchTerm.value.trim() === '') {
        return storeUsers.value || [];
    }
    return userSearch.value || [];
});

onMounted(async () => {
    try {
        isLoading.value = true;
        await userStore.getUsers();
        authStore.initializeFromStorage();
    } catch (error) {
        console.error("Hiba a felhasználók betöltésekor:", error);
    } finally {
        isLoading.value = false;
    }
});

function updateSearch() {
    try {
        userStore.startSearchBy(searchTerm.value);
    } catch (error) {
        console.error("Hiba a kereséskor:", error);
    }
}

const userSelect = (userId) => {
    if (!userId) {
        console.warn("Érvénytelen felhasználói ID!");
        return;
    }
    emit('selectedUser', userId);
};

const SelectSubScrip = (subid) => {
    switch (subid) {
        case 1:
            return 'Power';
        case 2:
            return 'Power-Plus';
        case 3:
            return 'Power-Premium';
        case 4:
            return 'Power-VIP';
        default:
            return 'N/A előfiz.';
    }
};

// Figyelni a felhasználói adatok változását
watch(() => storeUsers.value, () => {
    updateSearch();
}, { immediate: true });
</script>
