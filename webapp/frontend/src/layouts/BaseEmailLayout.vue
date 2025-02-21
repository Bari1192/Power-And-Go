// EmailTemplate.vue
<template>
    <div class="container mx-auto">
        <div class="col w-3/4 -mb-10 z-5 mx-auto">
            <img src="https://powerandgo.com/assets/img/email_logo_PWG.png" class="opacity-80">
        </div>
        <div
            class="col text-white bg-sky-800 bg-opacity-50 border-8 rounded-xl border-sky-900 py-6 px-10 w-3/4 mx-auto text-justify">
            <p class="mb-3 text-xl text-lime-400 font-bold">Kedves {{ rent.person }}!</p>
            <p class="my-3 font-bold">Köszönjük, hogy a PowerAndGo e-carsharinget választottad!</p>
            <div class="border-solid border-4 rounded-md border-lime-600 p-3 my-4">
                <p class="my-3">A(z) <b>{{ rent.plate }}</b> rendszámú PowerAndGo bérlését <b>{{ rent.rent_start
                        }}</b>-kor kezdted és <b>{{ rent.rent_close }}</b>-kor fejezted be.</p>
                <p class="my-3">A bérlésed során 0000 hosszabbítás volt a foglalásodon,
                    <b>{{ rent.vezetesi_idotartam }}</b>, ami alatt <b>{{ rent.distance }} km-t</b> tettél meg és
                    <b>{{ formatTime(rent.driving_minutes) }}</b> vezettél, illetve
                    <b>{{ formatTime(rent.parking_minutes) }}</b> parkoltál.
                </p>
                <p v-if="rent.usedCredits">
                    <br>A bérléshez az alábbi kuponokat, krediteket váltottad be:
                </p>
                <p v-else>
                    A bérléshez nem használtál fel <b>bónuszpercet</b>, vagy <b>kreditet</b>. A bérléseid során
                    felhasználható és gyűjthető bónuszpercekről bővebben a bónusz-percek oldalon tudsz tájékozódni.
                </p>
                <p>A bérlésed díját, <b>{{ rent.total_cost }}</b> Ft-ot, hamarosan kiszámlázzuk neked. A
                    részletes díjszámítást és az autó nyitása során készített képeidet tartalmazó
                    bérlésösszesítő dokumentumot <a :href="`https://powerandgo.com/berlesek/${rent.id}`"
                        style="text-decoration: underline; color: #84cc16;">innen</a> tudod letölteni.</p>
            </div>
            <p v-if="rent.charged_kw > 1" class="mt-10">
                Bérlésed alatt <b>{{ rent.charged_kw }} kWh-t</b> töltöttél az autóba más szolgáltató
                töltőoszlopán, ezért <b>{{ rent.credits }} Ft</b> értékű PowerAndGo csomagot szereztél.
                A töltésért járó csomagokról bővebben a <a href="https://powerandgo.com/kreditek"
                    style="text-decoration: underline; color: #84cc16;"><b>kreditek</b></a> oldalon
                tudsz tájékozódni.
            </p>
            <p v-else>
                Bérlésed alatt <b>{{ rent.charged_kw }} kWh-t</b> töltöttél az autóba más szolgáltató
                töltőoszlopán, ezért most lemaradtál a töltések után megszerezhető kredit-csomagról.
                A töltésért járó csomagokról bővebben <a href="https://powerandgo.com/ugyfelszolgalat"
                    style="text-decoration: underline; color: #84cc16;">itt</a> olvashatsz.
            </p>
            <p class="my-3 italic font-bold">Reméljük, hogy élvezted a PowerAndGo-s utadat!</p>
            <p class="mb-10">Ha hibát észleltél vagy ötleted, észrevételed van a szolgáltatásunkkal kapcsolatban,
                kérjük, jelezd nekünk az <a href="mailto:ugyfelszolgalat@powerandgo.com"
                    style="text-decoration: underline; color: #84cc16; font-style: italic;">ugyfelszolgalat@powerandgo.com</a>
                email címen!</p>
            <p>Köszönettel,</p>
            <p class="font-bold">PowerAndGo csapata</p>
            <p class="my-3 font-bold italic">Élmények. Neked. Nekünk. Tisztán.</p>
        </div>
    </div>
</template>

<script>
export default {
    name: 'BaseEmailLayout',
    props: {
        rent: {
            type: Object,
            required: true
        }
    },
    methods: {
        formatTime(minutes) {
            if (!minutes || minutes < 1) return '0 perc';
            const hours = Math.floor(minutes / 60);
            const mins = minutes % 60;
            return hours > 0 ? `${hours} óra ${mins} percet` : `${mins} percet`;
        }
    }
}
</script>