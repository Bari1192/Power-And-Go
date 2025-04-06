<template>
    <BaseLayout>
        <div class="flex justify-center items-start lg:items-center min-h-[75vh] lg:min-h-[70vh] xl:min-h-[60vh]">
            <div
                class="login-container w-full max-w-md xl:w-full xl:min-h-full mx-4 md:mx-auto my-8 lg:mb-8 lg:mt-0 border-8 rounded-2xl bg-emerald-200 border-emerald-700/80 border-double shadow-xl overflow-hidden">
                <p id="logintitle"
                    class="text-center tracking-wide text-2xl sm:text-3xl md:text-4xl text-white bg-green-800/80 border-lime-500 border-t-4 border-b-4 py-3 mt-2 mb-4">
                    Bejelentkezés
                </p>

                <div
                    class="w-full bg-lime-900 bg-opacity-45 pt-4 font-semibold text-lg xl:text-xl rounded-b-md flex flex-col m-auto text-center justify-center px-4 sm:px-6">
                    <FormKit type="form" id="login" :actions="false" @submit="submitHandler" v-slot="{ state }">
                        <FormKit name="user" label="E-mail cím" label-class="text-white py-2 sm:py-4" id="userinput"
                        :disabled="isLoading" :validation="'required|email'" :validation-messages="{
                                matches: 'Hibás a felhasználónév formátum.',
                                required: 'E-mail cím megadása kötelező',
                                email: 'Érvényes e-mail címet adjon meg'
                            }" placeholder="example@gmail.com" />

                        <FormKit type="password" name="password" label="Jelszó" label-class="text-white py-2 sm:py-4"
                            id="passwordinput" :validation="'required'" :disabled="isLoading" :validation-messages="{
                                required: 'Jelszó megadása kötelező'
                                

                            }" placeholder="********" />

                        <div class="button-container mt-4 sm:mt-6 mb-6 sm:mb-8 ">
                            <button type="submit" id="loginbutton"
                                class="bg-green-600 rounded-2xl  hover:bg-green-700 text-white/90 font-bold py-2 px-4 border-b-4 border-lime-400 hover:border-lime-500 transition-colors duration-300"
                                :disabled="isLoading">
                                {{ isLoading ? 'Bejelentkezés...' : 'Bejelentkezés' }}
                            </button>
                        </div>
                    </FormKit>
                </div>
            </div>
        </div>
    </BaseLayout>
</template>
<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@stores/AuthenticationStore'
import BaseLayout from '@layouts/BaseLayout.vue'

const router = useRouter()
const authStore = useAuthStore()
const isLoading = ref(false)
const submitted = ref(false)
const submitHandler = async (formData) => {
    isLoading.value = true

    // Mesterségesen generáltatom a késleltetést itt.
    await new Promise(resolve => setTimeout(resolve, 2000))
    await authStore.login(formData.user, formData.password)
    isLoading.value = false
    router.push('/');
}
</script>


<style>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css");

#logintitle {
    font-family: 'Playfair Display', serif;
    font-weight: 600;
}

#userinput,
#passwordinput {
    color: rgb(5, 80, 3);
    padding-inline: 0.5rem;
    text-align: center;
    border-radius: 1rem;
    border-color: rgba(202, 245, 123, 0.5);
    border-width: 2px;
    margin-top: 0.7rem;
}

#userinput {
    margin-bottom: 1rem;
}

[data-invalid] .formkit-message {
    color: red;
    font-size: 16px;
    font-style: italic;
}

li#login-incomplete.formkit-message {
    display: none;
}

[data-invalid] input#userinput.formkit-input,
[data-invalid] input#passwordinput.formkit-input {
    border: 2px solid red;
    margin: 0;
    padding: 0;
}

.formkit-submit {
    background-color: bisque;
}

[data-valid] input#userinput.formkit-input,
[data-valid] input#passwordinput.formkit-input {
    border: 2px solid green;
}

.loading-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 1.5rem auto;
}

.hourglass {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    animation: flip-hourglass 1s infinite alternate,
        color-hourglass 3s ease-in-out infinite;
}

@keyframes flip-hourglass {
    0% {
        transform: rotateX(0deg);
    }

    100% {
        transform: rotateX(180deg);
    }
}

@keyframes color-hourglass {
    0% {
        filter: hue-rotate(0deg);
    }

    50% {
        filter: hue-rotate(180deg);
    }

    100% {
        filter: hue-rotate(360deg);
    }
}
</style>