<template>
    <div class="w-full px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8 max-w-[1440px] xl:max-w-[1920px] mx-auto
    mt-12" style="font-family: 'Nunito','Arial';">
            <!-- Miért? -->
            <div class="group flex flex-col bg-slate-900/70 backdrop-blur-sm rounded-xl p-6 min-h-[200px] border-2 border-slate-300/40">
                <div class="flex items-center gap-3 mb-3">
                    <i class="fa-solid fa-leaf text-xl text-emerald-400"></i>
                    <h3 class="text-lg font-bold text-white">Miért?</h3>
                </div>
                <p class="text-slate-300 text-sm md:text-base xl:text-lg mb-4 flex-grow">
                    Tiszta út a jövőbe! A Power And Go nem csak közlekedési megoldás – közösen építünk egy élhetőbb
                    Budapestet.
                </p>
                <button
                    class="w-1/3 mx-auto md:ml-0 md:w-full bg-emerald-600 hover:bg-emerald-500 text-white text-sm md:text-base xl:text-lg py-2 rounded-lg transition-colors duration-200">
                    Tudj meg többet
                </button>
            </div>

            <!-- Mennyiért? -->
            <div class="group flex flex-col bg-slate-900/70 backdrop-blur-sm rounded-xl p-6 min-h-[200px] border-2 border-slate-300/40">
                <div class="flex items-center gap-3 mb-3">
                    <i class="fa-solid fa-wallet text-xl text-emerald-400"></i>
                    <h3 class="text-lg font-bold text-white">Mennyiért?</h3>
                </div>
                <p class="text-slate-300 text-sm md:text-base xl:text-lg mb-4 flex-grow">
                    Válassz rugalmas percdíjas vagy kedvezményes havi csomagjaink közül! Minden konstrukcióban ingyenes
                    parkolás!
                </p>
                <button
                    class="w-full bg-emerald-600 hover:bg-emerald-500 text-white text-sm md:text-base xl:text-lg py-2 rounded-lg transition-colors duration-200">
                    Árak és csomagok
                </button>
            </div>

            <!-- Hogyan? -->
            <div class="group flex flex-col bg-slate-900/70 backdrop-blur-sm rounded-xl p-6 min-h-[200px] border-2 border-slate-300/40">
                <div class="flex items-center gap-3 mb-3">
                    <i class="fa-solid fa-bolt text-xl text-emerald-400"></i>
                    <h3 class="text-lg font-bold text-white">Hogyan?</h3>
                </div>
                <p class="text-slate-300 text-sm md:text-base xl:text-lg mb-4 flex-grow">
                    3 egyszerű lépésben: Töltsd le az App-ot, regisztrálj, és már indulhatsz is! Most az első utadhoz
                    extra kedvezményt adunk!
                </p>
                <button
                    class="w-full bg-emerald-600 hover:bg-emerald-500 text-white text-sm md:text-base xl:text-lg py-2 rounded-lg transition-colors duration-200">
                    Applikáció letöltése
                </button>
            </div>

            <!-- Parkolási Zónák -->
            <div class="group flex flex-col bg-slate-900/70 backdrop-blur-sm rounded-xl p-6 min-h-[200px] border-2 border-slate-300/40">
                <div class="flex items-center gap-3 mb-3">
                    <i class="fa-solid fa-map-location-dot text-xl text-emerald-400"></i>
                    <h3 class="text-lg font-bold text-white">Parkolási Zónák</h3>
                </div>
                <p class="text-slate-300 text-sm md:text-base xl:text-lg mb-4 flex-grow">
                    Fedezd fel Budapestet és külső extra-zónáinkat kedvezményesen! reptéri parkolóban töltőállomásokkal
                    várunk.
                </p>
                <button class="w-full bg-emerald-600 hover:bg-emerald-500 text-white text-sm md:text-base xl:text-lg
                    py-2 rounded-lg transition-colors duration-200">
                    Zónák felfedezése
                </button>
            </div>
        </div>
    </div>
</template>



<style>
.cta-main {
    width: 100%;
}
</style>