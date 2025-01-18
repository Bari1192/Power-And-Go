<template>
    <BaseLayout>
        <div class="mt-10"> <!-- EXTRA MARGIN FENTRE --> </div>

        <!-- LAPOZÓ -->
        <div class="mx-auto px-5 py-4 flex justify-around w-fit border-2 rounded-2xl border-lime-600 shadow-lg shadow-lime-900">
            <button @click="changePage(1)" :disabled="currentPage === 1"
                class="cursor-pointer mx-2 text-lg focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">
                ⮜⮜
            </button>
            <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
                class="cursor-pointer mx-2 text-lg focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">
                ⮜
            </button>
            <button v-for="page in visiblePages" :key="page" @click="changePage(page)"
                :class="{ 'bg-sky-700 text-white': page === currentPage, 'bg-sky-500 text-black': page !== currentPage }"
                class="cursor-pointer mx-2 text-lg focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">
                {{ page }}
            </button>
            <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages"
                class="cursor-pointer mx-2 text-lg focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">
                ⮞
            </button>
            <button @click="changePage(totalPages)" :disabled="currentPage === totalPages"
                class="cursor-pointer mx-2 text-lg focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">
                ⮞⮞
            </button>
        </div>

        <!-- VISSZAIGAZOLÓ E-MAIL TELJES SZERKEZETE -->
        <div class="container mx-auto my-10" v-for="rent in paginatedRents" :key="rent.id">
            <div class="col w-3/4 m-auto">
                <img src="@assets/img/BaseEmail/pelda_logo.png" class="opacity-80">

            </div>
            <div class="col text-white col w-3/4  mx-auto text-justify">
                <p class="my-3 text-xl text-lime-400 font-bold">Kedves {{ rent.user.person.lastname }}!</p>
                <p class="my-3 font-bold ">Köszönjük, hogy a PowerAndGo e-carsharinget választottad!</p>

                <!-- "Keret" kezdete -->
                <div class=" border-solid border border-lime-600 p-6 m-2">
                    <p class="my-3"> A(z) <b>{{ rent.auto.plate }}</b> rendszámú PowerAndGo bérlését <b>{{
                        rent.rent_start_date }}</b>
                        -kor kezdted
                        és
                        <b>{{ rent.rent_end_date }}</b>
                        -kor
                        fejezted be.
                    </p>
                    <p class="my-3">A bérlésed során {.{"0h 00"}.} hosszabbítás volt a foglalásodon, <b>{{
                        rent.vezetesi_idotartam
                            }}</b> , ami alatt <b> {{ rent.driving_distance }} km-t</b> tettél meg, és a vezetés mellett
                        <b> {{ rent.parkolasi_idotartam }}.</b> A bérléshez felhasznált
                        bónusz percek:
                        0h
                        00'.
                        A bérléshez az alábbi kuponokat váltottad be:
                    </p>

                    <p>A bérlésed díját, <b>{{ rent.rental_cost }} </b> Ft-ot, hamarosan kiszámlázzuk neked. A
                        részletes
                        díjtételeket és az
                        autó
                        értékelése során készített képeidet tartalmazó bérlés összesítő dokumentumot <u>innen</u> tudod
                        letölteni.
                    </p>
                </div>
                <!-- "Keret" vége -->

                <!-- 2 OPCIÓ => Töltőtt -->
                <p class="mt-10"> Bérlésed alatt <b>{{ '0' }} kWh-t</b> töltöttél az autóba más szolgáltató
                    töltőoszlopán,
                    ezért
                    "{{ 4_200 }}" Ft értékű
                    PowerAndGo csomagot szereztél. A töltésért járó csomagokról bővebben <router-link
                        to="/ugyfelszolgalat" class="underline underline-offset-4 text-lime-500 ">itt</router-link>
                    olvashatsz.</p>
                <br><!-- 2 OPCIÓ => Nem töltött -->
                <p>Bérlésed alatt <b>{{ '0' }} kWh-t</b> töltöttél az autóba más szolgáltató töltőoszlopán, ezért 0 Ft
                    értékű
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
    async mounted() {
        try {
            const resp = await http.get('/renthistories');
            this.rents = resp.data.data;
        } catch (error) {
            console.error('Hiba történt az API hívás során:', error);
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
    visiblePages() {
        const pages = [];
        const maxPagesToShow = 5;
        let startPage = Math.max(1, this.currentPage - Math.floor(maxPagesToShow / 2));
        let endPage = startPage + maxPagesToShow - 1;

        if (endPage > this.totalPages) {
            endPage = this.totalPages;
            startPage = Math.max(1, endPage - maxPagesToShow + 1);
        }

        for (let i = startPage; i <= endPage; i++) {
            pages.push(i);
        }

        return pages;
    },
    methods: {
        changePage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
            }
        },
    },

}
</script>
