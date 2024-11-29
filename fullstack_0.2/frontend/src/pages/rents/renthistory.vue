<template>
    <BaseLayout>

        <div class="container" v-for="rent in rents" :key="rent.lezart_berles_id">
            <div class="col">
                <img src="@assets/img/BaseEmail/pelda_logo.png" alt="">

            </div>
            <div class="col text-white">
                <p class="my-3"><b>Kedves {{ rent.szemely_knev }}!</b></p>
                <p>Köszönjük, hogy a <b>PowerAndGo</b> e-carsharinget választottad!</p>
                <p> A(z) <b>{{ rent.auto_rendszam }}</b> rendszámú PowerAndGo bérlését <b>{{ rent.berles_kezdete }}</b>
                    -kor kezdted
                    és
                    <b>{{ rent.berles_vege }}</b>
                    -kor
                    fejezted be.
                </p>
                <p>A bérlésed során 0h 00' hosszabbítás volt a foglalásodon,<b>{{ rent.berles_percek }}</b> vezettél, ami
                    alatt
                    {{
                        rent.megtett_tavolsag }}
                    km-t
                    tettél meg, és a vezetés mellett 0h 46'-et parkoltál. A bérléshez felhasznált bónusz percek: 0h
                    00'.
                    A bérléshez az alábbi kuponokat váltottad be:</p>

                <p>A bérlésed díját, 1,833 Ft-ot, hamarosan kiszámlázzuk neked. A részletes díjtételeket és az autó
                    értékelése során készített képeidet tartalmazó bérlés összesítő dokumentumot innen tudod
                    letölteni.
                </p>

                <p> Bérlésed alatt 16 kWh-t töltöttél az autóba más szolgáltató töltőoszlopán, ezért 4,200 Ft értékű
                    PowerAndGo csomagot szereztél. A töltésért járó csomagokról bővebben itt olvashatsz.</p>
            </div>
        </div>

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
                <tr v-for="rent in rents" :key="rent.lezart_berles_id"
                    class=" text-white cursor-pointer odd:hover:bg-sky-900 even:hover:bg-sky-800 odd:bg-sky-500  even:bg-sky-600">
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
            rents: []
        }
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
