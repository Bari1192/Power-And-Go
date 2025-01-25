<template>
    <FormKit type="form" id="cleanreport" :form-class="submitted ? 'hide' : 'show'" submit-label="Küldés"
        @submit="submitHandler" :actions="false" #default="{ value }" :config="customConfig" :validation="'required'"
        :validation-messages="{
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
                    label-class="block  tracking-wider text-sky-400 text-lg font-semibold mb-2"
                    input-class=" block w-full bg-gray-100 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                    :options="[
                        { value: '', label: 'Kérem válasszon!' },
                        { value: 6, label: 'Tisztasági takarítást igényel' },
                        { value: 1, label: 'Tisztítva, forgalomba visszaállítása' },
                    ]" v-model="selectedStatus" />
            </div>
            <div class="w-full md:w-1/3 px-3">
                <FormKit name="lastRenter" type="text" label="Legutóbbi bérlő"
                    label-class="block  tracking-wider text-sky-400 text-lg font-semibold mb-2" :value="lastRenter"
                    disabled
                    input-class="appearance-none block w-full bg-gray-300 bg-opacity-90 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
            </div>
        </div>
        <div class="w-full px-3">
            <FormKit name="description" type="textarea" label="Bejelentés tartalma"
                placeholder="Mennyire és hol szennyeződött? Mikor tervezi lezárni a bérlést?" v-model="description"
                :validation="'required|length:10,255'" :validation-messages="{
                    length: 'A bejelentés szövege min 10, maximum 255 karakter hosszú lehet!',
                    required: 'Kötelező kitölteni!'
                }" validation-messages-class="custom-validation-message"
                :validation-visibility="submitted ? 'live' : 'dirty'"
                label-class="text-sky-400 text-lg font-semibold mb-2"
                input-class="max-h-28 w-full align-top appearance-none bg-gray-100 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 focus:outline-none focus:bg-white focus:border-gray-500" />
        </div>
        <div class="flex justify-center my-5">
            <FormKit type="submit" label="Bejelentés" id="button">
            </FormKit>
        </div>
        <div v-if="submitted" class="flex justify-center mb-10 mx-auto">
            <h2 class="text-green-600 italic w-fit bg-amber-50 font-semibold text-3xl">Az adatok sikeresen beküldésre
                kerültek!</h2>
        </div>
    </FormKit>
</template>


<script>

import { http } from '@utils/http'
import { generateClasses } from "@formkit/themes";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

export default {
    data() {
        return {
            submitted: false,
            selectedStatus: '',
            formLocation: this.selectedLocation,
            customConfig: {
                classes: generateClasses({
                    submit: {
                        input:
                            "text-lg bg-teal-500 hover:bg-teal-700 hover:border-teal-500 text-white font-bold rounded-lg border-2 border-teal-700 py-2 px-4",
                    },
                }),
            },
        };
    },
    props: {
        carId: {
            type: Number,
            required: true,
        },
        lastRenter: {
            type: [String, null],
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
        }
    },
    methods: {
        async submitHandler() {
            try {
                const payload = {
                    car_id: this.carId,
                    status_id: parseInt(this.selectedStatus, 10),
                    description: this.description.trim(),
                };
                const response = await http.post('/tickets', payload);
                this.submitted = true;
                this.$emit('submit-success', response.data);

                // Toast-os értesítés POPUP
                toast.success("Bejelentés sikeresen elküldve!", {
                    position: "bottom-center",
                    autoClose: 6000,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: true,
                    theme: "dark",
                });

            } catch (error) {
                toast.error("Hiba történt a bejelentés során!", {
                    position: "top-right",
                    autoClose: 6000,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: true,
                    theme: "dark",
                });
            }
        }
    },
    watch: {
        selectedStatus(statusNumber) {
            if (this.description === '' || this.description === 'Az autó elérhető és bérlésre kész.') {
                if (statusNumber === 5) {
                    this.description = 'Az autót tisztításra ki kell vonni a forgalomból.';
                } else if (statusNumber === 1) {
                    this.description = 'Az autó elérhető és bérlésre kész.';
                } else {
                    this.description = '';
                }
            }
        }
    }
}
</script>

<style>
.formkit-messages {
    color: #bb0404 !important;
    padding: .2rem;
    background-color: rgba(247, 232, 211, 0.9);
    font-weight: bold;
    text-align: start;
    margin: 0.25rem 0;
    width: fit-content;
}
</style>