<template>
    <BaseLayout>
        <div class="m-auto d-flex justify-center border-4 rounded-2xl border-sky-300 w-1/3 mt-24 pt-4 mb-52">
            <p
                class="text-center tracking-wide text-2xl font-semibold text-white bg-sky-500 border-lime-500 border-t-4 border-b-4 rounded-md py-6 my-4">
                Regisztráció | Power And Go
            </p>
            <div class="flex justify-center w-full py-4 border-b-2 border-gray-100 border-opacity-25">
                <div v-for="(step, index) in steps" :key="index" class="mx-2 flex flex-col items-center ">
                    <div :class="[
                        'w-10 h-10 rounded-full flex items-center justify-center mb-1',
                        currentStep === index + 1 ? 'bg-lime-500 text-white' : 'bg-sky-700 text-gray-300'
                    ]">
                        {{ index + 1 }}
                    </div>
                    <div class="text-xs font-semibold justify-center text-lime-500">{{ step }}</div>
                </div>
            </div>
            <div
                class="w-full bg-sky-900 pt-4 pl-4 font-semibold text-md text-start rounded-lg d-flex justify-center mb-8">
                <FormKit type="form" @submit="submitRegistration" :actions="false">
                    <div v-if="currentStep === 1" class="flex flex-col gap-1">
                        <div class="w-fit">
                            <h2
                                class="border-t-4 border-b-4 border-r-4 border-amber-900 text-3xl -ml-4 pl-4 pr-8 py-6 rounded-r-xl bg-sky-100 my-8 underline underline-offset-4 text-lime-600 font-semibold">
                                Személyes adatok
                            </h2>
                        </div>
                        <div class="row ml-4">
                            <div class="flex flex-row gap-4">
                                <div class="flex-1">
                                    <FormKit type="text" name="lastname" label="Vezetéknév"
                                        placeholder="Adja meg vezetéknevét" label-class="text-lime-500"
                                        input-class="custom-input" :validation="'required'" :validation-messages="{
                                            required: 'Kötelező megadni!'
                                        }" v-model="formData.lastname" />
                                </div>
                                <div class="flex-1">
                                    <FormKit type="text" name="firstname" label="Keresztnév" label-class="text-lime-500"
                                        input-class="custom-input" placeholder=" Adja meg keresztnevét"
                                        :validation="'required'" :validation-messages="{
                                            required: 'Kötelező megadni!'
                                        }" v-model="formData.firstname" />
                                </div>
                            </div>
                        </div>
                        <div class="row ml-4">
                            <div class="flex flex-row gap-4">
                                <div class="flex-1 ">
                                    <FormKit type="date" name="birth_date" label="Születési dátum"
                                        label-class="text-lime-500" input-class="custom-input" placeholder=" ÉÉÉÉ-HH-NN"
                                        validation="required" v-model="formData.birth_date" />
                                </div>
                                <div class="flex-1">
                                    <FormKit type="tel" name="phone" label="Telefonszám" label-class="text-lime-500"
                                        placeholder=" +36 30 666 8888" validation="required" v-model="formData.phone" />
                                </div>

                            </div>
                            <div class="flex-1">
                                <FormKit type="password" name="person_password" input-class="custom-input"
                                    label-class="text-lime-500 " label="Jelszó" placeholder=" Legalább 8 karakter"
                                    validation="required|length:8" v-model="formData.person_password" />
                            </div>
                        </div>
                    </div>

                    <!-- Második oldal innen -->
                    <div v-if="currentStep === 2" class="flex flex-col gap-2">
                        <div class="w-fit">
                            <h2
                                class="border-t-4 border-b-4 border-r-4 border-amber-900 text-3xl -ml-4 pl-4 pr-8 py-6 rounded-r-xl bg-sky-100 my-8 underline underline-offset-4 text-lime-600 font-semibold">
                                Dokumentum adatok
                            </h2>
                        </div>
                        <div>
                            <div class="flex flex-row gap-4 text-md">
                                <div class="flex-1">
                                    <FormKit type="text" name="id_card" label="Személyigazolvány szám"
                                        label-class="text-lime-500" placeholder="XXX12345XX" validation="required"
                                        input-class="custom-input" v-model="formData.id_card" />
                                </div>
                                <div class="flex-1">
                                    <FormKit type="text" name="driving_license" label="Jogosítvány száma"
                                        label-class="text-lime-500" placeholder="ABC12345" validation="required"
                                        input-class="custom-input" v-model="formData.driving_license" />
                                </div>
                            </div>
                            <div class="flex flex-row gap-4">
                                <div class="flex-1">
                                    <FormKit type="date" name="license_start_date" label="Jogosítvány érv. kezdete"
                                        label-class="text-lime-500" placeholder="ÉÉÉÉ-HH-NN" validation="required"
                                        input-class="custom-input w-full" v-model="formData.license_start_date" />
                                </div>
                                <div class="flex-1">
                                    <FormKit type="date" name="license_end_date" label="Jogosítvány érv. vége"
                                        label-class="text-lime-500" placeholder="ÉÉÉÉ-HH-NN" validation="required"
                                        input-class="custom-input w-full" v-model="formData.license_end_date" />
                                </div>
                            </div>
                            <div class="flex flex-row gap-4">
                                <div class="flex-1">
                                    <FormKit type="text" name="user_name" label="Felhasználónév"
                                        label-class="text-lime-500" placeholder="Adjon meg egyedi felhasználónevet"
                                        validation="required" input-class="custom-input" v-model="formData.user_name" />
                                </div>
                                <div class="flex-1">
                                    <FormKit type="text" name="pin" label="Felh. PIN kód megadása" placeholder="Négy számjegyű kód"
                                        input-class="custom-input" label-class="text-lime-500"
                                        validation="required|length:4" v-model="formData.pin" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- A harmadik oldal jön innen! -->
                    <div class="row flex justify-center gap-4 mt-8">
                        <button v-if="currentStep > 1" @click.prevent="prevStep"
                            class="tracking-[0.10em] bg-sky-600 my-6 font-semibold py-4 px-12 border-b-4 rounded-2xl transition-all duration-300 ease-in-out hover:bg-sky-700 active:translate-y-[2px] active:border-b-2">
                            <p class="text-white text-lg px-2 py-3">Vissza</p>
                        </button>

                        <button v-if="currentStep < steps.length" @click.prevent="nextStep"
                            class="tracking-[0.10em] bg-lime-600 my-6 font-semibold py-4 px-12 border-b-4 rounded-2xl transition-all duration-300 ease-in-out hover:bg-lime-700 active:translate-y-[2px] active:border-b-2">
                            <p class="text-white text-lg">Következő</p>
                        </button>

                        <FormKit v-if="currentStep === steps.length" type="submit"
                            input-class="tracking-[0.10em] bg-lime-600 my-6 font-semibold py-4 px-12 border-b-4 rounded-2xl transition-all duration-300 ease-in-out hover:bg-lime-700 active:translate-y-[2px] active:border-b-2">
                            <p class="text-white text-lg px-2 py-3">Regisztráció elküldése</p>
                        </FormKit>
                    </div>
                </FormKit>
            </div>
        </div>
    </BaseLayout>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { http } from '@/utils/http.mjs';
import BaseLayout from '@/layouts/BaseLayout.vue';
import { toast } from 'vue3-toastify';

const steps = ['Személyes adatok', 'Felhasználói profil', 'Összesítő'];
const currentStep = ref(1);

const formData = reactive({
    lastname: '',
    firstname: '',
    birth_date: '',
    phone: '',
    person_password: '',
    id_card: '',
    driving_license: '',
    license_start_date: '',
    license_end_date: '',
    user_name: '',
    pin: ''
});

const validateStep = (step) => {
    if (step === 1) {
        return formData.lastname &&
            formData.firstname &&
            formData.birth_date &&
            formData.phone &&
            formData.person_password &&
            formData.person_password.length >= 8;
    }
    return true;
};

const nextStep = () => {
    if (validateStep(currentStep.value)) {
        currentStep.value++;
    } else {
        toast.error('Kérem töltse ki az összes kötelező mezőt!');
    }
};

const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

const submitRegistration = async () => {
    try {
        const response = await http.post('/register', formData);
        toast.success('Sikeres regisztráció!');
    } catch (error) {
        toast.error('Hiba történt a regisztráció során: ' + (error.message || 'Ismeretlen hiba'));
    }
};
</script>

<style>
.formkit-input {
    padding: 6px;
    border-radius: 1rem;
    margin-bottom: .8rem;
    margin-top: .3rem;
    color: grey;
    padding: 6px 4px;
}

.formkit-label {
    padding-left: 8px;
    font-size: 20px;
}

/* Progress indicator styles */
.step-indicator {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 8px;
}
</style>
