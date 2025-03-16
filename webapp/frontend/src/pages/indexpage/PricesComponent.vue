<template>
  <div class="container mb-20 lg:w-5/6 h-full mx-auto bg-white rounded-2xl mt-8 shadow-2xl">
    <div class="pb-6 bg-lime-600 bg-opacity-30 rounded-2xl mt-6 border-4 border-yellow-100">

      <div class="title w-full mb-12 text-center bg-lime-600 bg-opacity-75 text-white py-8 rounded-2xl rounded-b-none">
        <h1 class="text-4xl font-bold pt-4 mb-4">Power & Go csomagok, ahogy <b class="ahogyteszereted">Te</b> szereted!
        </h1>
        <p class="font-semibold text-3xl"><i>Diákoknak, a munkába menet, vagy rendszeres használatra egyaránt!</i>
        </p>
      </div>


      <div class="flex flex-col lg:flex-row lg:px-24 lg:gap-16 justify-center gap-12 px-6 mt-12 p-16">
        <div class="flex-1 transform lg:translate-y-2 order-2 lg:order-1">
          <PassCard iconType="fa-solid fa-flag-checkered" packageName="Power-Plus" :price="1490" :drivingMin="83"
            :drivingMinDiscounted="41" :parkingMin="59" :dailyPrice="14680" :dailyPriceWeekend="10680" />
        </div>

        <div class="flex-1 transform lg:translate-y-4 lg:scale-110 z-10 order-2 lg:order-2">
          <PassCard iconType="fa-solid fa-crown" packageName="Power-VIP" :price="5990" :drivingMin="59"
            :drivingMinDiscounted="47" :parkingMin="59" :dailyPrice="14680" :dailyPriceWeekend="10680" />
        </div>

        <div class="flex-1 transform lg:translate-y-2 order-3">
          <PassCard iconType="fa-solid fa-shield" packageName="Power-Prémium" :price="2490" :drivingMin="83"
            :drivingMinDiscounted="41" :parkingMin="59" :dailyPrice="14680" :dailyPriceWeekend="10680" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import PassCard from '@layouts/monthlypasscard/PassCard.vue'
export default {
  components: {
    PassCard,
  },
};

</script>

<style>
.title {
  font-family: 'Playfair Display', serif;
}
.ahogyteszereted{
  color: yellow;
  text-shadow: 1px 1px 1px orange;
}
</style>