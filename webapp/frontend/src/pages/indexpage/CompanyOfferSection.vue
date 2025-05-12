<template>

    <div
        class="min-h-[95dvh] w-full relative overflow-hidden bg-gradient-to-br from-slate-900 to-slate-800 py-20 border-b-4 border-emerald-800/75">
        <!-- Elmosódásos háttér-fények -->
        <div class="absolute inset-0">
            <div class="absolute right-0 w-1/3 h-full bg-emerald-500/5 blur-3xl"></div>
            <div class="absolute -left-1/4 top-1/4 w-1/2 h-4/5 bg-emerald-700/10 blur-3xl rounded-full"></div>
        </div>

        <div class="company-main mx-auto px-4 lg:px-8 relative">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div class="flex flex-col justify-between h-full">

                    <!-- Szöveges tartalom -->
                    <div class="space-y-8">
                        <div class="space-y-8">
                            <h2 class="text-4xl md:text-5xl font-bold text-white leading-tight">
                                Vállalati mobilitás
                                <span class="text-emerald-400">újragondolva</span>
                            </h2>
                            <p class="text-slate-300 text-lg md:text-xl leading-relaxed"
                                style="font-family: 'Nunito','Arial';">
                                Könnyítsd meg céged mindennapjait a <span class="text-emerald-400 font-semibold">Power
                                    And Go</span>
                                szolgáltatásával! Legyen szó kedvezmények cafeteriáról, vagy a vállalkozásod
                                flottájának
                                kiegészítéséről a Power And Go -ra mindig számíthatsz.
                            </p>
                        </div>

                        <!-- Előnyök lista -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 py-8" style="font-family: 'Nunito','Arial';">
                            <div class="flex items-start space-x-3">
                                <div
                                    class="flex-shrink-0 w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-emerald-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-white font-semibold mb-1 text-lg">
                                        Költséghatékony</h3>
                                    <p class="text-slate-400 text-lg">Akár 15-30% költséghatékonyabb megoldás lehet
                                        a
                                        hagyományos flottákkal
                                        szemben.</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <div
                                    class="flex-shrink-0 w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-emerald-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-white font-semibold mb-1 text-lg">
                                        Cafeteria elem</h3>
                                    <p class="text-slate-400 text-lg">Béren kívüli juttatásként elszámolható,
                                        mellyel a
                                        munkatársakat is felvillanyozhatod.</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <div
                                    class="flex-shrink-0 w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-emerald-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-white font-semibold mb-1 text-lg">Rugalmas számlázás</h3>
                                    <p class="text-slate-400 text-lg">Automatizált elszámolást és egyszerű
                                        adminisztrációt biztosítunk számodra.
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <div
                                    class="flex-shrink-0 w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-emerald-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-white font-semibold mb-1 text-lg">Flotta kedvezmények</h3>
                                    <p class="text-slate-400 text-lg">Egyedi árazással és kedvezményekkel
                                        igényelhető, magas
                                        volumenű használattal is.</p>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Szekció -->
                        <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-8 pt-6">

                            <!-- AD1 -->
                            <button class="bg-slate-600 border-2 border-emerald-700/55 hover:bg-slate-700 text-white px-8 py-4 rounded-xl
                         transform transition-all duration-300 hover:scale-105 shadow-lg">
                                <span class="font-semibold">Részletes tájékoztató</span>
                            </button>
                            <!-- AD2 -->
                            <button
                                class="bg-emerald-600 border-2 border-emerald-100/35 hover:bg-emerald-600/50 text-white px-8 py-4 rounded-xl
                         transform transition-all duration-300 hover:scale-105 shadow-lg flex items-center justify-center">
                                <span class="font-semibold">Ajánlatot kérek</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="relative hidden lg:flex items-end justify-center h-full lg:min-h-[65svh]">
                    <div class="absolute bottom-0 left-0 right-0">
                        <div class="w-full h-[550px] opacity-25 blur-3xl"
                            style="background: linear-gradient(90deg, #10B981 0%, #34D399 100%);">
                        </div>
                    </div>
                    <div class="relative w-full flex justify-center items-start">
                        <img src="http://backend.vm1.test/storage/carsImages/newniro.webp" alt="KIA Niro EV"
                            class="relative w-full h-auto max-h-[450px] max-w-[1000px] object-contain z-10" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.company-main{
    max-width: 85%;
    max-height: 90%;
}
</style>