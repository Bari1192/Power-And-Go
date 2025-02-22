<div
    style="margin:auto;font-weight: 400;min-height: 100vh; width: 100%; background-color: rgb(0, 32, 74); padding: 4rem 0;">
    <div style="width: 100%; max-width: 1200px; margin: auto; position: relative; padding: 0 20px;">
        <div
            style="width: 100%; margin-bottom: -80px; margin-right:auto;margin-left:auto; position: relative; z-index: 5;">
            <img src={{ asset('storage/email/email_logo_PWG.png') }} style="width: 100%;" alt="PowerAndGo Logo">
        </div>
        <div
            style="width: 75%; font-size: 1.2rem; margin: -20px auto 0; padding: 24px 40px; background-color: rgb(4, 61, 104); border: 8px solid rgb(12, 74, 110); border-radius: 12px; text-align: justify; color: white; position: relative;">
            <p style="margin-bottom: 12px; font-size: 1.4rem; color: rgb(163, 230, 53); font-weight: bold;">Kedves
                {{ $lastname }}!</p>
            <p style="margin: 12px 0; font-weight: bold;">Köszönjük, hogy a <b>PowerAndGo-t</b> választottad!</p>

            <div style="border: 4px solid rgb(101, 163, 13); border-radius: 6px; padding: 12px; margin: 16px 0;">
                <p style="margin: auto;">
                    A(z) <b>{{ $carPlate }}</b> rendszámú PowerAndGo bérlésed
                    <b>{{ $rentStart }}</b>-kor kezdted és
                    <b>{{ $rentClose }}</b>-kor fejezted be.
                </p>
                <p style="margin:auto;">
                    A bérlésed összesen
                    <b>{{ \App\Providers\TimeFormatProvider::formatDuration($totalMinutes) }}ig</b> tartott,
                    @if (!$carPlate)
                        melyet <b>XXX perc</b> hosszabbítással kezdtél.
                    @else
                        amit a foglalás meghosszabbítása nélkül kezdtél.
                    @endif
                    Bérlésed során <b>{{ $distance }} km-t</b> tettél meg és
                    <b>{{ \App\Providers\TimeFormatProvider::formatDuration($driving) }}</b>
                    vezettél
                    @if (\App\Providers\TimeFormatProvider::formatDuration($parking) <= 0)
                        .
                    @else
                        ,illetve <b>{{ \App\Providers\TimeFormatProvider::formatDuration($parking) }}</b>
                        parkoltál.
                    @endif
                </p>
                @if ($usedCredits)
                    <p style="margin-top: 8px;">
                        <br>A bérléshez az alábbi kuponokat, krediteket váltottad be:
                    </p>
                @else
                    <p>
                        A bérléshez nem használtál fel <b>bónuszpercet</b>, vagy <b>kreditet</b>. A bérléseid során
                        felhasználható és gyűjthető bónuszpercekről bővebben a <a href="#"
                            style="text-decoration: underline; text-underline-offset: 4px; color: rgb(132, 204, 22);"><b>bónusz-percek</b></a>
                        oldalon
                        tudsz tájékozódni.
                    </p>
                @endif
                <p>
                    A bérlésed díját, <b>{{ $cost }}</b> Ft-ot, hamarosan kiszámlázzuk neked. A
                    részletes díjszámítást és az autó nyitása során készített képeidet tartalmazó
                    bérlésösszesítő dokumentumot <a href="#"
                        style="text-decoration: underline; text-underline-offset: 4px; color: rgb(132, 204, 22);">innen</a>
                    tudod letölteni.
                </p>
            </div>
            @if ($charge > 1)
                <p style="margin-top: 20px;">
                    Bérlésed alatt <b>{{ $charge }} kWh-t</b> töltöttél az autóba más szolgáltató
                    töltőoszlopán,
                    ezért <b>{{ $credits }} Ft</b> értékű PowerAndGo csomagot szereztél.
                    A töltésért járó csomagokról bővebben a <a href="#"
                        style="text-decoration: underline; text-underline-offset: 4px; color: rgb(132, 204, 22);"><b>kreditek</b></a>
                    oldalon tudsz
                    tájékozódni.
                </p>
            @else
                <p>
                    Bérlésed alatt <b>{{ $charge }} kWh-t</b> töltöttél az autóba más szolgáltató
                    töltőoszlopán,
                    ezért most lemaradtál a töltések után megszerezhető kredit-csomagról.
                    A töltésért járó csomagokról bővebben <a href="#"
                        style="text-decoration: underline; text-underline-offset: 4px; color: rgb(132, 204, 22);">itt</a>
                    olvashatsz.
                </p>
            @endif

            <p style="margin: 12px 0; font-style: italic; font-weight: bold;">Reméljük, hogy élvezted a PowerAndGo-s
                utadat!
            </p>

            <p style="margin-bottom: 40px;">
                Ha hibát észleltél vagy ötleted, észrevételed van a szolgáltatásunkkal kapcsolatban, kérjük, jelezd
                nekünk az
                <a href="#"
                    style="text-decoration: underline; text-underline-offset: 4px; color: rgb(132, 204, 22); font-style: italic;">ugyfelszolgalat@powerandgo.com</a>
                email címen!
            </p>

            <p>Köszönettel,</p>
            <p style="font-weight: bold;">PowerAndGo csapata</p>

            <p style="margin: 12px 0; font-weight: bold; font-style: italic;">Élmények. Neked. Nekünk. Tisztán.</p>
        </div>
    </div>
</div>
