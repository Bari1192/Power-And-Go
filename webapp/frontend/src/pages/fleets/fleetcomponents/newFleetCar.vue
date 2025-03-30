<template>
    <div id="bgcolor"
        class="m-auto d-flex justify-center my-10 lg:max-w-[1100px] border-4 rounded-2xl border-green-500/50 italic shadow-2xl font-semibold ">
        <div class="flex justify-center bg-yellow-100/55 my-5 rounded-t-sm rounded-b-sm">
            <p
                class="text-center tracking-wide text-4xl font-semibold text-white my-5 outline-orange-400 drop-shadow-2xl capitalize">
                új flotta modell hozzáadása
            </p>
        </div>
        <div class="m-auto d-flex justify-center border-b-4 border-green-100 w-2/3 mb-2 rounded-md"></div>
        <div class="flex mx-auto justify-center mt-4 mb-16">
            <p class=" ml-2 text-red-600 rounded-md  bg-yellow-50/85 max-w-fit font-semibold text-xl italic py-1 px-2">
                Kérjük fokozott figyelemmel töltse ki!</p>
        </div>
        <div v-if="submitted" class="flex justify-center mb-10 mx-auto">
            <h2 class="text-green-600 italic w-fit bg-amber-50 font-semibold text-3xl">Az adatok sikeresen beküldésre
                kerültek!</h2>
        </div>
        <FormKit type="form" id="registration-example" :form-class="submitted ? 'hide' : 'show'" submit-label="Küldés"
            @submit="submitHandler" :actions="false" #default="{ value }" :validation="'required'" :validation-messages="{
                required: 'Kérjük minden adatot töltsön ki!'
            }">
            <div class="flex flex-wrap my-5">
                <div class="w-full md:w-1/3 px-3">
                    <FormKit name="manufacturer" type="text" label="Gyártó neve" placeholder="például: VW, Skoda!"
                        :validation="'required|alpha|length:2,40'" :validation-messages="{
                            alpha: 'Kizárólag betűket tartalmazhat!',
                            length: 'A szöveg 2-40 karakterig terjedhet!',
                            required: 'Kötelező kitölteni!'
                        }" label-class="block tracking-wider text-white text-lg font-semibold mb-2"
                        input-class="appearance-none block w-full bg-gray-100 text-lime-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
                </div>
                <div class="w-full md:w-1/3 px-3">
                    <FormKit name="carmodel" type="text" label="Modell Típusa" placeholder="például: E-up!"
                        :validation="'required|alpha|length:2,30'" :validation-messages="{
                            alpha: 'Kizárólag betűket tartalmazhat!',
                            length: 'A szöveg 2-40 karakter hosszú lehet!',
                            required: 'Kötelező kitölteni!'
                        }" label-class="block tracking-wider text-white text-lg font-semibold mb-2"
                        input-class="appearance-none block w-full bg-gray-100 text-lime-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
                </div>
                <div class="w-full md:w-1/3 px-3">
                    <FormKit name="motor_power" type="number" label="Teljesítmény (kW) értékben"
                        placeholder="például: 18" :validation="'required|integer|min:18|max:500'" :validation-messages="{
                            required: 'Kötelező kitölteni!',
                            integer: 'Csak egész számot írhat be!',
                            min: 'Minimum 18 kW-os érték megadása szükséges!',
                            max: 'Maximum 500 kW-os értéket adhat meg!'
                        }" label-class="block  tracking-wider text-white text-lg font-semibold mb-2"
                        input-class="appearance-none block w-full bg-gray-100 text-lime-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
                </div>
            </div>
            <div class="flex flex-wrap my-5">
                <div class="w-full md:w-1/3 px-3">
                    <FormKit name="top_speed" type="number" label="Végsebesség (km/h)-ban" placeholder="például: 130"
                        :validation="'required|integer|min:100|max:300'" :validation-messages="{
                            required: 'Kötelező kitölteni!',
                            integer: 'Csak egész számot írhat be!',
                            min: 'Minimum 100km/h-ás érték megadása szükséges!',
                            max: 'Maximum 300 km/h-ás értéket írhatbe!'
                        }" label-class="block tracking-wider text-white text-lg font-semibold mb-2"
                        input-class="appearance-none block w-full bg-gray-100 text-lime-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white"
                        help-class="text-slate-600 italic text-start my-1 pl-2 text-sm" />
                </div>
                <div class="w-full md:w-1/3 px-3">
                    <FormKit name="tire_size" type="text" label="Gumiméret" placeholder="például: 165|65-R15"
                        :validation="'length:8,30|required'" :validation-messages="{
                            required: 'Kötelező kitölteni!',
                            length: 'A szövegnek 8-30 karakter között kell lennie!',
                        }" label-class="block tracking-wider text-white text-lg font-semibold mb-2"
                        help-class="text-slate-600 italic text-start my-1 pl-2 text-sm"
                        input-class="appearance-none block w-full bg-gray-100 text-lime-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
                </div>
                <div class="w-full md:w-1/3 px-3">
                    <FormKit name="driving_range" type="number" label="Hatótáv értéke (km)" placeholder="például: 235"
                        :validation="'required|integer|min:100|max:1000'" :validation-messages="{
                            required: 'Kötelező kitölteni!',
                            integer: 'Csak egész számot írhat be!',
                            min: 'Minimum 100 km-es érték megadása szükséges!',
                            max: 'Maximum 1 000 km-es érték lehet!'
                        }" label-class="block tracking-wider text-white text-lg font-semibold mb-2"
                        input-class="appearance-none block w-full bg-gray-100 text-lime-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
                </div>
            </div>

            <div class="flex justify-center items-center align-middle bg-yellow-100/55 my-12 min-h-20 ">
                <FormKit type="submit" label="Hozzáadás" id="addFleet" wrapper-class="w-full"
                    input-class="tracking-[0.10em] text-lg bg-yellow-500 text-white font-semibold py-2
                    px-12 border-b-4 border-b-yellow-50 rounded-lg transition-all duration-150 ease-in-out 
                    hover:bg-yellow-600 active:translate-y-[2px] active:border-b-2" />
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
                    manufacturer: formValues.manufacturer,
                    carmodel: formValues.carmodel,
                    motor_power: formValues.motor_power,
                    top_speed: formValues.top_speed,
                    tire_size: formValues.tire_size,
                    driving_range: formValues.driving_range,
                });
                this.submitted = true;
            } catch (error) {
                alert("Hiba! Nem sikerült elküldeni. Próbálja újra később!")
            }
        },
    }

}

</script>

<style scoped>

#bgcolor {
    /* background-color: white; */
    background: linear-gradient(to bottom,
            rgba(25, 126, 0, .9) 0%,
            rgba(57, 154, 33, .85) 35%,
            rgba(91, 185, 68, .8) 70%,
            rgba(101, 163, 13, .7) 100%);
}

:deep(.formkit-message[data-message-type="validation"]) {
    color: red;
    background-color: yellow;
    max-width:fit-content ;
    letter-spacing: 1px;
    padding:0 5px;
    border-radius: 5px;
    margin-top: 5px;
}

:deep(.formkit-message[data-message-type="ui"]) {
    display: none;
}

:deep(.formkit-wrapper[formkit-help="help-input_3"]) {
    display: none;
}
</style>