<template>
    <h1 class="text-center lg:text-start text-5xl font-bold text-sky-100 italic mt-20 mb-4">Új Bejelentés</h1>
    <div class="w-4/5 sm:w-full mx-auto border-b-8 border-indigo-800 rounded-xl opacity-60"></div>
    <div class="w-4/5 gap-5 mx-auto sm:w-full grid grid-cols-1 md:gap-4 md:grid-cols-2 lg:grid-cols-3  my-8 text-center text-xl">
        
        <button @click="$emit('toggle', 'cleanreport')" label="cleanreport"
            class="bg-fuchsia-700 rounded-full px-4 py-3 border-4 border-sky-600 hover:bg-fuchsia-800 text-white font-semibold">
            Tisztaság🚬
        </button>
        <button label="Meghibasodas"
            class="bg-orange-500 rounded-full px-4 py-3 border-4 border-sky-600 hover:bg-orange-600 text-white font-semibold">
            Műszaki hiba🔧
        </button>
        <button @click="$emit('toggle', 'accidentReport')" label="accidentReport"
            class="bg-red-600 rounded-full px-4 py-3 border-4 border-sky-600 hover:bg-red-700 text-sky-100 font-semibold">
            Baleseti S.O.S.⛐
        </button>
        <button @click="$emit('toggle', 'demageReport')" label="demageReport"
            class="bg-indigo-500 rounded-full px-4 py-3 border-4 border-sky-600 hover:bg-indigo-600 text-sky-100 font-semibold">
            Vandalizmus🔨
        </button>
        <button label="hiba"
            class="bg-gray-600 rounded-full px-4 py-3 border-4 border-sky-600 hover:bg-gray-700 text-sky-100 font-semibold">
            Büntetés📃
        </button>
        <button label="hiba"
            class="bg-emerald-600 rounded-full px-4 py-3 border-4 border-sky-600 hover:bg-emerald-700 text-sky-100 font-semibold">
            Távoli Hozzáférés🚘
        </button>
    </div>
</template>

<script>
export default {
    emits: ["toggle"],
    name: "CarReportButtons",
}
</script>