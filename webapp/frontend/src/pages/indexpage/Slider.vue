<template>
  <div
    class="relative min-h-[500px] xl:min-h-[100svh] justify-center items-center h-full w-full border-b-4 border-emerald-800/75 md:pt-20 pb-14">

    <div class="absolute inset-0">
      <div class="absolute inset-0 bg-gradient-to-tr from-slate-900 via-slate-800 to-emerald-900/30"></div>
      <div class="absolute right-0 w-1/4 h-full bg-emerald-500/5 blur-3xl"></div>
      <div class="absolute -left-1/4 top-1/4 w-1/5 h-2/5 bg-emerald-700/10 blur-3xl rounded-full"></div>
    </div>

    <div class="relative z-10 h-full w-full">
      <div class="max-w-[95%] mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
          <div class="space-y-8">
            <div class="space-y-4">
              <h2 class="text-4xl md:text-5xl font-bold text-white leading-tight">
                Új lendületre <span class="text-emerald-400">vágysz?</span>
              </h2>

              <div class="relative">
                <h3 class="text-2xl md:text-3xl font-bold text-emerald-300 pb-3">
                  A KIA Niro Megadja!
                </h3>
                <div class="absolute bottom-0 left-0 w-32 h-1 bg-gradient-to-r from-emerald-500 to-emerald-300"></div>
              </div>

              <p class="text-slate-300 text-lg md:text-xl leading-relaxed">
                Fedezd fel a vezetés új élményét és élvezd az elektromos autózás kényelmét már ma!
              </p>
            </div>

            <div class="mt-6 space-y-5">
              <router-link to="">
                <div class="w-fit bg-emerald-600 border-2 border-emerald-100/35 hover:bg-emerald-500 text-white px-8 py-3 rounded-xl
                         shadow-lg transition-colors duration-200">
                  <i class="fa-solid fa-car mr-3 transition-transform group-hover:translate-x-1 text-green-200"></i>
                  <span class="font-semibold">Tovább a bérléshez</span>
                </div>
              </router-link>

              <div>
                <router-link to="">
                  <div class="w-fit bg-slate-600 border-2 border-emerald-100/35 hover:bg-slate-500 text-white px-8 py-3 rounded-xl
                         shadow-lg transition-colors duration-200">
                    <i class="fa-solid fa-user mr-3 transition-transform group-hover:translate-x-1"></i>
                    <span class="font-semibold ">Regisztrálok</span>
                  </div>
                </router-link>
              </div>
            </div>

          </div>

          <div class="relative flex justify-center items-center">
            <div class="absolute inset-0 bg-gradient-to-b from-emerald-500/10 to-transparent blur-2xl"></div>
            <img src="http://backend.vm1.test/storage/carsImages/newniro.webp" alt="KIA Niro EV"
              class="relative w-full h-auto max-h-[550px] max-w-[600px] object-contain animate-float drop-shadow-[0_35px_35px_rgba(16,185,129,0.15)]" />
          </div>
        </div>

        <div class="xl:pt-20">
          <CTASections />
        </div>


      </div>
    </div>
  </div>
</template>

<script setup>
import CTASections from './CTASections.vue';
</script>

<style scoped>
@keyframes float {

  0%,
  100% {
    transform: translateY(0);
  }

  50% {
    transform: translateY(-10px);
  }
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}

@media (max-width: 768px) {
  .animate-float {
    animation: none;
  }
}
</style>
