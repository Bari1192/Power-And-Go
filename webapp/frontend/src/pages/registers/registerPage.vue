<template>
    <BaseLayout>
        <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 mb-24">
            <div id="headerbg" class="max-w-4xl w-full space-y-8  p-8 rounded-2xl shadow-2xl">
                <!-- Fejléc -->
                <div id="companyname" class="text-center">
                    <h2 class="text-4xl pt-5 pr-4 italic text-right font-extrabold text-white tracking-wider">
                        Power And Go
                    </h2>
                </div>

                <!-- Léptető -->
                <div class="flex justify-center space-x-4">
                    <div v-for="(step, index) in steps" :key="index" class="flex flex-col items-center">
                        <div :class="[
                            'w-12 h-12 rounded-full flex items-center justify-center mb-2 transition-all duration-1000',
                            currentStep === index + 1
                                ? 'bg-amber-100 text-lime-900 ring-4 ring-yellow-400 font-semibold text-xl'
                                : 'bg-lime-800 text-lime-100 ring-2 ring-lime-400/30'
                        ]">
                            {{ index + 1 }}
                        </div>
                        <div class="text-sm font-medium text-lime-100">{{ step }}</div>
                    </div>
                </div>

                <p class="text-white text-4xl ml-2 tracking-wide font-semibold">Regisztráció</p>
                <div v-if="currentStep === 1" class="space-y-6 transition-all  duration-1000">
                    <div
                        class="bg-gradient-to-r from-lime-800/40 to-transparent p-4 rounded-lg border-4 border-yellow-200 border-opacity-75 backdrop-blur-sm">
                        <h3 class="text-2xl font-bold text-lime-100 mb-6 border-l-8 border-yellow-400 pl-4">
                            Személyes adatok
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <FormKit type="text" name="lastname" v-model="formData.lastname" label="Vezetéknév"
                                placeholder="Adja meg vezetéknevét" validation="required"
                                :validation-messages="{ required: 'Kötelező mező' }" :classes="formClasses" />

                            <FormKit type="text" name="firstname" v-model="formData.firstname" label="Keresztnév"
                                placeholder="Adja meg keresztnevét" validation="required"
                                :validation-messages="{ required: 'Kötelező mező' }" :classes="formClasses" />

                            <FormKit type="date" name="birth_date" v-model="formData.birth_date" label="Születési dátum"
                                validation="required" :validation-messages="{ required: 'Kötelező mező' }"
                                :classes="formClasses" />

                            <FormKit type="tel" name="phone" v-model="formData.phone" label="Telefonszám"
                                placeholder="+36301234567" validation="required" :validation-messages="{
                                    required: 'Kötelező mező',
                                    matches: 'Érvénytelen telefonszám formátum'
                                }" :classes="formClasses" />
                        </div>
                        <div class="flex justify-center space-x-4 pt-6">
                            <button v-if="currentStep < 3" @click.prevent="nextStep"
                                class="px-8 py-3 bg-yellow-500 text-lime-900 rounded-lg hover:bg-yellow-600 transform hover:-translate-y-1 transition-all duration-200 shadow-lg hover:shadow-yellow-500/50">
                                Következő
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Második lépés: Dokumentum adatok -->
                <div v-if="currentStep === 2" class="space-y-6 transition-all duration-1000">
                    <div class="bg-gradient-to-r from-lime-800/40 to-transparent p-4 rounded-lg backdrop-blur-sm">
                        <h3 class="text-2xl font-bold text-lime-100 mb-6 border-l-4 border-yellow-400 pl-4">
                            Dokumentum adatok
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <FormKit type="text" name="id_card" v-model="formData.id_card"
                                label="Személyi igazolvány szám" placeholder="123456AA" validation="required"
                                :validation-messages="{ required: 'Kötelező mező' }" :classes="formClasses" />

                            <FormKit type="text" name="driving_license" v-model="formData.driving_license"
                                label="Jogosítvány szám" placeholder="AB123456" validation="required"
                                :validation-messages="{ required: 'Kötelező mező' }" :classes="formClasses" />

                            <FormKit type="date" name="license_start_date" v-model="formData.license_start_date"
                                label="Jogosítvány kiállítási dátuma" validation="required"
                                :validation-messages="{ required: 'Kötelező mező' }" :classes="formClasses" />

                            <FormKit type="date" name="license_end_date" v-model="formData.license_end_date"
                                label="Jogosítvány érvényességi ideje" validation="required"
                                :validation-messages="{ required: 'Kötelező mező' }" :classes="formClasses" />

                        </div>
                        <div class="flex justify-center space-x-4 pt-6">
                            <!-- Vissza gomb - csak ha nem az első lépésnél vagyunk -->
                            <button v-if="currentStep > 1" @click.prevent="prevStep"
                                class="px-8 py-3 bg-lime-800 text-lime-100 rounded-lg hover:bg-lime-900 transform hover:-translate-y-1 transition-all duration-200 shadow-lg hover:shadow-lime-900/50">
                                Vissza
                            </button>

                            <!-- Következő gomb - csak ha nem az utolsó lépésnél vagyunk -->
                            <button v-if="currentStep < 3" @click.prevent="nextStep"
                                class="px-8 py-3 bg-yellow-500 text-lime-900 rounded-lg hover:bg-yellow-600 transform hover:-translate-y-1 transition-all duration-200 shadow-lg hover:shadow-yellow-500/50">
                                Következő
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Harmadik lépés: Felhasználói fiók adatok -->
                <div v-if="currentStep === 3" class="space-y-6 transition-all duration-1000">
                    <div class="bg-gradient-to-r from-lime-800/40 to-transparent p-4 rounded-lg backdrop-blur-sm">
                        <h3 class="text-2xl font-bold text-lime-100 mb-6 border-l-4 border-yellow-400 pl-4">
                            Felhasználói fiók
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <FormKit type="text" name="user_name" v-model="formData.user_name" label="Felhasználónév"
                                placeholder="felhasználónév" validation="required"
                                :validation-messages="{ required: 'Kötelező mező' }" :classes="formClasses" />

                            <FormKit type="password" name="person_password" v-model="formData.person_password"
                                label="Jelszó" validation="required|length:8" :validation-messages="{
                                    required: 'Kötelező mező',
                                    length: 'A jelszónak minimum 8 karakterből kell állnia'
                                }" :classes="formClasses" />

                            <FormKit type="text" name="pin" v-model="formData.pin" label="PIN kód" placeholder="1234"
                                validation="required|matches:/^[0-9]{4}$/" :validation-messages="{
                                    required: 'Kötelező mező',
                                    matches: 'A PIN kódnak 4 számjegyből kell állnia'
                                }" :classes="formClasses" />
                            <div class="flex justify-center space-x-4 pt-6">
                                <!-- VISSZA -->
                                <button v-if="currentStep > 1" @click.prevent="prevStep"
                                    class="px-8 py-3 bg-lime-800 text-lime-100 rounded-lg hover:bg-lime-900 transform hover:-translate-y-1 transition-all duration-200 shadow-lg hover:shadow-lime-900/50">
                                    Vissza
                                </button>
                                <!-- Véglegesítés -->
                                <button v-if="currentStep === 3" type="submit" @click="submitRegistration"
                                    class="px-8 py-3 bg-yellow-500 text-lime-900 rounded-lg hover:bg-yellow-600 transform hover:-translate-y-1 transition-all duration-200 shadow-lg hover:shadow-yellow-500/50">
                                    Regisztráció véglegesítése
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BaseLayout>
</template>

<style scoped>
#headerbg {
    background: linear-gradient(to bottom,
            rgba(84, 139, 10, .9),
            rgba(84, 139, 10, .7),
            rgba(101, 163, 13, .3)),
        url('@assets/styles/grass.webp');

    background-position: bottom;
    background-size: contain;
    background-repeat: no-repeat;
    backdrop-filter: blur(8px);
    position: relative;
}

.overlay-gradient {
    pointer-events: none;
}

#companyname {
    background: url('@img/BaseEmail/largebanner.png');
    background-size: cover;
    background-position: center;
    height: 90px;
}

button {
    cursor: pointer;
    color: rgba(255, 255, 255, 0.9) !important;
    padding: 10px 20px;
    background-size: 200% 200%;
    background-image: linear-gradient(145deg,
            rgba(139, 194, 36, 0.8) 0%,
            rgba(217, 138, 2, 0.6) 50%,
            rgba(139, 194, 36, 0.9) 50%,
            rgba(217, 138, 2, 0.8) 100%);
    background-position: 0 0;
    backdrop-filter: blur(5px);
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    box-shadow:
        0 4px 15px rgba(0, 0, 0, 0.1),
        inset 0 0 0 1px rgba(255, 255, 255, 0.1);
    transition: all 0.7s ease;
}

button:hover {
    background-position: 100% 100%;
    box-shadow:
        0 6px 20px rgba(0, 0, 0, 0.15),
        inset 0 0 0 1px rgba(255, 255, 255, 0.2);
}

.backdrop-blur-sm {
    backdrop-filter: blur(8px);
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.transition-all {
    animation: fadeIn 1s ease-in-out;
}

input:focus {
    box-shadow: 0 0 0 2px rgba(234, 179, 8, 0.2);
}
</style>
<script setup>
import BaseLayout from '@/layouts/BaseLayout.vue';
import { ref, reactive } from 'vue';
import { http } from '@/utils/http.mjs';
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
const formClasses = {
    input: 'w-full text-lime-700 font-semibold px-4 py-3 rounded-lg bg-lime-100/90 border border-lime-300/30 placeholder-lime-700/50 focus:ring-2 focus:ring-yellow-400/50 focus:border-transparent transition duration-200',
    label: 'block text-lime-100 text-lg font-semibold pl-2 mb-2',
    message: 'text-yellow-300 text-sm mt-1'
};
const validateStep = (step) => {
    switch (step) {

        case 1:
            setTimeout(() => { }, 1000);
            return formData.lastname && formData.firstname &&
                formData.birth_date && formData.phone;
        case 2:
            setTimeout(() => { }, 1000);
            return formData.id_card && formData.driving_license &&
                formData.license_start_date && formData.license_end_date;
        case 3:
            setTimeout(() => { }, 1000);
            return formData.user_name && formData.person_password &&
                formData.pin;
        default:
            return true;
    }
};

const nextStep = () => {
    if (validateStep(currentStep.value)) {
        currentStep.value++;
        setTimeout(() => { }, 5000);

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
