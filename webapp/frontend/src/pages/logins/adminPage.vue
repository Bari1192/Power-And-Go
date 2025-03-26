<template>
    <BaseLayout>
        <div class="mx-auto mb-40 my-8 d-flex justify-center border-4 rounded-2xl bg-slate-200 border-lime-800 border-opacity-80 w-1/4 shadow-xl">
            <p
                id="logintitle" class="text-center tracking-wide text-4xl text-white bg-yellow-600 bg-opacity-75  border-lime-500  border-t-4 border-b-4 rounded-md py-3 my-8 ">
                Login Panel
            </p>

            <div
                class="w-full bg-lime-900 bg-opacity-45  pt-4 font-semibold text-2xl rounded-t-md rounde-b-md d-flex m-auto text-center justify-center my-8">
                <FormKit type="form" id="login" :actions="false" @submit="submitHandler" v-slot="{ state }">

                    <FormKit name="user" label="E-mail cím" label-class="text-white py-4" id="userinput"
                        :validation="'required'" :validation-messages="{
                            matches: 'Hibás a felhasználónév megadása!',
                            required: 'Kötelező megadni!',
                        }" />

                    <FormKit name="password" type="password" id="userpw" label="Jelszó" label-class="text-white"
                        :validation="'required'" :validation-messages="{
                            matches: 'A jelszónak 4-8 számból kell állnia.',
                            required: 'Kötelező megadni!',
                        }" />
                    <FormKit type="submit" label="Bejelentkezés"
                        input-class="tracking-[0.10em] text-lg bg-lime-600 my-6 text-white font-semibold py-3 px-12 border-b-4 rounded-2xl transition-all duration-300 ease-in-out hover:bg-lime-700 active:translate-y-[2px] active:border-b-2" />

                        <div v-if="isLoading" class="loading-container">
                        <div class="hourglass">⏳</div>
                    </div>
                    <div v-if="submitted">
                        <h2 class="text-green-500">Bejelentkezés sikeres!</h2>
                    </div>
                </FormKit>
            </div>
        </div>
    </BaseLayout>
</template>

<script>
import { http } from '@utils/http.mjs';
import BaseLayout from '@layouts/BaseLayout.vue';
import { toast } from 'vue3-toastify';



export default {
    components: {
        BaseLayout,
    },
    data() {
        return {
            submitted: false,
            loginError: null,
            state: {},
            isLoading: false,
        }
    },
    methods: {
        async submitHandler(formData) {
            this.isLoading = true;
            this.loginError = null;

            try {
                // Mesterségesen generáltatom a késleltetést itt.
                await new Promise(resolve => setTimeout(resolve, 2000));

                const response = await http.post('/authenticatelogin', {
                    email: formData.user,
                    password: formData.password
                });

                localStorage.setItem('token', response.data.data.token);
                localStorage.setItem('user', JSON.stringify(response.data.data.user));

                this.isLoading = false; 
                this.submitted = true;

                setTimeout(() => {
                    this.$router.push('/');
                }, 2000);

            } catch (error) {
                this.isLoading = false;
                this.submitted = false;

                const errorMessage = error.response?.data.message || "Ismeretlen hiba történt";

                // Különböző hibaüzenetek kezelése
                if (errorMessage.includes("email field must be a valid email")) {
                    toast.error("Hibás az e-mail cím megadása!", {
                        autoClose: 3000,
                        position: toast.POSITION.TOP_CENTER,
                        transition: toast.TRANSITIONS.BOUNCE,
                    });
                } else if (errorMessage.includes("password") || errorMessage.includes("jelszó")) {
                    toast.error("Hibás jelszó!", {
                        autoClose: 3000,
                        position: toast.POSITION.TOP_CENTER,
                        transition: toast.TRANSITIONS.BOUNCE
                    });
                } else if (error.response?.status === 401) {
                    toast.error("Sikertelen bejelentkezés: Hibás felhasználónév vagy jelszó", {
                        autoClose: 3000,
                        position: toast.POSITION.TOP_CENTER,
                        transition: toast.TRANSITIONS.BOUNCE
                    });
                } else {
                    // Általános hibaüzenet
                    toast.error(errorMessage, {
                        autoClose: 3000,
                        position: toast.POSITION.TOP_CENTER,
                        transition: toast.TRANSITIONS.BOUNCE
                    });
                }
            }
        }
    }
}
</script>


<style>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css");
#logintitle{
    font-family: 'Playfair Display', serif;
    font-weight:600;
}

#userinput,
#userpw {
    color: rgb(5, 80, 3);
    padding-inline: 0.5rem;
    text-align: center;
    border-radius: 1rem;
    border-color: rgba(202, 245, 123,0.5);
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
[data-invalid] input#userpw.formkit-input {
    border: 2px solid red;
    margin: 0;
    padding: 0;
}

.formkit-submit {
    background-color: bisque;
}

[data-valid] input#userinput.formkit-input,
[data-valid] input#userpw.formkit-input {
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