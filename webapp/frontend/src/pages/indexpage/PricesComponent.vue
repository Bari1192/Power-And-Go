<template>
  <div class="container mb-20 lg:w-8/12 h-full mx-auto">
    <div class="pb-6 bg-gradient-to-br from-slate-800 to-emerald-900 rounded-2xl shadow-2xl border border-emerald-400">
      <!-- Fejléc -->
      <div class="title w-full mb-12 text-center bg-emerald-600/90 py-8 rounded-t-2xl">
        <h1 class="text-4xl font-bold pt-4 mb-4 text-white">
          Power & Go csomagok, ahogy <b class="special-text">Te</b> szereted!
        </h1>
        <p class="font-semibold text-2xl text-emerald-100">
          <i>Diákoknak, a munkába menet, vagy rendszeres használatra egyaránt!</i>
        </p>
      </div>

      <!-- Kártya konténer -->
      <div class="flex flex-col lg:flex-row lg:px-24 lg:gap-16 justify-center gap-12 px-6 mt-12 p-16">
        <div class="flex-1 transform lg:translate-y-2 order-2 lg:order-1">
          <PassCard
            iconType="fa-solid fa-flag-checkered"
            :packageName="subs[1]?.sub_name"
            :price="subs[1]?.sub_monthly"
            :drivingMin="83"
            :drivingMinDiscounted="41"
            :parkingMin="59"
            :dailyPrice="14680"
            :dailyPriceWeekend="10680"
          />
        </div>
        <div class="flex-1 transform lg:translate-y-4 lg:scale-110 z-10 order-2 lg:order-2">
          <PassCard
            iconType="fa-solid fa-flag-checkered"
            :packageName="subs[2]?.sub_name"
            :price="subs[2]?.sub_monthly"
            :drivingMin="83"
            :drivingMinDiscounted="41"
            :parkingMin="59"
            :dailyPrice="14680"
            :dailyPriceWeekend="10680"
          />
        </div>
        <div class="flex-1 transform lg:translate-y-2 order-3">
          <PassCard
            iconType="fa-solid fa-flag-checkered"
            :packageName="subs[3]?.sub_name"
            :price="subs[3]?.sub_monthly"
            :drivingMin="83"
            :drivingMinDiscounted="41"
            :parkingMin="59"
            :dailyPrice="14680"
            :dailyPriceWeekend="10680"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import PassCard from '@layouts/monthlypasscard/PassCard.vue'
import { onMounted, ref } from 'vue'
import { http } from '@utils/http.mjs'

const subs = ref({})

const getSubs = async () => {
  try {
    const resp = await http.get('/subscriptions')
    subs.value = resp.data.data
  } catch (error) {
    console.log('Nem sikerült lekérni az előfizetési csoportokat!', error)
  }
}

onMounted(() => {
  getSubs()
})
</script>

<style>
.title {
  font-family: 'Playfair Display', serif;
}

.special-text {
  color: #34d399;
  text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

@keyframes glow {
  0%, 100% { text-shadow: 0 0 10px rgba(52, 211, 153, 0.5); }
  50% { text-shadow: 0 0 20px rgba(52, 211, 153, 0.8); }
}
</style>
