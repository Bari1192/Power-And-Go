<template>
    <BaseLayout>
        <div class="mx-auto w-fit my-10 border-2 rounded-2xl border-lime-500">
            <div class="m-auto w-fit mx-auto px-auto py-3 border-b-2 flex-wrap dark:border-none">
                <button @click="gotoPage(1)"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 border border-blue-700 rounded mx-3 ">
                    1. oldal
                </button>
                <button @click="backtoPage" :disabled="!links.prev"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 border border-blue-700 rounded mx-3 ">
                    Előző
                </button>
                <button @click="gotoNext" :disabled="!links.next"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 border border-blue-700 rounded mx-3 ">
                    Következő
                </button>
                <button @click="gotoPage(lastPage)"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 border border-blue-700 rounded mx-3 ">
                    Utolsó oldal
                </button>
            </div>
        </div>
        <div class="mx-auto my-10 border-2 rounded-2xl border-sky-300">
            <table class="w-full border-collapse rounded-2xl overflow-hidden ">
                <thead>
                    <tr class="text-white text-xl bg-sky-600 border-b-8 border-sky-800">
                        <th class="p-2">Számla típusa</th>
                        <th class="p-2">Kiállítva</th>
                        <th class="p-2">Összege</th>
                        <th class="p-2">Állapota</th>
                        <th class="p-2">Levezetett táv</th>
                        <th class="p-2">Parkolás</th>
                        <th class="p-2">Vezetés</th>
                        <th class="p-2">Bérlés kezdete</th>
                        <th class="p-2">Bérlés vége</th>
                    </tr>
                </thead>
                <tbody class="text-white text-lg">
                    <tr v-for="fine in fines" :key="fine.szamla_id"
                        class="text-white cursor-pointer odd:hover:bg-sky-900 even:hover:bg-sky-800 odd:bg-sky-500 even:bg-sky-600">
                        <td class="text-center p-2 font-semibold">{{ fine.szamla_tipus }}</td>
                        <td class="text-center p-2">{{ fine.szamla_kelt }}</td>
                        <td class="text-center p-2 font-semibold">{{ fine.osszeg }} Ft</td>
                        <td class="text-center text-lime-300 font-bold p-2 italic">{{ fine.szamla_status }}</td>
                        <td class="text-center p-2">{{ fine.megtett_tavolsag }} km</td>
                        <td class="text-center p-2">{{ fine.parkolasi_perc }} perc</td>
                        <td class="text-center p-2">{{ fine.vezetesi_perc }} perc</td>
                        <td class="text-center p-2">{{ fine.berles_kezd_datum }} <b>{{ fine.berles_kezd_ido }}</b></td>
                        <td class="text-center p-2">{{ fine.berles_veg_datum }} <b>{{ fine.berles_veg_ido }}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </BaseLayout>
</template>

<script>
import BaseLayout from '@layouts/BaseLayout.vue';
import { http } from '@utils/http.mjs';

export default {
    data() {
        return {
            fines: [],
            currentPage: 1,
            lastPage: 1,
            links: {},
        }
    },
    components: {
        BaseLayout,
    },
    async mounted() {
        await this.loadFines(); // Kezdésként az első oldalt tölti be
    },

    methods: {
        async loadFines(page = 1) {
            try {
                const resp = await http.get(`/szamlak?page=${page}`);
                this.fines = resp.data.data;
                this.currentPage = resp.data.meta.current_page;  // Metaadatok alapján
                this.links = resp.data.links;
                this.lastPage = resp.data.meta.last_page; // Ha létezik a válaszban
            } catch (error) {
                console.error('Hiba történt az API hívás során:', error);
            }
        },
        async gotoPage(page) {
            if (page < 1 || page > this.lastPage) return;
            await this.loadFines(page);
        },
        async gotoNext() {
            if (this.links.next) {
                await this.gotoPage(this.currentPage + 1);
            }
        },
        async backtoPage() {
            if (this.links.prev) {
                await this.gotoPage(this.currentPage - 1);
            }
        }
    }
}
</script>