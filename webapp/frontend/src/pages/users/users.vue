<template>
    <BaseLayout>
        <div class="container mt-20 mb-20 w-2/3 mx-auto">

            <div v-for="user in users" :key="user_id" class="grid grid-cols-3 grid-rows-1 gap-6  my-3">
                <BaseCard :title="'Felhasználó azonosító'" :text="user.user_id" />
                

                <RouterLink :to="{ name: '/users/[id]/userPerson', params: { id: user.user_id } }">
                <BaseCard :title="'Felhasználónév'" 
                :text="user.username" />
            </RouterLink>
                <BaseCard :title="'Felh. egyenlege'" :text="user.account_balance + ' Ft'" />
                <div class="w-full border-b-8 border-indigo-800 rounded-xl mb-6 grid-rows-1 opacity-60"></div>
            </div>

        </div>
    </BaseLayout>
</template>

<script>
import BaseLayout from "@layouts/BaseLayout.vue";
import BaseCard from "@layouts/BaseCard.vue";
import { http } from "@utils/http";

export default {
    components: {
        BaseLayout,
        BaseCard,
    },
    data() {
        return {
            users: [],
        };
    },
    async mounted() {
        try {
            const response = await http.get("/users");
            this.users = response.data.data;
        } catch (error) {
            console.error("Hiba történt az API-hívás során:", error);
        }
    }
};
</script>