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
                    <tr v-for="fine in fines" :key="fine.id"
                        class="text-white cursor-pointer odd:hover:bg-sky-900 even:hover:bg-sky-800 odd:bg-sky-500 even:bg-sky-600">
                        <td class="text-center p-2 font-semibold">{{ fine.bill_type }}</td>
                        <td class="text-center p-2">{{ fine.invoice_date }}</td>
                        <td class="text-center p-2 font-semibold">{{ fine.total_cost }} Ft</td>
                        <td class="text-center text-lime-300 font-bold p-2 italic">{{ fine.invoice_status }}</td>
                        <td class="text-center p-2">{{ fine.distance }} km</td>
                        <td class="text-center p-2">{{ timeFormat(fine.parking_minutes) }} </td>
                        <td class="text-center p-2">{{ timeFormat(fine.driving_minutes) }} </td>
                        <td class="text-center p-2">{{ fine.rent_start }} <b>{{ fine.rent_start }}</b></td>
                        <td class="text-center p-2">{{ fine.rent_close }} <b>{{ fine.rent_end_time }}</b></td>
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
        timeFormat(minutes) {
            if (minutes < 60) {
                return `${minutes} perc`;
            }
            const hours = Math.floor(minutes / 60);
            const plusMinutes = minutes % 60;
            if (plusMinutes === 0) {
                return `${hours} óra`;
            }
            return `${hours}ó ${plusMinutes}p`;
        },

        async loadFines(page = 1) {
            try {
                const resp = await http.get(`/bills?page=${page}`);
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