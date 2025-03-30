<template>
    <div class="mx-auto my-8 bg-sky-600 bg-opacity-70 w-fit p-5 rounded-2xl border-4 border-lime-500">
        <FormKit type="form" id="demageReport" :form-class="submitted ? 'hide' : 'show'" submit-label="Bejelentés"
            @submit="submitHandler" :config="customConfig" :actions="false" #default="{ value }"
            :validation="'required'" :validation-messages="{
                required: 'Kérjük minden adatot töltsön ki!'
            }">
            <div class="space-y-2 w-full">
                <h2
                    class="text-2xl dark:text-white font-bold mb-4 bg-lime-600 py-2 px-8 text-center border-2 border-lime-700 rounded-md">
                    Sérülés Bejelentés
                </h2>

                <FormKit name="title" type="select"
                    input-class="bg-gray-100 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                    label="Sérülés típusa" validation="required" :options="[
                        { value: '1', label: 'Karcolás' },
                        { value: '2', label: 'Horpadás' },
                        { value: '3', label: 'Törés, repedés' },
                    ]" label-class="text-white text-lg font-semibold" />

                <!-- Sérülés helye -->
                <FormKit name="location" type="text"
                    input-class="bg-gray-300 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                    label="Sérülés helye" v-model="formLocation" disabled validation="required"
                    label-class="text-white text-lg font-semibold" />

                <FormKit name="details" type="textarea" label="Részletek leírása"
                    input-class="max-h-24 min-h-16 w-full bg-gray-100 text-sky-800 font-semibold border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                    validation='required|contains_alpha|length:10,255' label-class="text-white text-lg font-semibold"
                    :validation-messages="{
                        required: 'Kötelező kitölteni!',
                        regex: 'A szövegnek tartalmaznia kell legalább egy betűt!',
                        length: 'Kérem pár szóban, de max. 255 karakterben foglalja össze!',
                        contains_alpha: 'Kötelező szöveget is megadnia!'
                    }" />

                <div class="flex flex-col space-y-1 items-center">
                    <span class="text-white text-lg bg-amber-600 shadow-md px-5 py-2 rounded-md font-semibold">
                        Azonnali intézkedést igényel?
                    </span>

                    <div class="flex items-center pt-1 space-x-4">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input id="requires_immediate_action_yes" name="requires_immediate_action" type="radio"
                                value="yes" v-model="requiresImmediateAction" class="custom-radio" />
                            <span class="text-white text-lg">Igen</span>
                        </label>

                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input id="requires_immediate_action_no" name="requires_immediate_action" type="radio"
                                value="no" v-model="requiresImmediateAction" class="custom-radio" />
                            <span class="text-white text-lg">Nem</span>
                        </label>
                    </div>

                    <!-- Fenntartott hely a szövegnek, hogy ne tolja el az oldalt -->
                    <span
                        class="italic opacity-80 text-lime-50 pt-3 font-bold transition-opacity duration-300 h-3 flex items-center justify-center"
                        :class="{ 'opacity-100': requiresImmediateAction === 'yes', 'opacity-0': requiresImmediateAction !== 'yes' }">
                        <sup>*A gépjármű kivonásra kerül a forgalomból.</sup>
                    </span>
                </div>

                <div class="flex justify-center pt-1">
                    <FormKit type="submit" label="Bejelentés" :disabled="isSubmitting">
                        {{ isSubmitting ? 'Bejelentés folyamatban...' : 'Bejelentés' }}
                    </FormKit>
                </div>
            </div>
        </FormKit>
    </div>
</template>

<script>
import { generateClasses } from "@formkit/themes";

export default {
    props: {
        selectedLocation: {
            type: String,
            default: '',
        },
    },
    data() {
        return {
            requiresImmediateAction: null,
            formLocation: this.selectedLocation,
            customConfig: {
                classes: generateClasses({
                    global: {
                        input: "text-sky-500 mb-2",
                        messages: "text-rose-800 font-bold italic mb-2 bg-orange-200 px-2",
                    },
                    submit: {
                        input:
                            "text-lg bg-lime-600 hover:bg-lime-700 hover:border-lime-600 text-white font-bold rounded-lg border-2 border-lime-700 py-2 px-4",
                    },
                }),
            },
        };
    },
    watch: {
        selectedLocation(newLocation) {
            this.formLocation = newLocation;
        },
    },
    methods: {
        submitHandler(formData) {
            console.log("Beküldött adatok:", formData);
        },
    },
};
</script>

<style scoped>
.custom-radio {
    appearance: none;
    -webkit-appearance: none;
    width: 22px;
    height: 22px;
    background-color: white;
    border-radius: 4px;
    border: 2px solid red;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0px;
    cursor: pointer;
}

.custom-radio:checked::after {
    width: 20px;
    height: 20px;
    content: "✅";
    background-color: red;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}
</style>