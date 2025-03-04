    <div class="container mx-auto">
        <div class="col w-3/4 -mb-10 z-5 mx-auto">
            <img src="@assets/img/BaseEmail/email_logo_PWG.png" class="opacity-80">
        </div>
        <div
            class="col text-white bg-sky-800 bg-opacity-50 border-8 rounded-xl border-sky-900 py-6 px-10 w-3/4 mx-auto text-justify">
            <p class="mb-3 text-xl text-lime-400 font-bold">Kedves {{ fee . person }}!</p>
            <div class="border-solid border-4 rounded-md border-lime-600 p-5 my-4">
                <p class="my-3 leading-7">
                <p>Tájékoztatunk, hogy az eddigi kitartó munkádnak és gurulásaidnak köszönhetően összesen
                    <strong>{{ $bonusMinutes }} </strong> bónusz-percet gyűjtöttél össze.
                    Ne hagyd elveszni a kemény munkád gyümölcsét és használd ki a díjmentes suhanás örömét a
                    bónusz-perceiddel
                </p>

                <p>A bónusz perceid legkésőbb <strong>{{ $expirationDate }}-kor tudod felhasználni, utána elveszíted
                        őket.</strong></p>

                <p>Használd fel mihamarabb, vagy szerezz további bónusz-perceket még a lejárat előtt, hogy
                    meghosszabbíthasd a felhasználásukat!</p>


                <p class="mb-10">Kérdésed, észrevételed vagy panasz esetén kérjük, lépj kapcsolatba velünk az <a
                        href="mailto:ugyfelszolgalat@powerandgo.com"
                        style="text-decoration: underline; color: #84cc16; font-style: italic;">ugyfelszolgalat@powerandgo.com</a>
                    email címen, vagy keressen bennünket bizalommal elérhetőségeinken!</p>

                <p>Köszönettel,</p>
                <p class="font-bold">PowerAndGo csapata</p>
                <p class="my-3 font-bold italic">Élmények. Neked. Nekünk. Tisztán.</p>
            </div>
        </div>
