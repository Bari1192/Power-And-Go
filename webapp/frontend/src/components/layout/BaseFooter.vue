<template>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <section class="py-8 text-white border-t-4 border-lime-500 bg-teal-600 ">
        <div class="container mx-auto w-full ">
            <div class="grid grid-cols-1 px-4 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 ">
                <!-- Contact Info -->
                <div class="w-3/4 mx-auto md:w-full md:mx-auto lg:mx-auto lg:w-auto">
                    <h4 class="text-2xl text-lime-300 font-bold mb-4">Elérhetőség</h4>
                    <div
                        class="pt-2 2xl:text-lg font-semibold border-t-2 border-gray-100 border-opacity-10 md:max-w-fit">
                        <a href="#" class="flex items-center space-x-2 py-1">
                            <i class="fa-solid fa-map-location-dot text-center" aria-hidden="true"></i>
                            <div class="hover:text-lime-300">1111 Kozma u. 13.</div>
                        </a>
                        <a href="#" class="flex items-center space-x-2 py-1">
                            <i class="fa fa-phone text-center" aria-hidden="true"></i>
                            <div class="hover:text-lime-300">+36 1 123 4567</div>
                        </a>
                        <a href="#" class="flex items-center space-x-2 py-1">
                            <i class="fa fa-envelope text-center" aria-hidden="true"></i>
                            <div class="hover:text-lime-300">info@powerandgo.com</div>
                        </a>
                    </div>
                    <div
                        class="flex justify-normal space-x-4 max-w-fit lg:justify-between  sm:justify-normal sm:space-x-4 sm:max-w-fit md:max-w-fit md:justify-start md:space-x-5 md:mt-4 md:py-4 py-3 border-y-2 border-gray-100 border-dashed border-opacity-20 ">
                        <a href="" class="text-sky-600 hover:text-sky-800 ">
                            <i class="fab fa-facebook fa-2x bg-white shadow-md rounded-full " aria-hidden="true"></i>
                        </a>
                        <a href="" class="text-blue-50 hover:text-gray-300 ">
                            <i class="fab fa-twitter fa-2x" aria-hidden="true"></i>
                        </a>
                        <a href="" class="text-sky-400 bg-white hover:text-sky-500 ">
                            <i class="fab fa-linkedin fa-2x border-y-2 border-sky-500 "></i>
                        </a>
                        <a href="" class="text-pink-500 hover:text-red-800 ">
                            <i class="fab fa-instagram fa-2x " aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

                <!-- Info Detail -->
                <div class="w-3/4 mx-auto md:w-full">
                    <h4 class="text-2xl text-lime-300 font-bold mb-4">Információk</h4>
                    <p class="py-2 border-t-2 text-base 2xl:text-lg border-gray-100 border-opacity-10">
                        Fenntarthatóság és a megújulás a fő erényünk. Ezért tartjuk kiemelten fontosnak, hogy
                        flottánkban kizárólag elektromos autók alkossák.
                    </p>
                    <p class="border-t-2 2xl:text-lg text-base border-gray-100 border-opacity-10 py-2">Mindezekkel
                        hozzájárulsz az élhető városi levegő kialakításához, a kényelmes és gyors
                        utazások során.</p>
                </div>

                <div class="w-3/4 mx-auto md:w-full ">
                    <h4 class="text-2xl text-lime-300 font-bold my-4">Hasznos Linkek</h4>
                    <div class="grid grid-cols-2 gap-2 w-full text-base xl:text-lg font-semibold">
                        <a href="#" class="hover:text-lime-300">Főoldal</a>
                        <a href="#" class="hover:text-lime-300">Bejelentkezés</a>
                        <a href="#" class="hover:text-lime-300">Rólunk</a>
                        <a href="#" class="hover:text-lime-300">Regisztráció</a>
                        <a href="#" class="hover:text-lime-300">Kedvezmények</a>
                        <a href="#" class="hover:text-lime-300">Elérhetőség</a>
                        <a href="#" class="hover:text-lime-300">Flotta</a>
                        <a href="#" class="hover:text-lime-300">Fenntarthatóság</a>
                        <a href="#" class="hover:text-lime-300">Cégeknek</a>
                        <a href="#" class="hover:text-lime-300">Megjelenéseink</a>
                    </div>
                </div>

                <div class="w-3/4 mx-auto lg:mx-4 lg:w-full mt-4 sm:mt-0">
                    <h4 class="text-2xl text-lime-300 font-bold uppercase mb-4">Feliratkozás</h4>
                    <h5
                        class="font-semibold py-2 2xl:text-xl lg:text-lg md:text-sm text-amber-50 italic border-t-2 border-gray-100 border-opacity-10">
                        Értesülj elsőkként az újdonságainkról!
                    </h5>
                    <form action="#" class="space-y-3">
                        <input type="text" placeholder="e-mail cím"
                            class="w-full p-2 pl-2 border text-teal-600 font-semibold border-lime-400 rounded-md" />
                        <button type="submit" id="newsletter"
                            class="w-full bg-lime-500 text-amber-50 font-semibold py-2 rounded-md hover:bg-lime-600 hover:border hover:border-white hover:border-opacity-25 hover:text-amber-50">
                            Hírlevél feliratkozás
                        </button>
                        <div class="w-full italic text-sm lg:text-xs md:text-left xl:px-2 ">*A hírlevélre való
                            feliratkozással elfogadod az
                            általános <u>szerződési feltételeket.</u></div>
                    </form>
                </div>
            </div>
        </div>

    </section>
</template>

<style>
#newsletter {
    cursor: pointer;
    color: rgba(255, 255, 255, 0.9) !important;
    padding: 10px;
    background-size: 200% 200%;
    background-image: linear-gradient(145deg,
            rgba(139, 194, 36, 0.8) 0%,
            rgba(217, 138, 2, 0.6) 50%,
            rgba(139, 194, 36, 0.9) 50%,
            rgba(217, 138, 2, 0.8) 100%);
    background-position: 0 0;
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    box-shadow:
        0 4px 15px rgba(0, 0, 0, 0.1),
        inset 0 0 0 1px rgba(255, 255, 255, 0.1);
    transition: all 0.7s ease;
}

#newsletter:hover {
    background-position: 100% 100%;
    box-shadow:
        0 6px 20px rgba(0, 0, 0, 0.15),
        inset 0 0 0 1px rgba(255, 255, 255, 0.2);
}
</style>