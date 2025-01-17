<template>
    <BaseLayout>
        <div class="container mx-auto">
            <div class="flex justify-center items-center my-16 space-x-16">
                <RouterLink :to="{ name: '/cars/[autok_id]/edit-car', params: { autok_id: car.autok_id } }">
                    <button @click="setMode('edit')" class="bg-lime-600 hover:bg-lime-500 text-white p-2 rounded">
                        Szerkesztés
                    </button>
                </RouterLink>
                <button @click="setMode('hozzaadas')" class="bg-lime-600 hover:bg-lime-500 text-white p-2 rounded">
                    Új névjegy létrehozása
                </button>
            </div>
        </div>
        <h1 class="text-5xl text-sky-800">
            {{
                mode === "edit" ? `${car.plate} szerkesztése` : "Új névjegy létrehozása"
            }}
        </h1>
        <form @submit.prevent="submitForm">
            <div class="mb-2 my-5">
                <label for="teljesnev" class="block text-2xl text-sky-800">Auto Azonosító</label>
                <input type="text" id="teljesnev"
                    class="border border-sky-500 text-sky-500 text-sm rounded-lg block w-full p-2.5"
                    v-model="car.autok_id" required />
            </div>

            <div class="mb-2">
                <label for="telefonszam" class="block text-sm text-sky-800">Rendszám</label>
                <input type="text" id="telefonszam"
                    class="border border-sky-500 text-sky-500 text-sm rounded-lg block w-full p-2.5"
                    v-model="car.plate" required />
            </div>
            <div class="mb-2">
                <label for="email" class="block text-sm text-sky-800">Futásteljesítmény</label>
                <input type="text" id="email"
                    class="border border-sky-500 text-sky-500 text-sm rounded-lg block w-full p-2.5"
                    v-model="car.km_ora_allas" required />
            </div>
            <div class="mb-2">
                <label for="cim" class="block text-sm text-sky-800">Gyártási év</label>
                <input type="text" id="cim"
                    class="border border-sky-500 text-sky-500 text-sm rounded-lg block w-full p-2.5" value="{{ ca }}"
                    v-model="car.manufacturing_year" required />
            </div>
            <div class="mb-2">
                <label for="születésnap" class="block text-sm text-sky-800">Felszereltség</label>
                <input type="text" id="születésnap"
                    class="border border-sky-500 text-sky-500 text-sm rounded-lg block w-full p-2.5"
                    v-model="car.felsz_id_fk" required />
            </div>
            <div class="mb-2">
                <label for="vállalat" class="block text-sm text-sky-800">Flotta Azon.</label>
                <input type="text" id="vállalat"
                    class="border border-sky-500 text-sky-500 text-sm rounded-lg block w-full p-2.5"
                    v-model="car.flotta_id_fk" required />
            </div>
            <button type="submit" class="w-100 py-2 bg-sky-600 hover:bg-sky-500 text-white p-2 rounded">
                Mentés
            </button>
        </form>
    </BaseLayout>
</template>

<script>
import BaseLayout from "@layouts/BaseLayout.vue";
import { http } from "@utils/http";

export default {
    components: {
        BaseLayout,
    },
    data() {
        return {
            car: [],
            mode: null,
        };
    },
    async loadCar() {
        try {
            const response = await http.get(`/cars/${this.$route.params.id}`);
            this.car = response.data;
        } catch (error) {
            console.error("Hiba történt az adatok lekérésekor:", error);
        }
    },
    methods: {
        setMode(mode) {
            this.mode = mode;
            if (mode === "hozzaadas") {
                this.car = {
                    plate: "",
                    km_ora_allas: "",
                    manufacturing_year: "",
                    felsz_id_fk: "",
                    flotta_id_fk: "",
                };
            }
        },
        async loadCar() {
            try {
                const response = await http.get(`/cars/${this.$route.params.id}`);
                this.car = response.data;
            } catch (error) {
                console.error("Hiba történt az adatok lekérésekor:", error);
            }
        },
        async submitForm() {
            try {
                if (this.mode === "edit") {
                    await http.put(`/cars/${this.$route.params.id}`, this.car);
                    alert("A névjegy frissítve lett.");
                } else if (this.mode === "hozzaadas") {
                    await http.post("/cars", this.contact);
                    alert("Új névjegy létrehozva.");
                }
                this.$router.push("/");
            } catch (error) {
                console.error("Hiba történt a mentés során:", error);
                alert("Hiba történt a mentés során.");
            }
        },
    },
};
</script>