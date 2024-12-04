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
                    <p class="my-3">A bérlésed során {.{"0h 00"}.} hosszabbítás volt a foglalásodon, <b>{{
                        rent.vezetesi_idotartam
                            }}</b> , ami alatt <b> {{ rent.megtett_tavolsag }} km-t</b> tettél meg, és a vezetés mellett
                        <b> {{ rent.parkolasi_idotartam }}.</b> A bérléshez felhasznált
                        bónusz percek:
                        0h
                        00'.
                        A bérléshez az alábbi kuponokat váltottad be:
                    </p>

                    <p>A bérlésed díját, <b>{{ rent.berles_osszeg }} </b> Ft-ot, hamarosan kiszámlázzuk neked. A
                        részletes
                        díjtételeket és az
                        autó
                        értékelése során készített képeidet tartalmazó bérlés összesítő dokumentumot <u>innen</u> tudod
                        letölteni.
                    </p>
                </div>
                <!-- "Keret" vége -->

                <!-- 2 OPCIÓ => Töltőtt -->
                <p class="mt-10"> Bérlésed alatt 0 kWh-t töltöttél az autóba más szolgáltató töltőoszlopán, ezért
                    ((4,200)) Ft értékű
                    PowerAndGo csomagot szereztél. A töltésért járó csomagokról bővebben <router-link
                        to="/ugyfelszolgalat" class="underline underline-offset-4 text-lime-500 ">itt</router-link>
                    olvashatsz.</p>
                <br><!-- 2 OPCIÓ => Nem töltött -->
                <p>Bérlésed alatt 0 kWh-t töltöttél az autóba más szolgáltató töltőoszlopán, ezért 0 Ft értékű
                    PowerAndGo csomagot szereztél. A töltésért járó csomagokról bővebben <router-link
                        to="/ugyfelszolgalat" class="underline underline-offset-4 text-lime-500 ">itt</router-link>
                    olvashatsz.</p>

                <p class="my-3 font-bold"> Reméljük, hogy élvezted a PowerAndGo-s utadat!</p>

                <p class="mb-10"> Ha hibát észleltél vagy ötleted, észrevételed van a szolgáltatásunkkal kapcsolatban,
                    kérjük, jelezd
                    nekünk az <router-link to="/ugyfelszolgalat"
                        class="underline underline-offset-4 text-lime-500 italic">ugyfelszolgalat@powerandgo.com</router-link>
                    email címen!</p>

                <p>Köszönettel,</p>
                <p class="font-bold">PowerAndGo csapata</p>

                <p class="my-3 font-bold italic"> Élmények. Neked. Nekünk. Tisztán.</p>
            </div>
        </div>
        <!-- Lapozó -->
        <div class="pagination text-center my-16">
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
