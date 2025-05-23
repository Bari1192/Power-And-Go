<template>
  <div class="zero-main border-b-4 border-slate-500">



    <div class="min-h-screen bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900">

      <!-- Vivaro Container Section -->
      <section class="relative min-h-[40vh] md:min-h-[70vh] py-12 flex items-center justify-center">
        <div class="absolute inset-0 z-0">
          <img src="http://backend.vm1.test/storage/carsImages/newvivaro.png" alt="Elektromos autó töltés közben"
            class="w-full h-full object-contain opacity-25" />
        </div>
        <div class="block z-10 container m-auto w-full px-6 text-center">
          <div class="text-5xl md:text-7xl font-bold bg 
                           bg-clip-text text-transparent bg-gradient-to-r 
                           from-sky-300 via-emerald-500 to-green-300 mx-auto w-fit px-8 tracking-wide">
            <p>Zéró Emisszió</p>
            <p class="pb-4">Korlátlan Szabadság</p>
          </div>
        </div>
      </section>

      <!-- 3 perc -->
      <div class="min-w-full border-b-4 border-gray-600/30 mb-6"></div>
      <div class="container mx-auto px-4 pt-12 pb-24">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
          <router-link to="/">
            <div class="bg-clip-text text-transparent bg-gradient-to-b from-sky-200 via-emerald-400 to-green-200
              rounded-xl p-8 border-2 border-slate-300/40 backdrop-blur-sm duration-200 ease-in 
              lg:hover:translate-y-4 lg:transform hover:scale-105 shadow-lg shadow-slate-700 h-full flex flex-col"
              style="font-family: 'Nunito','Arial';">
              <h3 class="text-2xl font-bold text-white mb-2">🚀 3 perc és kész!</h3>
              <p class="mb-2 lg:text-lg font-semibold flex-grow">
                Töltsd le a <a href="/">PowerAndGo</a> App-unkat! A regisztrációdat a lehető leghamarabb visszajelezzük
                és már indulhatsz is. Ennyire egyszerű.
                Sőt! Most extra kedvezménnyel indulhatsz az első utadra!
              </p>
              <div class="mt-4 w-full">
                <button class="w-full bg-emerald-600 hover:bg-emerald-500 text-white text-sm md:text-base xl:text-lg py-2 px-4 rounded-xl
           transition-colors duration-200 border-2 border-emerald-100/35">
                  Megnézem
                </button>
              </div>
            </div>
          </router-link>

          <!-- Zöld út a városban! -->
          <router-link to="/">
            <div
              class="bg-clip-text text-transparent bg-gradient-to-b 
                           from-sky-200 via-emerald-400 to-green-200 rounded-xl p-8 border-2 border-slate-300/40 backdrop-blur-sm
                             duration-200 ease-in lg:hover:translate-y-4 lg:transform hover:scale-105 shadow-lg shadow-slate-700 h-full flex flex-col"
              style="font-family: 'Nunito','Arial';">
              <h3 class="text-2xl font-bold text-slate-200 mb-2">🌱 Zöld út a városban!</h3>
              <p class="mb-2 lg:text-lg font-semibold flex-grow">
                Pörgesd fel a városban töltött idődet környezettudatosan! Tiszta utazás, okos közlekedés - csatlakozz a
                változás éllovasaihoz! A Power And Go-val nem csak mész, hanem jobbá teszed Budapestet.
              </p>
              <div class="mt-4 w-full">
                <button class="w-full bg-emerald-600 hover:bg-emerald-500 text-white text-sm md:text-base xl:text-lg py-2 px-4 rounded-xl
           transition-colors duration-200 border-2 border-emerald-100/35">
                  Megnézem
                </button>
              </div>
            </div>
          </router-link>

          <!-- Hogyan? -->
          <router-link to="/">
            <div
              class="bg-clip-text text-transparent bg-gradient-to-b 
         from-sky-200 via-emerald-400 to-green-200 rounded-xl p-8 border-2 border-slate-300/40 backdrop-blur-sm
         duration-200 ease-in lg:hover:translate-y-4 lg:transform hover:scale-105 shadow-lg shadow-slate-700 h-full flex flex-col"
              style="font-family: 'Nunito','Arial';">
              <h3 class="text-2xl font-bold text-white mb-2">💰 Spórolj okosan!</h3>
              <p class="mb-2 lg:text-lg font-semibold flex-grow">
                Válts kredit-csomagokra a havi utazásaidhoz is, extra kedvezményekkel!
                Minden kredit-csomag mellé extra pontokat írunk jóvá, melyek további levezethető perceket jelentenek
                számodra. <i>Ne maradj le!</i>
              </p>
              <div class="mt-4 w-full">
                <button class="w-full bg-emerald-600 hover:bg-emerald-500 text-white text-sm md:text-base xl:text-lg py-2 px-4 rounded-xl
           transition-colors duration-200 border-2 border-emerald-100/35">
                  Megnézem
                </button>
              </div>
            </div>
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.zero-main {
  background: url('@img/Welcome/blocks3.webp');
  background-position: center;
  background-repeat: repeat-y;
  background-size: cover;
  min-width: 100%;
  min-height: 100%;
}

.bg-gradient-overlay {
  background: linear-gradient(to bottom,
      rgba(15, 23, 42, 0.9),
      rgba(15, 23, 42, 0.7));
}
</style>