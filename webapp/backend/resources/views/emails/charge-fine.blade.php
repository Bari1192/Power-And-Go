<div
    style="font-family:cursive;margin:auto;font-weight: 300;min-height: 100vh; width: 100%; background-color: rgb(0, 32, 74); padding: 4rem 0;">
    <div style="width: 100%; max-width: 1200px; margin: auto; position: relative; padding: 0 20px;">
        <div
            style="width: 100%; margin-bottom: -80px; margin-right:auto;margin-left:auto; position: relative; z-index: 5;">
            <img src={{ asset('storage/email/email_logo_PWG.png') }} style="width: 100%;" alt="PowerAndGo Logo">
        </div>
        <div
            style="width: 75%; font-size: 1.2rem; margin: -20px auto 0; padding: 24px 40px; background-color: rgb(4, 61, 104); border: 8px solid rgb(12, 74, 110); border-radius: 12px; text-align: justify; color: white; position: relative;">
            <p style="margin-bottom: 12px; font-size: 1.4rem; color: rgb(163, 230, 53); font-weight: bold;">Kedves
                {{ $lastname }}!</p>

            <div class="border-solid border-4 rounded-md border-lime-600 p-5 my-4">
                <p class="my-3 leading-7">
                    <strong>Tájékoztatunk</strong>, hogy a(z) <strong>{{ $carPlate }}</strong> rendszámú
                    <strong>{{ $rentStart }}</strong>-kor megkezdett és
                    <strong>{{ $rentClose }}</strong>-kor lezárt PowerAndGo bérlésed során az
                    <i>Általános Szerződési Feltétek 8.3.5.</i> részében foglaltak szerint -
                    <i> az az akkumulátor alultöltöttsége okán keletkezett Szállítási Költségre hivatkozva</i>
                    <strong>kötbér megállapítására került sor</strong>.
                </p>

                <p class="my-3">
                    <strong><u>Töltöttségi állapot részletező:</u></strong>
                </p>

                <p>
                    Az autó lezárásakor megállapított töltöttségi szintje:
                    <strong>{{ $charge }} %</strong> volt.
                </p>

                <p>
                    A korábbiakban hivatkozott jármű esetében az ASZF előírása szerint a minimális töltöttségi
                    szintnek a zárás pillanatában legalább
                    <strong>{{ $minCharge }} %-nak</strong> kell lennie.
                </p>

                <p class="my-3">
                    Mivel a <strong>tényleges töltöttségi szint alacsonyabb</strong> az előíráshoz képest, ezért az
                    ASZF szerint eljárva
                    a gépjármű <u>elszállítását rendeltük elő</u>. Ennek költségeit az alábbiakban részletezzük:
                </p>

                <p>
                    <strong>Büntetési díj indoklása (ÁSZF szerint):</strong>
                </p>

                <ul>
                    <li class="ml-3 py-1">🛻 Szállítási Költség:
                        {{ number_format($shippingCost, 0, ',', ' ') }} Ft</li>
                    <li class="ml-3 py-1">👮🏻‍♂️ Kiszállási Díj:
                        {{ number_format($dispatchCost, 0, ',', ' ') }} Ft</li>
                    <li class="ml-3 py-1">📝 Adminisztrációs díj:
                        {{ number_format($adminCost, 0, ',', ' ') }} Ft</li>
                </ul>

                <p class="mt-2">
                    Ezen tételek alapján került kiszámításra a csatolt <strong>büntetési díj</strong> mértéke:
                    <strong>{{ number_format($cost, 0, ',', ' ') }} Ft</strong>.
                </p>
            </div>

            <p class="my-2">
                A csatolt dokumentumokat
                <a href="https://powerandgo.com/berlesek/1"
                    style="text-decoration: underline; color: #84cc16;">innen</a>
                éred el, amelyek részletesen tartalmazzák a büntetési számla számítását és indoklását. Amennyiben az
                egyenlegén rendelkezésre áll a szükséges összeg, azt levonjuk az egyenlegéből.
            </p>

            <p class="mb-10">Kérdésed, észrevételed vagy panasz esetén kérjük, lépj kapcsolatba velünk az <a
                    href="mailto:ugyfelszolgalat@powerandgo.com"
                    style="text-decoration: underline; color: #84cc16; font-style: italic;">ugyfelszolgalat@powerandgo.com</a>
                email címen, vagy keressen bennünket bizalommal elérhetőségeinken!</p>

            <p>Köszönettel,</p>
            <p class="font-bold">PowerAndGo csapata</p>
            <p class="my-3 font-bold italic">Élmények. Neked. Nekünk. Tisztán.</p>
        </div>
    </div>
