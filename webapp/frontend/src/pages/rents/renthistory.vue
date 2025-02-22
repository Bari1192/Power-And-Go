<template>
    <BaseLayout>
        <div class="mt-10"> <!-- EXTRA MARGIN FENTRE --> </div>

        <!-- LAPOZÓ -->
        <div
            class="mx-auto px-5 py-4 flex justify-around w-fit border-2 rounded-2xl border-lime-600 shadow-lg shadow-lime-900">
            <button @click="changePage(1)" :disabled="currentPage === 1"
                class="cursor-pointer mx-2 text-lg focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg px-5 py-2.5 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">
                ⮜⮜
            </button>
            <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
                class="cursor-pointer mx-2 text-lg focus:outline-none text-white bg-purple-700 hover:bg-sky-500 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg px-5 py-2.5">
                ⮜
            </button>
            <button v-for="page in visiblePages" :key="page" @click="changePage(page)"
                :class="{ 'bg-sky-700 text-white': page === currentPage, 'bg-sky-500 text-black': page !== currentPage }"
                class="cursor-pointer mx-2 text-lg focus:outline-none text-white bg-purple-700 hover:bg-sky-500 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg px-5 py-2.5">
                {{ page }}
            </button>
            <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages"
                class="cursor-pointer mx-2 text-lg focus:outline-none text-white bg-purple-700 hover:bg-sky-500 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg px-5 py-2.5">
                ⮞
            </button>
            <button @click="changePage(totalPages)" :disabled="currentPage === totalPages"
                class="cursor-pointer mx-2 text-lg focus:outline-none text-white bg-purple-700 hover:bg-sky-500 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg px-5 py-2.5">
                ⮞⮞
            </button>
        </div>

        <div class="container mb-32 mx-auto mt-5 " v-for="rent in paginatedRents" :key="rent.id">
            <div class="col w-3/4 -mb-10 z-5 mx-auto">
                <img src="@assets/img/BaseEmail/email_logo_PWG.png" class="opacity-80">
            </div>
            <div
                class="col text-white bg-sky-800 bg-opacity-50 border-8 rounded-xl border-sky-900 py-6 px-10 w-3/4  mx-auto text-justify">
                <p class="mb-3 text-xl text-lime-400 font-bold">Kedves {{ rent.person }}!</p>
                <p class="my-3 font-bold ">Köszönjük, hogy a PowerAndGo e-carsharinget választottad!</p>

                <!-- "Keret" kezdete -->
                <div class=" border-solid border-4 rounded-md border-lime-600 p-3 my-4">
                    <p class="my-3"> A(z) <b>{{ rent.plate }}</b> rendszámú PowerAndGo bérlését <b>{{
                        rent.rent_start }}</b>
                        -kor kezdted
                        és
                        <b>{{ rent.rent_close }}</b>
                        -kor
                        fejezted be.
                    </p>
                    <p class="my-3">A bérlésed során 0000 hosszabbítás volt a foglalásodon,
                        <b>{{ rent.vezetesi_idotartam }}</b> , ami alatt <b> {{ rent.distance }} km-t</b> tettél meg
                        és
                        <b>{{ formatTime(rent.driving_minutes) }}</b> vezettél, illetve
                        <b> {{ formatTime(rent.parking_minutes) }}</b> parkoltál.
                        <!-- Ezt majd arra tudod használni, hogy a bérlés végösszegnél ha rendelkezésre áll elég kredit, akkor 
                         Interakcióban a felhasználóval megnézzük, hogy CREDIT / BÓNUSZPERC jóváírást kér(t)-e
                         Ha igen, azzal csökkentjük a Bills végösszegét,
                         Itt meg kap egy TRUE értéket. -->

                    <p v-if="rent.usedCredits">
                        <br>A bérléshez az alábbi kuponokat, krediteket váltottad be:
                    </p>
                    <p v-else>
                        A bérléshez nem használtál fel <b>bónuszpercet</b>, vagy <b>kreditet</b>. A bérléseid során
                        felhasználható és gyűjthető bónuszpercekről bővebben a <router-link to="/bonuszperc"
                            class="underline underline-offset-4 text-lime-500"><b>bónusz-percek</b></router-link>
                        oldalon
                        tudsz tájékozódni.
                    </p>
                    </p>

                    <p>A bérlésed díját, <b>{{ rent.total_cost }} </b> Ft-ot, hamarosan kiszámlázzuk neked. A
                        részletes díjszámítást és az autó nyitása során készített képeidet tartalmazó
                        bérlésösszesítő
                        dokumentumot <router-link to="/bonuszperc"
                            class="underline underline-offset-4 text-lime-500">innen</router-link> tudod
                        letölteni.
                    </p>
                </div>

                <!-- Töltött / Nem töltött dinamikusan! -->
                <p v-if="rent.charged_kw > 1" class="mt-10">
                    Bérlésed alatt <b>{{ rent.charged_kw }} kWh-t</b> töltöttél az autóba más szolgáltató
                    töltőoszlopán,
                    ezért <b>{{ rent.credits }} Ft</b> értékű PowerAndGo csomagot szereztél.
                    A töltésért járó csomagokról bővebben a <router-link to="/kreditek"
                        class="underline underline-offset-4 text-lime-500"><b>kreditek</b></router-link> oldalon
                    tudsz tájékozódni.
                </p>
                <p v-else>
                    Bérlésed alatt <b>{{ rent.charged_kw }} kWh-t</b> töltöttél az autóba más szolgáltató
                    töltőoszlopán,
                    ezért most lemaradtál a töltések után megszerezhető kredit-csomagról.
                    A töltésért járó csomagokról bővebben
                    <router-link to="/ugyfelszolgalat" class="underline underline-offset-4 text-lime-500">
                        itt
                    </router-link> olvashatsz.
                </p>

                <p class="my-3 italic font-bold"> Reméljük, hogy élvezted a PowerAndGo-s utadat!</p>

                <p class="mb-10"> Ha hibát észleltél vagy ötleted, észrevételed van a szolgáltatásunkkal
                    kapcsolatban,
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
import BaseEmailLayout from '@layouts/BaseEmailLayout.vue';
import { http } from '@utils/http.mjs';

export default {
    components: {
        BaseLayout,
        BaseEmailLayout,
    },
    data() {
        return {
            rents: [],
            currentPage: 1,
            itemsPerPage: 5,
        }
    },
    async mounted() {
        try {
            const resp = await http.get('/bills/closedrentsbills');
            this.rents = resp.data.data;
        } catch (error) {
            console.error('Hiba történt az API hívás során:', error);
        }
    },
    computed: {
        totalPages() {
            return Math.ceil(this.rents.length / this.itemsPerPage);
        },

        paginatedRents() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.rents.slice(start, end);
        },
        visiblePages() {
            const start = Math.max(1, this.currentPage - 2);
            const end = Math.min(this.totalPages, this.currentPage + 2);
            return Array.from({ length: end - start + 1 }, (_, i) => start + i);
        }
    },
    methods: {
        formatTime(minutes) {
            if (!minutes || minutes < 1) return '0 perc';
            const hours = Math.floor(minutes / 60);
            const mins = minutes % 60;
            return hours > 0 ? `${hours} óra ${mins} percet` : `${mins} percet`;
        },
        isCharged(kw) {
            return kw > 1 ? `ezért ${rent.credits} Ft értékű
                    PowerAndGo csomagot szereztél.`: 'ezért most lemaradtál a töltések után megszerezhető kredit-csomagról.A töltésért járó csomagokról bővebben';
        },
        changePage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
            }
        }
    }
}
</script>