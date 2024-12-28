<template>
    <FormKit type="form" id="cleanreport" :form-class="submitted ? 'hide' : 'show'" submit-label="Küldés"
        @submit="submitHandler" :actions="false" #default="{ value }" :validation="'required'" :validation-messages="{
            required: 'Kérjük minden adatot töltsön ki!'
        }">
        <div class="flex flex-wrap my-5">
            <div class="w-full md:w-1/3 px-3">
                <FormKit name="car_id" type="text" label="Autó azonosítója" :validation="'required|integer'" disabled
                    :validation-messages="{
                        required: 'Kötelező megadni!'
                    }" label-class="block tracking-wider text-sky-400 text-lg font-semibold mb-2" :value="carId"
                    input-class="appearance-none block w-full bg-gray-300 bg-opacity-90 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-sky-100 focus:border-gray-500" />
            </div>

            <div class="w-full md:w-1/3 px-3">
                <FormKit name="statusDescrip" type="select" label="Bejelentés törzse" :validation="'required'"
                    :validation-messages="{ required: 'Bejelentés típusát kötelező megjelölni!' }"
                    validation-messages-class="custom-validation-message"
                    label-class="block  tracking-wider text-sky-400 text-lg font-semibold mb-2" :value="statusDescrip"
                    input-class="appearance-none block w-full bg-gray-100 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                    :options="[
                        { value: '', label: 'Kérem válasszon!' },
                        { value: '5', label: 'Tisztasági takarítást igényel' },
                        { value: '1', label: 'Tisztítva, forgalomba visszaállítása' },
                        { value: 'hibakód_3', label: 'Hibakód 3 - Akkumulátor probléma' },
                        { value: 'hibakód_4', label: 'Hibakód 4 - Guminyomás alacsony' },
                    ]" />
            </div>
            <div class="w-full md:w-1/3 px-3">
                <FormKit name="statusId" type="text" label="Legutóbbi bérlő"
                    label-class="block  tracking-wider text-sky-400 text-lg font-semibold mb-2" :value="lastRenter"
                    disabled
                    input-class="appearance-none block w-full bg-gray-300 bg-opacity-90 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
            </div>
        </div>
        <div class="w-full px-3">
            <FormKit name="description" type="textarea" label="Bejelentés tartalma"
                placeholder="Mennyire és hol szennyeződött? Mikor tervezi lezárni a bérlést?"
                 :validation="'required|alpha|length:20,255'"
                :validation-messages="{
                    alpha: 'Kizárólag betűket tartalmazhat!',
                    length: 'A bejelentés szövege min 20, maximum 255 karakter hosszú lehet!',
                    required: 'Kötelező kitölteni!'
                }"
                validation-messages-class="custom-validation-message"
                label-class=" text-sky-400 text-lg font-semibold mb-2 "
                input-class="max-h-28 w-full align-top appearance-none bg-gray-100 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 focus:outline-none focus:bg-white focus:border-gray-500" />
        </div>
        <div class="flex flex-wrap my-5">

        </div>

        <div class="flex justify-center my-5 ">
            <FormKit type="submit" label="Bejelentés"
                class="bg-teal-500 rounded-full py-2 px-6 border-4 border-sky-800 hover:bg-teal-400 text-white font-semibold">
            </FormKit>
        </div>
        <div v-if="submitted" class="flex justify-center mb-10 mx-auto">
            <h2 class="text-green-600 italic w-fit bg-amber-50 font-semibold text-3xl">Az adatok sikeresen beküldésre
                kerültek!</h2>
        </div>
    </FormKit>
</template>


<script>
export default {
    props: {
        carId: {
            type: Number,
            required: true,
        },
        lastRenter: {
            type: [String, null],
            required: true,
        },
        statusDescrip: {
            type: String,
            required: true,
        },
        submitted: {
            type: Boolean,
            default: false,
        },
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
                await http.post('/tickets', {
                    car_id: carId,
                    status_id: statusId,
                    status_descrip: statusDescrip,
                });
                this.submitted = true;
            } catch (error) {
                alert("Hiba! Nem sikerült elküldeni. Próbálja újra később!")
            }
        },
    },
};
</script>

<style>
#input_1-rule_required, #input_3-rule_required{
    color: #bb0404 !important;
    padding: .2rem;
    background-color: rgba(247, 232, 211, 0.9);
    font-weight: bold;
    text-align: start;
    margin: 0.25rem 0;
    width:fit-content;
}
</style>