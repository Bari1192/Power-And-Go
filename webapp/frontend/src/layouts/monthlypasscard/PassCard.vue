<template>
  <div
    class="price-item lg:gap-4 bg-teal-700 rounded-2xl shadow-lg shadow-green-800 price-border-primary border-t-8 border-r-4 border-lime-500 ">
    <div class="icon-circle-container">
      <div class="icon-circle">
        <i :class="iconType"></i>
      </div>
    </div>

    <div class="p-5 text-center">
      <h5 class="text-4xl text-lime-400 font-bold mt-2 pb-4 border-b-4 border-dotted border-gray-200 border-opacity-15">
        {{ packageName }}
      </h5>
    </div>

    <div class=" border-b-2  border-slate-200 border-double border-opacity-30">
      <p class="text-2xl flex justify-center align-middle font-bold mb-1">{{ price }} Ft</p>
      <p class="text-xl flex text-gray-200 justify-center align-middle mb-2"><i>{{ period }}</i></p>
    </div>
    <div class="p-5">
      <ul class="space-y-3 text-lg pl-2">
        <li class="flex items-center">
          <i class="fa fa-check mr-6"></i>
          <span><b>Vezetés</b> {{ drivingMin }} Ft/perc </span>
        </li>
        <li class="flex items-center">
          <i class="fa fa-check mr-6"></i>
          <span><b>Kedvezményes vezetés</b> akár {{ drivingMinDiscounted }} Ft/perc</span>
        </li>
        <li class="flex items-center">
          <i class="fa fa-check mr-6"></i>
          <span><b>Parkolás</b> {{ parkingMin }} Ft/perc </span>
        </li>
        <li class="flex items-center">
          <i class="fa fa-check mr-6"></i>
          <span><b>Napi bérlés</b> már {{ dailyPrice }}Ft-tól</span>
        </li>
        <li class="flex items-center">
          <i class="fa fa-check mr-6"></i>
          <span><b>Hétvégi</b> napi bérlés már {{ dailyPriceWeekend }}-tól</span>
        </li>
      </ul>
    </div>
    <div class="mt-5 py-6 text-center border-t-2 border-slate-200 border-double border-opacity-30">
      <button class="rounded-xl font-semibold py-2 px-6 hover:bg-lime-500 bg-lime-600">
        {{ buttonText }}</button>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    iconType: {
    type: String,
    default: 'fa-solid fa-car' 
  },
    packageName: String,
    price: [Number, String],
    period: {
      type: String,
      default: 'Havonta'
    },
    drivingMin: Number,
    drivingMinDiscounted: Number,
    parkingMin: Number,
    dailyPrice: Number,
    dailyPriceWeekend: Number,
    buttonText: {
      type: String,
      default: 'További Kedvezmények'
    }
  }
}
</script>

<style scoped>
.price-item {
  position: relative;
}

.icon-circle-container {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  display: flex;
  justify-content: center;
}

.icon-circle {
  width: 50px;
  height: 50px;
  background-color: rgb(2, 86, 58); 
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-top: -25px;
  border: 3px solid rgb(255, 170, 1); 
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
  z-index: 2;
}
.icon-circle i {
  color: yellow ;
  font-size: 20px;
}
</style>