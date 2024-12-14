<template>
    <div
        class="m-auto d-flex justify-center my-10 w-3/4 border-2 rounded-2xl border-sky-300 dark:text-red-600 dark:italic dark:font-semibold ">
        <p class="text-center tracking-wide text-4xl font-semibold text-sky-100 my-5 capitalize">
            új flotta modell hozzáadása
        </p>
        <div class="m-auto d-flex justify-center border-b-4 border-sky-300 w-2/3 mb-20"></div>
        <p
            class=" ml-2 text-red-600 rounded-md  bg-amber-100 bg-opacity-95 max-w-fit  font-semibold text-3xl italic p-1 underline">
            Kérjük fokozott figyelemmel töltse ki!</p>
        <FormKit type="form" id="registration-example" :form-class="submitted ? 'hide' : 'show'" submit-label="Küldés"
            @submit="submitHandler" :actions="false" #default="{ value }">
            <div class="flex flex-wrap my-5">
                <div class="w-full md:w-1/3 px-3">
                    <FormKit name="gyarto" type="text" label="Gyártó neve" placeholder="például: VW, Skoda!"
                        :validation="'required|alpha|length:2,40'" :validation-messages="{
                            alpha: 'Kizárólag betűket tartalmazhat!',
                            length: 'A szöveg 2-40 karakterig terjedhet!',
                            required: 'Kötelező kitölteni!'
                        }" label-class="block tracking-wider text-sky-400 text-lg font-semibold mb-2"
                        input-class="appearance-none block w-full bg-gray-100 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
                </div>
                <div class="w-full md:w-1/3 px-3">
                    <FormKit name="tipus" type="text" label="Modell Típusa" placeholder="például: E-up!"
                        :validation="'required|alpha|length:2,30'" :validation-messages="{
                            alpha: 'Kizárólag betűket tartalmazhat!',
                            length: 'A szöveg 2-40 karakter hosszú lehet!',
                            required: 'Kötelező kitölteni!'
                        }" label-class="block tracking-wider text-sky-400 text-lg font-semibold mb-2"
                        input-class="appearance-none block w-full bg-gray-100 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
                </div>
                <div class="w-full md:w-1/3 px-3">
                    <FormKit name="teljesitmeny" type="number" label="Teljesítmény (kW) értékben"
                        placeholder="például: 18" :validation="'required|integer|min:18|max:500'" :validation-messages="{
                            required: 'Kötelező kitölteni!',
                            integer: 'Csak egész számot írhat be!',
                            min: 'Minimum 18 kW-os érték megadása szükséges!',
                            max: 'Maximum 500 kW-os értéket adhat meg!'
                        }" label-class="block  tracking-wider text-sky-400 text-lg font-semibold mb-2"
                        input-class="appearance-none block w-full bg-gray-100 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
                </div>
            </div>
            <div class="flex flex-wrap my-5">
                <div class="w-full md:w-1/3 px-3">
                    <FormKit name="vegsebesseg" type="number" label="Végsebesség (km/h)-ban" placeholder="például: 130"
                        :validation="'required|integer|min:100|max:300'" :validation-messages="{
                            required: 'Kötelező kitölteni!',
                            integer: 'Csak egész számot írhat be!',
                            min: 'Minimum 100km/h-ás érték megadása szükséges!',
                            max: 'Maximum 300 km/h-ás értéket írhatbe!'
                        }" label-class="block tracking-wider text-sky-400 text-lg font-semibold mb-2"
                        input-class="appearance-none block w-full bg-gray-100 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white"
                        :help="'Csak a szám értékét írja be!'" help-class="text-gray-500 italic text-start my-1" />
                </div>
                <div class="w-full md:w-1/3 px-3">
                    <FormKit name="gumimeret" type="text" label="Gumiméret" placeholder="például: 165|65-R15"
                        :validation="'length:8,30|required'" :validation-messages="{
                            required: 'Kötelező kitölteni!',
                            length: 'A szövegnek 8-30 karakter között kell lennie!',
                        }" label-class="block tracking-wider text-sky-400 text-lg font-semibold mb-2"
                        :help="'A szervíz végett kérjük pontosan töltse ki!'"
                        help-class="text-gray-500 italic text-start my-1"
                        input-class="appearance-none block w-full bg-gray-100 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
                </div>
                <div class="w-full md:w-1/3 px-3">
                    <FormKit name="hatotav" type="number" label="Hatótáv értéke (km)" placeholder="például: 235"
                        :validation="'required|integer|min:100|max:1000'" :validation-messages="{
                            required: 'Kötelező kitölteni!',
                            integer: 'Csak egész számot írhat be!',
                            min: 'Minimum 100 km-es érték megadása szükséges!',
                            max: 'Maximum 1 000 km-es érték lehet!'
                        }" label-class="block tracking-wider text-sky-400 text-lg font-semibold mb-2"
                        input-class="appearance-none block w-full bg-gray-100 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
                </div>
            </div>
            <div class="flex justify-center my-5 ">
                <FormKit type="submit" label="Hozzáadás"
                    class="bg-blue-500 hover:bg-blue-400 text-white font-semiboldpy-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
                </FormKit>
            </div>
            <div v-if="submitted">
                <h2 class="text-xl text-green-500 mx-auto">Az adatok sikeresen beküldésre kerültek!</h2>
            </div>
        </FormKit>
    </div>
</template>


<script>
import { http } from '@utils/http.mjs';

export default {
    data() {
        return {
            fleets: [],
            submitted: false,
        }
    },
    async mounted() {
        try {
            const resp = await http.get('/fleets');
            this.fleets = resp.data.data;
        } catch (error) {
            console.error('Hiba történt az API hívás során:', error);
        }
    },
    methods: {
        async submitHandler(formValues) {
            try {
                await http.post('fleets/', {
                    gyarto: formValues.gyarto,
                    tipus: formValues.tipus,
                    teljesitmeny: formValues.teljesitmeny,
                    vegsebesseg: formValues.vegsebesseg,
                    gumimeret: formValues.gumimeret,
                    hatotav: formValues.hatotav,
                });
                this.submitted = true;
            } catch (error) {
                alert("Hiba! Nem sikerült elküldeni. Próbálja újra később!")
            }
        },
    }

}

</script>
