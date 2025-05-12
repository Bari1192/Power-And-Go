<template>
    <div class="flex flex-col items-center justify-between p-8 min-h-[400px]">
        <!-- Banner szekció -->
        <div class="w-full text-center mb-4">
            <p class="text-emerald-400 text-2xl font-bold tracking-wide">
                {{ newsBanner }}
            </p>
        </div>

        <div class="grid flex-col grid-cols-2 items-center justify-center w-full gap-8">
            <!-- Szöveg szekció -->
            <div
                class="detail-box grid grid-cols-4 text-left bg-black bg-opacity-50 rounded-2xl p-8 md:w-2/5 backdrop-blur-sm border border-emerald-500/30">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-4">{{ titleAD }}</h1>
                </div>



                <div class="w-fit h-fit">
                    <p class="text-lg text-gray-200 leading-relaxed">{{ adDescription }}</p>
                </div>


            </div>

            <!-- Kép szekció -->
            <div class="img-box md:w-2/5">
                <img :src="imgSrc" :alt="imgAlt"
                    class="w-full max-w-md mx-auto hover:scale-105 transition-transform duration-300 drop-shadow-2xl" />
            </div>
        </div>
        <div class="grid grid-cols-2 flex-row justify-around items-center">
            <div class="mt-8 flex w-fit h-fit">
                <a :href="linkToWhere" class="bg-emerald-600 hover:bg-emerald-700 text-white text-lg font-semibold px-8 py-3 rounded-xl
                  transition-all duration-300 ease-in-out transform
                  shadow-lg hover:shadow-emerald-500/50">
                    {{ linkDescription }}
                </a>
            </div>

            <div class="mt-8 flex w-fit h-fit">
                <h2 class="text-2xl text-emerald-400 font-bold mb-6 border-b-2 border-emerald-500 pb-2 w-fit">
                    {{ modelName }}
                </h2>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    newsBanner: {
        type: String,
        default: ''
    },
    titleAD: {
        type: String,
        required: true
    },
    modelName: {
        type: String,
        required: true
    },
    adDescription: {
        type: String,
        required: true
    },
    linkDescription: {
        type: String,
        required: true
    },
    imgSrc: {
        type: String,
        required: true
    },
    linkToWhere: {
        type: String,
        required: true
    },
    imgAlt: {
        type: String,
        required: true
    }
})
</script>

<style scoped>
.img-box img {
    max-width: 90%;
    height: auto;
    filter: drop-shadow(0 0 10px rgba(16, 185, 129, 0.2));
}

@keyframes float {
    0% {
        transform: translateY(0px);
    }

    50% {
        transform: translateY(-10px);
    }

    100% {
        transform: translateY(0px);
    }
}

.img-box img {
    animation: float 3s ease-in-out infinite;
}
</style>