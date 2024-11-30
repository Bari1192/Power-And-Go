<template>
    <BaseLayout>
        <!-- LAPOZÓ -->
        <div class="pagination text-center my-5">
            <button v-for="page in totalPages" :key="page" @click="changePage(page)"
                :class="{ 'bg-sky-700 text-white': page === currentPage, 'bg-sky-500 text-black': page !== currentPage }"
                class="px-3 py-2 m-1 border rounded">
                {{ page }}
            </button>
        </div>

        <!-- VISSZAIGAZOLÓ E-MAIL TELJES SZERKEZETE -->
        <div class="container mx-auto my-10" v-for="rent in paginatedRents" :key="rent.lezart_berles_id">
            <div class="col w-3/4 m-auto">
                <img src="@assets/img/BaseEmail/pelda_logo.png" class="opacity-80">

            </div>
            <div class="col text-white col w-3/4  mx-auto text-justify">
                <p class="my-3 text-xl text-lime-400 font-bold">Kedves {{ rent.szemely_knev }}!</p>
                <p class="my-3 font-bold ">Köszönjük, hogy a PowerAndGo e-carsharinget választottad!</p>

                <!-- "Keret" kezdete -->
                <div class=" border-solid border border-lime-600 p-6 m-2">
                    <p class="my-3"> A(z) <b>{{ rent.auto_rendszam }}</b> rendszámú PowerAndGo bérlését <b>{{
                        rent.berles_kezdete }}</b>
                        -kor kezdted
                        és
                        <b>{{ rent.berles_vege }}</b>
                        -kor
                        fejezted be.
                    </p>
                    <p class="my-3">A bérlésed során 0h 00' hosszabbítás volt a foglalásodon, <b>{{ rent.berles_percek
                            }}</b> percet vezettél,
                        ami
                        alatt
                        <b> {{
                            rent.megtett_tavolsag }}
                            km-t</b>
                        tettél meg, és a vezetés mellett ((0h 46'))-et parkoltál. A bérléshez felhasznált bónusz percek:
                        0h
                        00'.
                        A bérléshez az alábbi kuponokat váltottad be:
                    </p>

                    <p>A bérlésed díját, ((1,833)) Ft-ot, hamarosan kiszámlázzuk neked. A részletes díjtételeket és az
                        autó
                        értékelése során készített képeidet tartalmazó bérlés összesítő dokumentumot <u>innen</u> tudod
                        letölteni.
                    </p>
                </div>
                <!-- "Keret" vége -->

                <!-- 2 OPCIÓ => Töltőtt -->
                <p class="mt-10"> Bérlésed alatt 0 kWh-t töltöttél az autóba más szolgáltató töltőoszlopán, ezért ((4,200)) Ft értékű
                    PowerAndGo csomagot szereztél. A töltésért járó csomagokról bővebben <router-link
                        to="/ugyfelszolgalat" class="underline underline-offset-4 text-lime-500 ">itt</router-link>
                    olvashatsz.</p>
                <br><!-- 2 OPCIÓ => Nem töltött -->
                <p>Bérlésed alatt 0 kWh-t töltöttél az autóba más szolgáltató töltőoszlopán, ezért 0 Ft értékű
                    PowerAndGo csomagot szereztél. A töltésért járó csomagokról bővebben <router-link
                        to="/ugyfelszolgalat" class="underline underline-offset-4 text-lime-500 ">itt</router-link>
                    olvashatsz.</p>

                <p class="my-3 font-bold"> Reméljük, hogy élvezted a PowerAndGo-s utadat!</p>

                <p class="mb-10"> Ha hibát észleltél vagy ötleted, észrevételed van a szolgáltatásunkkal kapcsolatban, kérjük, jelezd
                    nekünk az <router-link to="/ugyfelszolgalat"
                        class="underline underline-offset-4 text-lime-500 italic">ugyfelszolgalat@powerandgo.com</router-link>
                    email címen!</p>

                <p>Köszönettel,</p>
                <p class="font-bold">PowerAndGo csapata</p>

                <p class="my-3 font-bold italic"> Élmények. Neked. Nekünk. Tisztán.</p>
            </div>
        </div>

        <!-- TÁBLÁZAT A LEKÉRT ADATOKKAL -->
        <table class="mx-auto my-10 w-3/4 border-2 border-sky-300 border-collapse">
            <thead>
                <tr class="text-white text-xl bg-sky-600">
                    <th class="px-2 ">Bérlés Azonosítója</th>
                    <th class="px-2">Autó Rendszáma</th>
                    <th class="px-2">Bérlés kezdete</th>
                    <th class="px-2">Bérlés Vége</th>
                    <th class="px-2">Bérlés Összesítője (perc)</th>
                    <th class="px-2">Megtett táv</th>
                    <th class="px-2">Keresztnév</th>
                    <th class="px-2">Email cím</th>
                </tr>
            </thead>
            <tbody class=" text-white text-lg">
                <tr v-for="rent in paginatedRents" :key="rent.lezart_berles_id"
                    class=" text-white cursor-pointer odd:hover:bg-sky-900 even:hover:bg-sky-800 odd:bg-sky-500 even:bg-sky-600">
                    <td class="text-center p-2">{{ rent.lezart_berles_id }}</td>
                    <td class="text-center p-2">{{ rent.auto_rendszam }}</td>
                    <td class="text-center p-2">{{ rent.berles_kezdete }}</td>
                    <td class="text-center p-2">{{ rent.berles_vege }}</td>
                    <td class="text-center p-2">{{ rent.berles_percek }}</td>
                    <td class="text-center p-2">{{ rent.megtett_tavolsag }}</td>
                    <td class="text-center p-2">{{ rent.szemely_knev }}</td>
                    <td class="text-center p-2">{{ rent.szemely_email }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Lapozó -->
        <div class="pagination text-center my-5">
            <button v-for="page in totalPages" :key="page" @click="changePage(page)"
                :class="{ 'bg-sky-700 text-white': page === currentPage, 'bg-sky-500 text-black': page !== currentPage }"
                class="px-3 py-2 m-1 border rounded">
                {{ page }}
            </button>
        </div>


    </BaseLayout>
</template>

<script>
import BaseLayout from '@layouts/BaseLayout.vue'
import { http } from '@utils/http.mjs';

export default {
    name: 'renthistory',

    components: {
        BaseLayout,
    },
    data() {
        return {
            rents: [],
            currentPage: 1,
            itemsPerPage: 10,
        }
    },
    computed: {
        // # Az aktuális oldal - adatok.
        paginatedRents() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.rents.slice(start, end);
        },
        // # Az összes oldalszám meghatározása.
        totalPages() {
            return Math.ceil(this.rents.length / this.itemsPerPage);
        },
    },
    methods: {
        //# Oldalak közötti váltás mechanika.
        changePage(page) {
            this.currentPage = page;
        },
    },
    async mounted() {
        try {
            const resp = await http.get('/renthistories');
            this.rents = resp.data.data;
        } catch (error) {
            console.error('Hiba történt az API hívás során:', error);
        }
    }
}
</script>
