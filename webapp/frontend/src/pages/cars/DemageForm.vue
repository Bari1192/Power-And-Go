<template>
    <div class="bg-slate-700/50 rounded-xl p-6 border border-sky-500/30">
        <FormKit type="form" id="demageReport" :form-class="submitted ? 'hide' : 'show'" submit-label="Bejelentés"
            @submit="handleSubmit" :actions="false" #default="{ value, state }">
            <div class="space-y-6">
                <div
                    class="bg-gradient-to-r from-orange-600/20 via-amber-600/30 to-orange-600/20 rounded-lg p-3 border-2 border-orange-500/50">
                    <h2 class="text-center text-lg font-bold text-slate-100 tracking-wider uppercase">
                        sérülések megállapítása
                    </h2>
                </div>

                <!-- Sérülés típusa -->
                <FormKit name="title" type="select" label="Sérülés típusa" validation="required" :validation-messages="{
                    required: 'Kötelező megadni!'
                }" :options="[
            { value: '', label: 'Kérem válasszon!' },
            { value: '1', label: 'Karcolás' },
            { value: '2', label: 'Horpadás' },
            { value: '3', label: 'Törés, repedés' }
        ]" outer-class="w-full" label-class="block text-lg font-semibold text-slate-200 mb-2"
                    input-class="w-full px-4 py-2 rounded-lg border bg-slate-600 text-slate-100" />

                <!-- Sérülés helye -->
                <FormKit name="location" type="text" label="Sérülés helye" v-model="formLocation" disabled
                    validation="required" :validation-messages="{
                        required: 'Kötelező megadni!'
                    }" outer-class="w-full" label-class="block text-lg font-semibold text-slate-200 mb-2"
                    input-class="w-full px-4 py-2 rounded-lg border border-slate-500 bg-slate-700 text-emerald-300 font-semibold opacity-75 cursor-not-allowed" />

                <!-- Részletek -->
                <FormKit name="details" type="textarea" label="Részletek leírása"
                    validation="required|contains_alpha|length:10,255" :validation-messages="{
                        required: 'Kötelező kitölteni!',
                        contains_alpha: 'Kötelező szöveget is megadnia!',
                        length: 'Kérem pár szóban, de max. 255 karakterben foglalja össze!'
                    }" placeholder="Írja le a sérülés részleteit..." outer-class="w-full"
                    label-class="block text-lg font-semibold text-slate-200 mb-2"
                    input-class="w-full p-3 rounded-lg border bg-slate-600 text-slate-100 resize-y max-h-[200px] min-h-[100px]" />

                <!-- Azonnali intézkedés -->
                <div class="bg-amber-100/20 rounded-lg p-4 border border-amber-500/30">
                    <span class="block text-center text-lg font-semibold text-amber-400 mb-3">
                        Azonnali intézkedést igényel?
                    </span>

                    <div class="flex items-center justify-center space-x-6">
                        <label class="flex items-center space-x-2 cursor-pointer group">
                            <input id="requires_immediate_action_yes" name="requires_immediate_action" type="radio"
                                value="yes" v-model="requiresImmediateAction"
                                class="w-5 h-5 text-red-600 bg-slate-700 border-2 border-orange-500 focus:ring-orange-700 focus:ring-1 " />
                            <span class="text-slate-200 group-hover:text-white transition-colors">Igen</span>
                        </label>

                        <label class="flex items-center space-x-2 cursor-pointer group">
                            <input id="requires_immediate_action_no" name="requires_immediate_action" type="radio"
                                value="no" v-model="requiresImmediateAction"
                                class="w-5 h-5 text-emerald-600 bg-slate-700 border-2 border-emerald-500 focus:ring-emerald-800 focus:ring-1" />
                            <span class="text-slate-200 group-hover:text-white transition-colors">Nem</span>
                        </label>
                    </div>

                    <!-- Figyelmeztetés -->
                    <div class="mt-3 h-6">
                        <p class="text-xs text-center text-slate-50 italic transition-all duration-300" :class="{
                            'opacity-100': requiresImmediateAction === 'yes',
                            'opacity-0': requiresImmediateAction !== 'yes'
                        }">
                            * A gépjármű kivonásra kerül a forgalomból.
                        </p>
                    </div>
                </div>

                <!-- Submit gomb -->
                <div class="text-center pt-2">
                    <FormKit type="submit" label="Bejelentés" :disabled="state.submitting" outer-class="w-full"
                        input-class="px-8 py-3 tracking-wider text-slate-50 bg-orange-600/75 rounded-lg font-bold shadow-orange-800 hover:bg-orange-700/90 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        {{ state.submitting ? 'Bejelentés folyamatban...' : 'Bejelentés Elküldése' }}
                    </FormKit>
                </div>
            </div>
        </FormKit>

        <!-- Sikeres beküldés -->
        <div v-if="submitted" class="text-center py-8">
            <div class="bg-emerald-600/20 border-2 border-emerald-500 rounded-xl p-6 inline-block">
                <h3 class="text-2xl font-bold text-emerald-400">
                    Sérülés sikeresen bejelentve!
                </h3>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue'
import { generateClasses } from '@formkit/themes'

// Props
const props = defineProps({
    selectedLocation: {
        type: String,
        default: ''
    }
})

// Emits
const emit = defineEmits(['submit-success'])

// Reaktív változók
const submitted = ref(false)
const requiresImmediateAction = ref(null)
const formLocation = ref(props.selectedLocation)

// FormKit konfiguráció
const customConfig = {
    classes: generateClasses({
        global: {
            messages: 'text-orange-400 font-semibold italic mt-2 text-sm'
        }
    })
}

// Watchers
watch(() => props.selectedLocation, (newLocation) => {
    formLocation.value = newLocation
})

// Metódusok
const handleSubmit = (formData) => {
    const completeFormData = {
        ...formData,
        requires_immediate_action: requiresImmediateAction.value
    }

    console.log('Beküldött adatok:', completeFormData)

    // Emit az eseményt
    emit('submit-success', completeFormData)

    // Sikeres beküldés jelzése
    submitted.value = true

    // Reset 3 másodperc után
    setTimeout(() => {
        submitted.value = false
        requiresImmediateAction.value = null
    }, 3000)
}
</script>

<style scoped>
/* FormKit validációs üzenetek stílusa */
:deep(.formkit-messages) {
    color: #f87171;
    padding: 0.5rem 0;
    font-weight: 600;
    text-align: start;
    margin-top: 0.25rem;
}

/* Radio gombok egyedi stílusa */
input[type="radio"] {
    appearance: none;
    -webkit-appearance: none;
    width: 1.25rem;
    height: 1.25rem;
    border-radius: 50%;
    outline: none;
    cursor: pointer;
    position: relative;
    transition: all 0.2s ease;
}

input[type="radio"]:checked {
    background-color: currentColor;
}

input[type="radio"]:checked::after {
    content: "✓";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 0.75rem;
    font-weight: bold;
}
</style>
