<template>
  <BaseLayout>
    <div class="container mt-20 w-1/3 mx-auto">
      <h1 class="text-5xl text-sky-800"> {{ car.rendszam }}</h1>
      <BaseCard :title="'km óra'" :text="car.km_ora_allas" />
      <BaseCard :title="'Gyártási év'" :text="car.gyartasi_ev" />
      <BaseCard :title="'Felszereltség'" :text="car.felsz_id_fk" />
      <BaseCard :title="'Flotta ID'" :text="car.flotta_id_fk" />
      <BaseCard :title="'Kategória'" :text="car.kategoria_besorolas_fk" />

    </div>
  </BaseLayout>
</template>

<script>
import { http } from '@utils/http'
import BaseCard from '@layouts/BaseCard.vue'
import BaseLayout from "@layouts/BaseLayout.vue";




export default {
  components: {
    BaseCard,
    BaseLayout,
  },
  data() {
    return {
      car: Array
    }
  },
  async mounted() {
    const response = await http.get(`/cars/${this.$route.params.id}`);
    this.car = response.data.data;
  }
}

</script>