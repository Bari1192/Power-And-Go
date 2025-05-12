<template>
  <div class="price-item bg-slate-800 rounded-2xl shadow-xl border border-emerald-500/30 
    transition-all duration-300 hover:scale-[1.02] hover:shadow-emerald-500/20 origin-bottom">
    <!-- Kör Ikonok  -->
    <div class="icon-circle-container">
      <div class="icon-circle">
        <i :class="iconType"></i>
      </div>
    </div>

    <!-- Csomag -->
    <div class="p-5 text-center">
      <h5 class="text-3xl text-emerald-400 font-bold mt-2 pb-4 border-b border-emerald-500/20">
        {{ packageName }}
      </h5>
    </div>

    <!-- Havi ár -->
    <div class="border-b border-emerald-500/20 py-4 bg-emerald-900/30">
      <p class="text-4xl flex justify-center items-center font-bold mb-1 text-white">
        {{ price }} Ft
      </p>
      <p class="text-xl flex text-emerald-200/80 justify-center items-center mb-2">
        <i>{{ period }}</i>
      </p>
    </div>

    <!-- Részletes árlista -->
    <div class="p-6 bg-slate-800/50">
      <ul class="space-y-4 text-lg">
        <li class="flex items-center text-gray-100">
          <i class="fa fa-check mr-4 text-emerald-400"></i>
          <span><b class="text-emerald-400">Vezetés</b> {{ drivingMin }} Ft/perc</span>
        </li>
        <li class="flex items-center text-gray-100">
          <i class="fa fa-check mr-4 text-emerald-400"></i>
          <span><b class="text-emerald-400">Kedvezményes vezetés</b> akár {{ drivingMinDiscounted }} Ft/perc</span>
        </li>
        <li class="flex items-center text-gray-100">
          <i class="fa fa-check mr-4 text-emerald-400"></i>
          <span><b class="text-emerald-400">Parkolás</b> {{ parkingMin }} Ft/perc</span>
        </li>
        <li class="flex items-center text-gray-100">
          <i class="fa fa-check mr-4 text-emerald-400"></i>
          <span><b class="text-emerald-400">Napi bérlés</b> már {{ dailyPrice }} Ft-tól</span>
        </li>
        <li class="flex items-center text-gray-100">
          <i class="fa fa-check mr-4 text-emerald-400"></i>
          <span><b class="text-emerald-400">Hétvégi</b> napi bérlés már {{ dailyPriceWeekend }}-tól</span>
        </li>
      </ul>
    </div>

    <!-- Gomb -->
    <div class="mt-5 py-2 text-center border-t border-emerald-500/20">
      <button class="rounded-xl lg:w-4/5 w-fit font-semibold my-3 px-4 py-2 bg-emerald-600 text-white
        transition-all duration-300 hover:bg-emerald-500 shadow-sm hover:shadow-emerald-500/50">
        {{ buttonText }}
      </button>
    </div>
  </div>
</template>

<script setup>
defineProps({
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
    default: 'Megnézem'
  }
})
</script>

<style scoped>
.price-item {
  position: relative;
  backdrop-filter: blur(8px);
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
  width: 60px;
  height: 60px;
  background: linear-gradient(135deg, #059669, #065f46);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-top: -30px;
  border: 3px solid #34d399;
  box-shadow: 0 4px 12px rgba(52, 211, 153, 0.3);
  z-index: 2;
}

.price-item:hover .icon-circle {
  box-shadow: 0 6px 16px rgba(52, 211, 153, 0.4);
}

.icon-circle i {
  color: #ecfdf5;
  font-size: 24px;
}

.price-item:hover {
  transition: all;
  transform: scale(translate-y-2 ease-in-out);
  transition-duration: 0.5s;
  
}
</style>
