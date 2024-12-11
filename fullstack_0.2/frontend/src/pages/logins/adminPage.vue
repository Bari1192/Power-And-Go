<template>
    <BaseLayout>
        <div
            class="m-auto d-flex justify-center p-3 my-10 w-3/4 border-2 rounded-2xl border-sky-300 dark:text-sky-100 dark:italic dark:font-semibold ">

            <h2 class="text-xl font-bold mb-4">Support ticket</h2>
            <FormKit type="form">
                <!-- <ul class="validation-errors" v-if="messages.length">
                    <li v-for="message in messages">{{ message }}</li>
                </ul> -->
                <FormKit name="title" label="Ticket title" help="A short sentence that describes your problem."
                    validation="required" />
                <FormKit name="issue" type="textarea" label="Issue" help="Please describe your issue in detail."
                    validation="required|length:50,500" />
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
            fleets: [],
        }
    },
    components: {
        BaseLayout,
    },
    // async mounted() {
    //     try {
    //         const resp = await http.get('/szemelyek');
    //         this.fleets = resp.data.data;
    //     } catch (error) {
    //         console.error('Hiba történt az API hívás során:', error);
    //     }
    // },
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
