<template>
  <section class="registration-section relative overflow-hidden bg-gradient-to-br from-emerald-800 to-emerald-900 py-16">
    <!-- Háttér minta -->
    <div class="absolute inset-0 grid grid-cols-4 md:grid-cols-6 gap-4 opacity-20">
      <div v-for="n in 24" :key="n" 
           class="h-32 w-32 rounded-full bg-emerald-500/30 transform rotate-45"></div>
    </div>
    
    <!-- Fő tartalom -->
    <div class="container mx-auto px-6 relative">
      <div class="flex flex-col md:flex-row items-center justify-between gap-12">
        
        <!-- Bal oldali szöveg szekció -->
        <div class="w-full md:w-1/2 space-y-6 text-white">
          <h2 class="text-4xl md:text-5xl font-bold leading-tight">
            Válaszd az autózás 
            <span class="text-emerald-400 inline-block transform hover:scale-105 transition-transform duration-300">
              jó oldalát!
            </span>
          </h2>
          
          <p class="text-xl text-emerald-100/90">
            500 elektromos autó vár Budapest-szerte.
          </p>
          
          <div class="flex flex-wrap gap-4 pt-4">
            <a href="/register" 
               class="inline-flex items-center px-8 py-3 bg-emerald-500 hover:bg-emerald-400 
                      text-white font-semibold rounded-xl transform hover:scale-105 
                      transition-all duration-300 shadow-lg hover:shadow-emerald-500/50">
              <span>REGISZTRÁLOK</span>
              <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M13 7l5 5m0 0l-5 5m5-5H6" />
              </svg>
            </a>
          </div>
          
          <!-- Ikonos előnyök -->
          <div class="grid grid-cols-2 gap-4 pt-8">
            <div class="flex items-center space-x-3">
              <div class="p-2 bg-emerald-500/20 rounded-lg">
                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
              </div>
              <span class="text-sm text-emerald-100">Gyors regisztráció</span>
            </div>
            <div class="flex items-center space-x-3">
              <div class="p-2 bg-emerald-500/20 rounded-lg">
                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <span class="text-sm text-emerald-100">0-24 órás elérhetőség</span>
            </div>
          </div>
        </div>
        
        <!-- Jobb oldali autó kép -->
        <div class="w-full md:w-1/2 relative">
          <img 
            src="http://backend.vm1.test/storage/carsImages/eupmodel.png"
            alt="Elektromos VW e-up!"
            class="w-full max-w-lg mx-auto transform hover:scale-105 transition-all duration-500
                   drop-shadow-[0_0_25px_rgba(16,185,129,0.3)] animate-float"
          />
          
          <!-- Dekoratív ikonok -->
          <div class="absolute top-0 right-0 p-4">
            <div class="bg-emerald-500/20 p-3 rounded-full">
              <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 13l4 4L19 7" />
              </svg>
            </div>
          </div>
        </div>
        
      </div>
    </div>
  </section>
</template>

<style scoped>
@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}
</style>
