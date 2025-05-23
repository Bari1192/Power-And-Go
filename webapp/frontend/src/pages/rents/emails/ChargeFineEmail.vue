<template>
    <div class="bg-slate-800 rounded-2xl border border-red-500/20 overflow-hidden">
        <!-- Email Header -->
        <div class="bg-gradient-to-r from-rose-800/70 to-red-900/70 px-6 py-2">
            <div class="flex items-center justify-between">
                <img src="http://backend.vm1.test/storage/carsImages/6.png" alt="" class="h-24 opacity-90 w-auto" />
                <span class="text-white font-medium text-sm bg-rose-300/70 px-4 py-2 rounded-md">
                    Büntetési Értesítő
                </span>
            </div>
        </div>

        <!-- Email Content -->
        <div class="p-12 text-slate-200">
            <!-- Greeting -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-white mb-2">
                    Kedves {{ fee.person }}!
                </h2>
                <div class="bg-red-500/10 border border-red-500/20 rounded-lg p-4 text-yellow-200">
                    <i class="fas fa-exclamation-triangle text-yellow-300 mr-2"></i>
                    Fontos értesítés az Ön bérlésével kapcsolatban
                </div>
            </div>

            <!-- Incident Details Card -->
            <div class="bg-slate-700/50 rounded-xl p-6 mb-8 border border-red-500/20">
                <h3 class="text-lg font-semibold text-red-400 mb-4">Esemény Részletei</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Vehicle Info -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-car"></i>
                            <span>Rendszám: <strong>{{ fee.plate }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-clock"></i>
                            <span>Kezdés: <strong>{{ fee.rent_start }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-flag-checkered"></i>
                            <span>Befejezés: <strong>{{ fee.rent_close }}</strong></span>
                        </div>
                    </div>

                    <!-- Charging Status -->
                    <div class="bg-slate-800/50 p-4 rounded-lg" style="font-family: 'Nunito','Arial';">
                        <div class="flex items-center justify-between mb-3">
                            <span>Záráskori töltöttség:</span>
                            <span class="font-bold text-rose-400">{{ fee.end_percent }}%</span>
                        </div>
                        <div class="border-b-2 border-slate-300/40 w-full mb-3"></div>
                        <div class="flex items-center justify-between">
                            <span>Minimum előírás:</span>
                            <span class="font-bold text-rose-400">
                                {{ chargingCategories[fee.category].min_toltes }}%
                            </span>
                        </div>
                    </div>
                </div>

                <!-- ÁSZF Reference -->
                <div class="bg-slate-800/50 p-4 rounded-lg mb-6">
                    <p class="text-sm text-slate-300">
                        <i class="fas fa-book text-red-400 mr-2"></i>
                        Az <span class="text-emerald-400 italic font-bold">Általános Szerződési Feltétek 8.3.5.</span>
                        része alapján - az akkumulátor alultöltöttsége okán keletkezett Szállítási
                        Költségre hivatkozva <strong>kötbér megállapítására került sor</strong>.
                    </p>
                </div>

                <!-- Penalty -->
                <div class="space-y-4">
                    <h4 class="font-semibold underline underline-offset-4">Büntetési Díj Részletezése:</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center p-3 bg-slate-800/50 rounded-lg">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-truck text-yellow-300"></i>
                                <span>Szállítási Költség</span>
                            </div>
                            <span class="font-base italic">100.000 Ft</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-slate-800/50 rounded-lg">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-user-shield text-yellow-300"></i>
                                <span>Kiszállási Díj</span>
                            </div>
                            <span class="font-base italic">8.000 Ft</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-slate-800/50 rounded-lg">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-file-alt text-yellow-300"></i>
                                <span>Adminisztrációs díj</span>
                            </div>
                            <span class="font-base italic">5.000 Ft</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="bg-slate-700/50 rounded-xl p-6 mb-8 border border-emerald-500/20">
                <p class="mb-4">
                    A csatolt dokumentumokat
                    <a :href="`https://powerandgo.com/berlesek/${fee.id}`" class="text-emerald-400 font-medium hover:underline underline-offset-4">
                        innen
                    </a>
                    éred el, amelyek részletesen tartalmazzák a büntetési számla számítását és indoklását.
                </p>
                <p class="text-sm text-slate-400">
                    Az összeg automatikusan levonásra kerül az egyenlegéből, amennyiben rendelkezésre áll
                    a szükséges fedezet.
                </p>
            </div>

            <!-- Footer -->
            <div class="text-center space-y-4 mt-8 pt-8 border-t border-slate-700">
                <p class="text-slate-400">
                    Kérdés vagy észrevétel esetén keress minket:
                    <a href="mailto:ugyfelszolgalat@powerandgo.com" class="text-emerald-400 hover:underline">
                        ugyfelszolgalat@powerandgo.com
                    </a>
                </p>
                <div>
                    <p class="font-bold text-emerald-500">PowerAndGo csapata</p>
                    <p class="text-sm text-slate-400 italic">Élmények. Neked. Nekünk. Tisztán.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    fee: {
        type: Object,
        required: true
    }
});

const chargingCategories = {
    1: { min_toltes: 9.0 },
    2: { min_toltes: 6.0 },
    3: { min_toltes: 4.5 },
    4: { min_toltes: 4.0 },
    5: { min_toltes: 4.0 }
};
</script>
