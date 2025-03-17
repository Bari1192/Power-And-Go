<template>
    <svg viewBox="0 0 1630 320" class="p-0 -mt-52 max-h-full max-w-screen scale-x">
        <defs>
            <mask id="carMask">
                <rect width="100%" height="100%" fill="lime" />
                <image href="@img/Models/eup.png" x="225" y="189" width="250" height="180" fill="white"
                    class="car-image car-1" />
                <image href="@img/Models/kianiro.png" x="547" y="209" width="240" height="160" fill="black"
                    class="car-image car-2" />
                 <image href="@img/Models/skodacitigo.webp" x="925" y="207" width="160" height="180" fill="black"
                    class="car-image car-3" /> 
                 <image href="@img/Models/vivaroE.png" x="1230" y="201" width="160" height="180" fill="white"
                    class="car-image car-4" /> 
            </mask>
        </defs>

        <path mask="url(#carMask)" fill="ForestGreen" fill-opacity="0.9"
            d="M0,256L80,229.3C160,203,320,149,480,149.3C640,149,800,203,960,229.3C1120,256,1280,256,1360,256L1630,256L1630,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z">
        </path>
    </svg>
</template>

<style>
.car-image {
    /*  */
}

.car-1 {
    /*  */
}

.car-2 {
    /*  */
}

.car-3 {
    /*  */
}
</style>