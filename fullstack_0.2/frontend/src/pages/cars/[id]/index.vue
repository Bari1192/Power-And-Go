<template>
  <BaseLayout>
    <div class="container mt-20 w-2/3 mx-auto" v-if="car && car.flotta">
      <div class=" w-1/5 border-b-4 border-lime-500  mt-3 mb-3"></div>
      <h1 class="text-5xl text-sky-100 mb-6"> {{ car.rendszam }}</h1>
      <div class=" w-1/5 border-t-4 border-lime-500 mt-3 mb-3"></div>
      <div class="grid grid-cols-3 gap-6">
        <BaseCard :title="'Státusza'" :text="car.statusz" />
        <BaseCard :title="'Becsült hatótáv hátra'" :text="car.becsult_hatotav+' km'" />
        <BaseCard :title="'Töltési energia'" :text="car.toltes_kw" />
        <BaseCard :title="'Töltési százalék'" :text="car.toltes_szazalek" />
        <BaseCard :title="'Gyártó'" :text="car.flotta.gyarto" />
        <BaseCard :title="'Típus'" :text="car.flotta.tipus" />
        <BaseCard :title="'Teljesítmény'" :text="car.flotta.teljesitmeny+' kW'" />
        <BaseCard :title="'Végsebesség'" :text="car.flotta.vegsebesseg+' km/h'" />
        <BaseCard :title="'Gumiméret'" :text="car.flotta.gumimeret" />
        <BaseCard :title="'Hatótáv'" :text="car.flotta.hatotav+' km'" />
        <BaseCard :title="'Futásteljesítmény'" :text="car.km_ora_allas +' km'"/>
        <BaseCard :title="'Gyártási éve'" :text="car.gyartasi_ev" />
        <BaseCard :title="'Felszereltsége'" :text="car.felsz_id_fk+' szintű'" />
        <BaseCard :title="'Flotta azonosító'" :text="car.flotta_id_fk" />
        <BaseCard :title="'Kategória azonosító'" :text="car.kategoria_besorolas_fk"/>
      </div>
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
      car: {}
    }
  },
  async mounted() {
    const response = await http.get(`/cars/${this.$route.params.id}`);
    this.car = response.data.data;
  }
}

</script>