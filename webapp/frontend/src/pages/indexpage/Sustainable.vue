<template>
    <div
        class="min-h-[95dvh] w-full relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 py-40
        border-b-4 border-emerald-800/75">
        <!-- Elmosódásos háttér-fények -->
        <div class="absolute inset-0">
            <div class="absolute left-0 w-1/3 h-full bg-emerald-700/15 blur-3xl"></div>
            <div class="absolute left-1/5 top-1/2 w-1/4 h-1/4 bg-emerald-300/30 blur-3xl rounded-full"></div>
        </div>

        <div class="company-main mx-auto px-4 lg:px-8 relative">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Bal oldali kép szekció -->
                <div class="relative hidden lg:flex items-end justify-center h-full lg:min-h-[65svh]">
                    <div class="absolute bottom-0 left-0 right-0">
                        <div class="w-full h-[550px] opacity-25 blur-3xl lg:translate-x-[-20%]"
                            style="background: linear-gradient(270deg, #10B981 0%, #34D399 100%);"></div>
                    </div>
                    <div class="relative w-full flex justify-center items-start">
                        <img src="../../assets/img/Models/skodacitigo.webp" alt="Elektromos autó"
                            class="relative w-full h-auto max-h-[450px] max-w-[1000px] lg:translate-x-[-20%]  object-contain z-10 scale-x-[-1]" />
                    </div>
                </div>

                <!-- Jobb oldali szöveges tartalom -->
                <div class="flex flex-col justify-between h-full">
                    <!-- Főcím és szöveg -->
                    <div class="space-y-8">
                        <div class="space-y-8">
                            <h2 class="text-4xl md:text-5xl font-bold text-white leading-tight">
                                Válaszd az autózás <span class="text-emerald-400">fenntartható oldalát!</span>
                            </h2>
                            <p class="text-slate-300 text-lg md:text-xl leading-relaxed"
                                style="font-family: 'Nunito', 'Arial';">
                                Több, mint 500 elektromos autó vár Budapest-szerte. Csatlakozz a jövő közlekedéséhez!
                            </p>
                        </div>

                        <!-- Előnyök grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 py-8" style="font-family: 'Nunito', 'Arial';">
                            <!-- Ingyenes Parkolás -->
                            <div class="flex items-start space-x-3">
                                <div
                                    class="flex-shrink-0 w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                                    <i class="fa-solid fa-square-parking text-2xl text-emerald-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-white font-semibold mb-1 text-lg">Ingyenes Parkolás</h3>
                                    <p class="text-slate-400 text-lg">Díjmentes parkolás minden budapesti zónában.</p>
                                </div>
                            </div>

                            <!-- Egyszerű Használat -->
                            <div class="flex items-start space-x-3">
                                <div
                                    class="flex-shrink-0 w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                                    <i class="fa-solid fa-mobile-screen-button text-2xl text-emerald-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-white font-semibold mb-1 text-lg">Egyszerű Használat</h3>
                                    <p class="text-slate-400 text-lg">Válassz, nyisd ki, indulj - ennyire egyszerű!</p>
                                </div>
                            </div>

                            <!-- Rugalmas Bérlés -->
                            <div class="flex items-start space-x-3">
                                <div
                                    class="flex-shrink-0 w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                                    <i class="fa-solid fa-shuffle text-2xl text-emerald-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-white font-semibold mb-1 text-lg">Rugalmas Bérlés</h3>
                                    <p class="text-slate-400 text-lg">Percdíjas elszámolás, minimális kötöttség.</p>
                                </div>
                            </div>

                            <!-- Biztosítás -->
                            <div class="flex items-start space-x-3">
                                <div
                                    class="flex-shrink-0 w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                                    <i class="fa-solid fa-user-shield text-2xl text-emerald-400"></i>
                                </div>
                                <div>
                                    <h3 class="text-white font-semibold mb-1 text-lg">Teljes Biztonság</h3>
                                    <p class="text-slate-400 text-lg">Választható teljes körű biztosítás minden útra.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Szekció -->
                        <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-8 pt-6">
                            <button
                                class="bg-slate-600 border-2 border-emerald-700/55 hover:bg-slate-700 text-white px-8 py-4 rounded-xl transform transition-all duration-300 hover:scale-105 shadow-lg">
                                <span class="font-semibold">Részletes tájékoztató</span>
                            </button>
                            <button
                                class="bg-emerald-600 border-2 border-emerald-100/35 hover:bg-emerald-600/50 text-white px-8 py-4 rounded-xl transform transition-all duration-300 hover:scale-105 shadow-lg flex items-center justify-center">
                                <span class="font-semibold">Regisztrálok</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.company-main {
    max-width: 85%;
    max-height: 90%;
}
</style>
