<template>
    <svg viewBox="0 0 1630 320" class="p-0 -mt-52 max-h-full max-w-screen scale-x">
        <defs>
            <mask id="carMask">
                <rect width="100%" height="100%" fill="lime" />
                <image href="@assets/img/Welcome/eup.png" x="700" y="255" width="150" height="80" fill=""
                    class="car-image car-1" />
                <image href="@assets/img/Welcome/kianiro.png" x="700" y="251" width="150" height="100" fill=""
                    class="car-image car-2" />
                <image href="@assets/img/Welcome/skodacitigo.webp" x="700" y="268" width="150" height="70" fill=""
                    class="car-image car-3" />
            </mask>
        </defs>

        <path mask="url(#carMask)" fill="ForestGreen" fill-opacity="0.9"
            d="M0,256L80,229.3C160,203,320,149,480,149.3C640,149,800,203,960,229.3C1120,256,1280,256,1360,256L1630,256L1630,320L1360,320C1280,320,1120,320,960,320C800,320,640,320,480,320C320,320,160,320,80,320L0,320Z">
        </path>
    </svg>
</template>

<style>
@keyframes moveCar {
    0% {
        opacity: 0;
        transform: translateX(100%);
    }
    5% {
        opacity: 1;
        transform: translateX(90%);
    }
    95% {
        opacity: 1;
        transform: translateX(-90%);
    }
    100% {
        opacity: 0;
        transform: translateX(-100%);
    }
}

.car-image {
    opacity: 0; /* Kezdeti állapotban láthatatlan */
    animation: moveCar 25s linear infinite;
    will-change: transform;
    animation-play-state: running;
}

.car-1 {
    animation-delay: 0s;
}

.car-2 {
    animation-delay: 10s; /* Növeltem a késleltetést */
}

.car-3 {
    animation-delay: 20s; /* Növeltem a késleltetést */
}
</style>