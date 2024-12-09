<template>
    <BaseLayout>

        <div
            class="m-auto d-flex justify-center my-10 w-3/4 border-2 rounded-2xl border-sky-300 dark:text-red-600 dark:italic dark:font-semibold ">
            <p class="text-center tracking-wide text-4xl font-semibold text-sky-100 my-5 ">
                Új flottatípus felvétele
            </p>
            <div class="m-auto d-flex justify-center border-b-4 border-sky-300 w-2/3 mb-20"></div>
            <FormKit type="form" id="registration-example" :form-class="submitted ? 'hide' : 'show'" submit-label="Post"
                @submit="submitHandler" :actions="false" #default="{ value }">
                <div class="flex flex-wrap my-5">
                    <div class="w-full md:w-1/3 px-3">
                        <FormKit name="gyarto" type="text" label="Gyártó neve" placeholder="például: VW, Skoda!"
                            :validation="'required|alpha|length:2,40'" :validation-messages="{
                                alpha: 'Kizárólag betűket tartalmazhat!',
                                length: 'A szöveg 2-40 karakter hosszú lehet!',
                                required: 'Kötelező kitölteni!'
                            }" label-class="block tracking-wider text-sky-400 text-lg font-semibold mb-2"
                            input-class="appearance-none block w-full bg-gray-100 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
                    </div>
                    <div class="w-full md:w-1/3 px-3">
                        <FormKit name="tipus" type="text" label="Modell Típusa" placeholder="például: E-up!"
                            :validation="'required|length:2,40'" :validation-messages="{
                                is: ({ node: { value } }) =>
                                    `Sorry, we don’t service ${value} anymore.`,
                            }" label-class="block  tracking-wider text-sky-400 text-lg font-semibold mb-2"
                            input-class="appearance-none block w-full bg-gray-100 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
                    </div>
                    <div class="w-full md:w-1/3 px-3">
                        <FormKit name="teljesitmeny" type="number" label="Teljesítmény (kW) értékben"
                            placeholder="például: 18" :validation="'required|integer|min:18|max:500'"
                            label-class="block  tracking-wider text-sky-400 text-lg font-semibold mb-2"
                            input-class="appearance-none block w-full bg-gray-100 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
                    </div>
                </div>
                <div class="flex flex-wrap my-5">
                    <div class="w-full md:w-1/3 px-3">
                        <FormKit name="vegsebesseg" type="number" label="Végsebesség (km/h)-ban"
                            placeholder="például: 130" :validation="'required|integer|min:100|max:300'"
                            label-class="block tracking-wider text-sky-400 text-lg font-semibold mb-2"
                            input-class="appearance-none block w-full bg-gray-100 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white" />
                    </div>
                    <div class="w-full md:w-1/3 px-3">
                        <FormKit name="gumimeret" type="text" label="Gumiméret" placeholder="például: 165|65-R15"
                            :validation="'required|string'"
                            label-class="block tracking-wider text-sky-400 text-lg font-semibold mb-2"
                            :help="'A szervíz végett kérjük pontosan töltse ki!'"
                            help-class="text-gray-500 italic text-start my-1"
                            input-class="appearance-none block w-full bg-gray-100 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
                    </div>
                    <div class="w-full md:w-1/3 px-3">
                        <FormKit name="hatotav" type="number" label="Hatótáv értéke (km)" placeholder="például: 235"
                            :validation="'required|integer|min:100|max:1000'"
                            label-class="block tracking-wider text-sky-400 text-lg font-semibold mb-2"
                            input-class="appearance-none block w-full bg-gray-100 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
                    </div>
                </div>
                <div class="flex justify-center my-5 ">
                    <FormKit type="submit" label="Hozzáadás"
                        class="bg-blue-500 hover:bg-blue-400 text-white font-semiboldpy-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded">
                    </FormKit>
                </div>
                <div v-if="submitted">
                    <h2 class="text-xl text-green-500">Submission successful!</h2>
                </div>
            </FormKit>
        </div>
    </BaseLayout>
</template>


<script>
import { http } from '@utils/http.mjs';
import BaseLayout from '@layouts/BaseLayout.vue'


export default {
    data() {
        return {
            fleets: []
        }
    },
    components: {
        BaseLayout,
    },
    async mounted() {
        try {
            const resp = await http.get('/fleets');
            this.fleets = resp.data.data;
        } catch (error) {
            console.error('Hiba történt az API hívás során:', error);
        }
    }
}

</script>

<style scoped>
#input_0-rule_alpha {
    color: #ef4444 !important;
    font-style: italic;
    text-align: start;
    margin: 0.25rem 0;
}
</style>
