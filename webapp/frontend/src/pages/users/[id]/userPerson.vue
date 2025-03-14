<template>
    <BaseLayout>
        <div class=" mx-auto w-4/5 border border-sky-500 rounded-3xl overflow-hidden">
            <table class="w-full">
                <thead>
                    <tr class="text-white bg-sky-500 ">
                        <th class="px-4 py-2 text-center">user_id</th>
                        <th class="p-0 m-0 w-1 text-center">username</th>
                        <th class="px-2 py-2 text-center">account_balance</th>
                        <!-- person_information -->
                        <th class="px-2 py-2 text-center">person_id</th>
                        <th class="px-2 py-2 text-center">Felsz. bes.</th>
                        <th class="px-2 py-2 text-center">flotta. bes.</th>
                        <th class="px-2 py-2 text-center">category_id</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="car.car_id"
                        class="odd:bg-sky-800 even:bg-sky-950 text-center text-lg">
                        <td class="py-2 text-lime-500 font-semibold ">
                        </td>
                        <td class="py-2 text-sky-300 font-semibold">{{ car.car_id }}</td>
                        <td class="p-0 m-0 text-sky-300 font-semibold">{{ car.odometer }}</td>
                        <td class="py-2 text-sky-300 font-semibold">{{ car.manufactured }}</td>
                        <td class="py-2 text-sky-300 font-semibold">{{ car.equipment_class }}</td>
                        <td class="py-2 text-sky-300 font-semibold">{{ car.flotta_adatok.id }}</td>
                        <td class="py-2 text-sky-300 font-semibold">{{ car.category_id }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </BaseLayout>
</template>



<script>
import BaseLayout from "@layouts/BaseLayout.vue";
export default {
    props: {
        users: [],
    },
    async mounted() {
        const response = await http.get(`/users/${this.$route.params.id}`);
        this.users = response.data.data;
    },
}
</script>
