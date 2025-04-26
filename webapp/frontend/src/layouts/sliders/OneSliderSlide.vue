<template>
    <div
        class="row rounded-tl-2xl w-2/5 p-2 h-16 flex flex-col md:flex-row items-center justify-between rounded-br-xl bg-lime-500 shadow-2xl border-r-4 border-b-4 border-white border-opacity-75">
        <p class="col flex mx-auto align-bottom font-semibold text-yellow-100 text-2xl">{{ newsBanner }}</p>
    </div>

    <div class="mx-auto w-4/5 flex flex-col md:flex-row items-center justify-between">
        <div class="detail-box text-left bg-yellow-500 bg-opacity-85 rounded-3xl p-6 md:w-2/5">
            <h1 class="text-3xl font-bold uppercase mb-4">{{ titleAD }}</h1>
            <h2 class="text-2xl text-white  font-semibold italic border-b-4 border-dashed border-opacity-50 border-spacing-6 border-orange-600 w-fit">
                {{ modelName }}
            </h2>
            <div class="w-full">
                <p class="text-xl text-sky-50 mt-4">{{ adDescription }}</p>
            </div>
            <div class="mt-8 flex w-full space-x-4">
                <a :href="linkToWhere"
                    class="bg-lime-600 border-2 border-yellow-200 hover:bg-lime-700 text-white  hover:border-lime-500 hover:text-white text-xl font-semibold px-6 py-2 rounded-xl transition-all duration-300 ease-in-out active:translate-y-[2px]">
                    {{ linkDescription }}
                </a>
            </div>
        </div>
        <div class="img-box md:w-2/5">
            <img :src="imgSrc" :alt="imgAlt" class="w-full max-w-md mx-auto animate-up-down" />
        </div>
    </div>
</template>

<script>
export default {
    props: {
        newsBanner: {
            type: String,
        },
        titleAD: {
            type: String,
            required: true,
        },
        modelName: {
            type: String,
            required: true,
        },
        adDescription: {
            type: String,
            required: true,
        },
        linkDescription: {
            type: String,
            required: true,
        },
        imgSrc: {
            type: String,
            required: true,
        },
        linkToWhere: {
            type: String,
            required: true,
        },
        imgAlt: {
            type: String,
            required: true,
        },
    }
}
</script>
<style>
img{
    max-width: 80% !important;
    max-height: 80% !important;
}

</style>