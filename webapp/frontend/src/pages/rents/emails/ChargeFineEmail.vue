<template>
    <div class="container mx-auto">
        <div class="col w-3/4 -mb-10 z-5 mx-auto">
            <img src="@assets/img/BaseEmail/email_logo_PWG.png" class="opacity-80">
        </div>
        <div
            class="col text-white bg-sky-800 bg-opacity-50 border-8 rounded-xl border-sky-900 py-6 px-10 w-3/4 mx-auto text-justify">
            <p class="mb-3 text-xl text-lime-400 font-bold">Kedves {{ fee.person }}!</p>
            <div class="border-solid border-4 rounded-md border-lime-600 p-5 my-4">
                <p class="my-3 leading-7">
                    <b>Tájékoztatunk</b>, hogy a(z) <b>{{ fee.plate }}</b> rendszámú <b>{{ fee.rent_start }}</b>-kor
                    megkezdett és
                    <b>{{ fee.rent_close }}</b>-kor lezárt PowerAndGo bérlésed során az <i>Általános Szerződési
                        Feltétek 8.3.5.</i>
                    részében foglaltak szerint - <i> az az akkumulátor alultöltöttsége okán keletkezett Szállítási
                        Költségre
                        hivatkozva</i> <b>kötbér
                        megállapítására került sor</b>.
                </p>

                <p class="my-3"> <b><u>Töltöttségi állapot részletező:</u></b></p>
                <p>Az autó lezárásakor megállapított töltöttségi százalék szintje: <b>{{ fee.end_percent }} %</b>
                    volt.</p>
                <p>A korábbiakban hivatkozott jármű esetében Az ASZF előírása szerint a minimális töltöttségi szintnek a
                    zárás pillanatában legalább
                    <b>{{ chargingCategories[fee.category].min_toltes }} %-nak</b>
                    kell lennie.
                </p>

                <p class="my-3">
                    Mivel a <b>tényleges töltöttségi szint alacsonyabb</b> az előíráshoz képest, ezért az ASZF.-nek
                    megfelelően
                    eljárva a gépjármű <u>elszállítását rendeltük elő</u>. Ennek költségeit az alábbiakban részletezzük:
                </p>

                <p><b>Büntetési díj indoklása (ÁSZF szerint):</b></p>
                <ul>
                    <li class="ml-3 py-1">🛻 Szállítási Költség: 100.000 Ft</li>
                    <li class="ml-3 py-1">👮🏻‍♂️ Kiszállási Díj: 8.000 Ft</li>
                    <li class="ml-3 py-1">📝 Adminisztrációs díj: 5.000 Ft</li>
                </ul>
                <p class="mt-2">Ezen tételek alapján került kiszámításra a csatolt <b>büntetési díj</b> mértéke.</p>

            </div>
            <p class="my-2">A csatolt dokumentumokat <a :href="`https://powerandgo.com/berlesek/${fee.id}`"
                    style="text-decoration: underline; color: #84cc16;">innen</a> éred el, amelyek
                részletesen tartalmazzák a büntetési számla számítását és indoklását.
                Amennyiben az egyenlegén rendelkezésre áll a szükséges összeg, az összeget
                levonjuk az egyenlegéből.</p>
            <p class="mb-10">Kérdésed, észrevételed vagy panasz esetén kérjük, lépj kapcsolatba velünk az <a
                    href="mailto:ugyfelszolgalat@powerandgo.com"
                    style="text-decoration: underline; color: #84cc16; font-style: italic;">ugyfelszolgalat@powerandgo.com</a>
                email címen, vagy keressen bennünket bizalommal elérhetőségeinken!</p>

            <p>Köszönettel,</p>
            <p class="font-bold">PowerAndGo csapata</p>
            <p class="my-3 font-bold italic">Élmények. Neked. Nekünk. Tisztán.</p>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ChargeFineEmail',
    data() {
        return {
            chargingCategories: {
                1: { min_toltes: 9.0 },
                2: { min_toltes: 6.0 },
                3: { min_toltes: 4.5 },
                4: { min_toltes: 4.0 },
                5: { min_toltes: 4.0 }
            }
        }
    },
    props: {
        fee: {
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