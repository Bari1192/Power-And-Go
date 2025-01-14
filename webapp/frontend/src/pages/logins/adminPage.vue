<template>
    <BaseLayout>
        <div class="m-auto my-8 d-flex justify-center border-4 rounded-2xl border-sky-300 w-1/3 mb-20">
            <p
                class="text-center tracking-wide text-4xl font-semibold text-white bg-sky-400 border-lime-400 border-t-2 border-b-2 rounded-md py-3 my-8 ">
                Login Panel
            </p>

            <div class="w-full text-sky-300 font-semibold text-2xl d-flex m-auto text-center justify-center my-8">
                <FormKit type="form" id="login" @submit="submitHandler" :actions="false">

                    <FormKit name="user" label="Felhasználónév" label-class="text-sky-300" id="userinput"
                        :validation="[['required'], ['matches', /^\d{10,20}$/]]" :validation-messages="{
                            matches: 'Hibás a felhasználónév megadása!',
                            required: 'Kötelező megadni!',
                        }" />

                    <FormKit name="password" type="password" id="userpw" label="Jelszó"
                        :validation="[['required'], ['matches', /^\d{4,8}$/]]" :validation-messages="{
                            matches: 'A jelszónak 4-8 számból kell állnia.',
                            required: 'Kötelező megadni!',
                        }" />
                    <div class="flex justify-center my-12">
                        <FormKit type="submit" label="Bejelentkezés"
                            class="bg-blue-500 hover:bg-blue-400 text-lime-300 text-2xl font-semibold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded" />
                    </div>
                    <div v-if="submitted">
                        <h2 class="text-xl text-green-500">Bejelentkezés sikeres!</h2>
                    </div>
                </FormKit>
            </div>
        </div>
    </BaseLayout>
</template>


<script>
import { http } from '@utils/http.mjs';
import BaseLayout from '@layouts/BaseLayout.vue'


export default {
    data() {
        return {
            username: '', // Felhasználónév tárolása
            password: '', // Jelszó tárolása
            submitted: false
        }
    },
    components: {
        BaseLayout
    },
    watch: {
        // Felhasználónév figyelése
        username(newValue) {
            this.validateUsername(newValue);
        },
        // Jelszó figyelése
        password(newValue) {
            this.validatePassword(newValue);
        }
    },
    methods() {
        submitHandler(values);
        {
            this.submitted = true;
        }
    }
}

</script>

<style>
#userinput,
#userpw {
    color: rgb(3, 74, 103);
    padding-inline: 0.5rem;
    text-align: center;
    border-radius: 1rem;
    border-color: rgb(1, 133, 185);
    border-width: 2px;
    margin-top: 0.5rem;
}

[data-invalid] .formkit-message {
    color: red;
    font-size: 16px;
}

[data-valid] .formkit-inner {
    border: 2px solid green;
}

[data-loading] .formkit-inner::after {
    content: '⏳';
    display: inline-block;
    margin-left: 5px;
}
</style>