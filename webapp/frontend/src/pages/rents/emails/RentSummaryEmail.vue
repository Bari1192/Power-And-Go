<template>
    <div class="bg-slate-800 rounded-2xl border border-emerald-500/20 overflow-hidden">
        <!-- Email Header -->
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 p-6">
            <div class="flex items-center justify-between">
                
                <img src="http://backend.vm1.test/storage/carsImages/1.png" alt="PowerAndGo Logo" class="h-20 opacity-90 w-auto" />
                <span class="text-slate-100 tracking-wide font-bold text-md bg-emerald-500 border-2 border-emerald-400 px-4 py-2 rounded-md">
                     Bérlés Összesítő
                </span>
            </div>
        </div>

        <!-- Email Content -->
        <div class="p-8 text-slate-200">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-emerald-400 mb-2">
                    Kedves {{ rent.person }}!
                </h2>
                <p class="text-slate-400">
                    Köszönjük, hogy a <span class="text-emerald-400 font-medium">Power And Go</span> e-carsharing-et választottad!
                </p>
            </div>

            <!-- Rental Summary Card -->
            <div class="bg-slate-700/50 rounded-xl p-6 mb-8 border border-emerald-500/20">
                <h3 class="text-lg font-semibold text-emerald-400 mb-4">Bérlési Összesítő</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Info -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-car text-emerald-400"></i>
                            <span>Rendszám: <strong>{{ rent.plate }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-clock text-emerald-400"></i>
                            <span>Kezdés: <strong>{{ rent.rent_start }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-flag-checkered text-emerald-400"></i>
                            <span>Befejezés: <strong>{{ rent.rent_close }}</strong></span>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-road text-emerald-400"></i>
                            <span>Megtett táv: <strong>{{ rent.distance }} km</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-car-side text-emerald-400"></i>
                            <span>Vezetési idő: <strong>{{ formatTime(rent.driving_minutes) }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-parking text-emerald-400"></i>
                            <span>Parkolási idő: <strong>{{ formatTime(rent.parking_minutes) }}</strong></span>
                        </div>
                    </div>
                </div>

                <!-- Cost Summary -->
                <div class="mt-6 pt-6 border-t border-slate-600">
                    <div class="flex justify-between items-center">
                        <span class="text-lg">Végösszeg:</span>
                        <span class="text-2xl font-bold text-emerald-400">{{ rent.total_cost }} Ft</span>
                    </div>
                </div>
            </div>

            <!-- Charging Info -->
            <div v-if="rent.charged_kw > 0" class="bg-slate-700/50 rounded-xl p-6 mb-8 border border-emerald-500/20">
                <div class="flex items-center gap-4">
                    <div class="bg-emerald-500/20 p-3 rounded-full">
                        <i class="fas fa-charging-station text-2xl text-emerald-400"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-emerald-400">Töltési Információ</h4>
                        <p class="text-slate-400">
                            Töltött mennyiség: <strong>{{ rent.charged_kw }} kWh</strong>
                            <br>
                            Jóváírt kredit: <strong>{{ rent.credits }} Ft</strong>
                        </p>
                    </div>
                </div>
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
                    <p class="font-bold text-emerald-400">PowerAndGo csapata</p>
                    <p class="text-sm text-slate-400 italic">Élmények. Neked. Nekünk. Tisztán.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    rent: {
        type: Object,
        required: true
    }
});

const formatTime = (minutes) => {
    if (!minutes || minutes < 1) return '0 perc';
    const hours = Math.floor(minutes / 60);
    const mins = minutes % 60;
    return hours > 0 ? `${hours} óra ${mins} perc` : `${mins} perc`;
};
</script>
