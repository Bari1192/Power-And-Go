<template>
    <h1 class="text-5xl font-bold text-sky-100 italic mt-20 mb-4">Új Bejelentés</h1>
    <div class="w-full mx-auto border-b-8 border-indigo-800 rounded-xl mb-6 opacity-60"></div>
    <div class="flex justify-center my-8 text-center text-xl space-x-8">
        <button @click="$emit('toggle', 'cleanreport')" label="cleanreport"
            class="bg-teal-500 rounded-full py-2 px-6 border-4 border-sky-800 hover:bg-teal-400 text-white font-semibold">
            Tisztaság 🚬
        </button>
        <button label="Meghibasodas"
            class="bg-orange-500 rounded-full py-2 px-6 border-4 border-sky-800 hover:bg-orange-600 hove:text-white text-white font-semibold">
            Meghibásodás 🔧
        </button>
        <button @click="$emit('toggle', 'accidentReport')" label="accidentReport"
            class="bg-rose-600 rounded-full py-2 px-6 border-4 border-sky-800 hover:bg-rose-700 text-sky-100 font-semibold">
            Baleset ⛐
        </button>
        <button @click="$emit('toggle', 'demageReport')" label="demageReport"
            class="bg-indigo-500 rounded-full py-2 px-6 border-4 border-sky-800 hover:bg-indigo-400 text-sky-100 font-semibold">
            Rongálás 🔨
        </button>
        <button label="hiba"
            class="bg-red-700 rounded-full py-2 px-6 border-4 border-sky-800 hover:bg-red-600 text-sky-100 font-semibold">
            Büntetés 📃
        </button>
    </div>
</template>

<script>
export default {
    name: "CarReportButtons",
}
</script>