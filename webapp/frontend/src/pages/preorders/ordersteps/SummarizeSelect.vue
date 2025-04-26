<template>
    <div class="grid grid-cols-3 w-full m-12">

        <div class="flex flex-col sm:flex-row bg-lime-500/80 border-2 border-gray-500/40 border-dotted rounded-xl shadow-xl 
      transition-all duration-200 ease-in-out hover:bg-emerald-300/70 active:translate-y-[2px] active:border-b-2
      min-h-[200px] sm:h-[170px] w-full overflow-hidden cursor-pointer">

            <!-- Kép - fix méretű konténer -->
            <div
                class="w-full sm:w-1/2  lg:h-[200px]  flex items-center justify-center overflow-hidden bg-white rounded-br-lg">
                <img class="object-cover w-auto h-36 p-8 lg:h-auto max-w-full"
                    :src="`http://backend.vm1.test/storage/carsImages/${fleet.id}.png`"
                    :alt="fleet.manufacturer + ' ' + fleet.carmodel + ' képe'">
            </div>

            <!-- Leírások - 2/3 szélesség nagy képernyőn -->
            <div class="w-full sm:w-1/2 p-2 flex flex-col justify-between  ">
                <div>
                    <h5 class="text-lg xl:text-xl tracking-tight text-white text-center font-medium">
                        {{ fleet.manufacturer + ' ' + fleet.carmodel }}
                    </h5>
                    <ul class="my-2 font-base text-sm text-nowrap lg:text-wrap text-lime-800/80">
                        <li class="mx-auto pb-2">
                            <i class="fa-solid fa-road xl:pl-3 xl:pr-1 text-lime-800/90"></i> Akár <b>{{
                                fleet.driving_range
                            }}</b> km
                            hatótáv.
                        </li>
                        <li class="mx-auto pb-2">
                            <i class="fa-solid fa-plug  xl:pl-3 xl:pr-1 text-lime-800/90"></i> <b>{{
                                fleet.motor_power
                            }}</b>
                            kW-os
                            akkumulátor.
                        </li>
                        <li class="mx-auto">
                            <i class="fa-solid fa-car xl:pl-3 xl:pr-1 text-lime-800/90"></i>Elérhető <b>
                            </b>
                            db a flottában.
                        </li>
                        <slot />
                    </ul>
                </div>
            </div>
        </div>
        <div
            class="bg-lime-500 max-w-fit mx-4 rounded-lg text-slate-100 border-2 border-orange-300 shadow-xl p-4 cursor-pointer hover:bg-emerald-500 hover:text-lime-50 duration-200 ease-in-out">
            <p><span class="font-medium text-slate-600 ">Megrendelő neve: </span>{{ user.firstname + ' ' + user.lastname
                || "Hibás" }}</p>
            <p><span class="font-medium text-slate-600">Felhasználó neve: </span>{{ user.user_name || 'Hibás'
                }}</p>
            <p><span class="text-slate-600">Előfizetési csomag: </span>{{ SelectSubScrip(user.sub_id) }}</p>
            <p><span class="text-slate-600">Aktuális Egyenleg: </span>{{ user.account_balance || 0 }} Ft</p>
            <p><span class="text-slate-600">Telefonszám: </span>{{ user.phone }}</p>
            <p><span class="text-slate-600">Email cím: </span>{{ user.email }}</p>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    fleet: {
        type: Object,
        required: true
    },
    user: {
        type: Object,
        required: true,
    }
});
const SelectSubScrip = (subid) => {
    switch (subid) {
        case 1:
            return 'Power';
        case 2:
            return 'Power-Plus';
        case 3:
            return 'Power-Premium';
        case 4:
            return 'Power-VIP';
        default:
            return 'N/A előfiz.';
    }
};
</script>