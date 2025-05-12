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

      <div class="min-w-full border-b-4 border-gray-600/30 mb-6"></div>
      <div class="container mx-auto px-4 pt-12 pb-24 lg:min-h-[300px]">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full ">
          <router-link to="/">
            <div class="bg-emerald-600/60 lg:min-h-[150] lg:max-w-[450px] rounded-xl p-8 border border-slate-500/85 backdrop-blur-sm
               duration-200 ease-in lg:hover:translate-y-4 lg:transform hover:scale-105 shadow-lg shadow-slate-700"
              style="font-family: 'Nunito','Arial';">
              <h3 class="text-2xl font-bold text-white mb-2">🚀 3 perc és kész!</h3>
              <p class="text-slate-200 mb-2 lg:text-base">
                Töltsd le a <a href="/">PowerAndGo</a> App-unkat! A regisztrációdat a lehető leghamarabb visszajelezzük
                és már indulhatsz is. Ennyire egyszerű.
                Sőt! Most extra kedvezménnyel indulhatsz az első utadra!

              </p>
             <router-link to="/" class="text-emerald-50 bg-teal-600  border-2 border-emerald-100/35 
              hover:bg-teal-500 hover:text-white py-2 px-4 rounded-xl 
  mt-4 inline-flex shadow-lg text-base duration-200">
                <span>Megnézem</span>
              </router-link>
            </div>
          </router-link>

          <!-- Mennyiért? -->
          <router-link to="/">
            <div
              class="bg-emerald-600/60 lg:min-h-[150] lg:max-w-[450px] rounded-xl p-8 border border-slate-500/85 backdrop-blur-sm
                             duration-200 ease-in lg:hover:translate-y-4 lg:transform hover:scale-105 shadow-lg shadow-slate-700"
              style="font-family: 'Nunito','Arial';">
              <h3 class="text-2xl font-bold text-white mb-2">🌱 Zöld út a városban!</h3>
              <p class="text-slate-200 mb-2 lg:text-base">
                Pörgesd fel a városban töltött idődet környezettudatosan! Tiszta utazás, okos közlekedés - csatlakozz a
                változás éllovasaihoz! A Power And Go-val nem csak mész, hanem jobbá teszed Budapestet.
              </p>
              <router-link to="/" class=" text-emerald-50 bg-teal-600  border-2 border-emerald-100/35 
              hover:bg-teal-500 hover:text-white py-2 px-4 rounded-xl 
  mt-4 inline-flex shadow-lg  text-base duration-200">
                <div>Megnézem</div>
              </router-link>
            </div>
          </router-link>

          <!-- Hogyan? -->
          <router-link to="/">
            <div
              class="bg-emerald-600/60 lg:min-h-[150] lg:max-w-[450px] rounded-xl p-8 border border-slate-500/85 backdrop-blur-sm 
                             duration-200 ease-in lg:hover:translate-y-4 lg:transform hover:scale-105 shadow-lg shadow-slate-700"
              style="font-family: 'Nunito','Arial';">
              <h3 class="text-2xl font-bold text-white mb-2">💰 Spórolj okosan!</h3>
              <p class="text-slate-200 mb-2 lg:text-base">
                Válts kredit-csomagokra a havi utazásaidhoz is, extra kedvezményekkel!
                Minden kredit-csomag mellé extra pontokat írunk jóvá, melyek további levezethető perceket jelentenek
                számodra. <i>Ne maradj le!</i>
              </p>
              <router-link to="/" class="text-emerald-50 bg-teal-600  border-2 border-emerald-100/35 
              hover:bg-teal-500 hover:text-white py-2 px-4 rounded-xl 
  mt-4 inline-flex shadow-lg text-base duration-200">
                <span>Megnézem</span>
              </router-link>
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