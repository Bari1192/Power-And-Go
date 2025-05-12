<template>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

    <section class="py-8 text-slate-300 border-t-4 border-emerald-800/75 bg-gradient-to-b from-slate-900 to-slate-800">
        <div class="container mx-auto w-full px-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

                <!-- kapcsolat, elérhetőség,infó -->
                <div class="w-full">
                    <h4 class="text-xl text-emerald-400 font-bold mb-4">Elérhetőség</h4>
                    <div class="pt-2 border-t border-emerald-500/20">
                        <a href="#"
                            class="flex items-center space-x-2 py-2 hover:text-emerald-400 transition-colors duration-200">
                            <i class="fa-solid fa-map-location-dot text-emerald-300/90"></i>
                            <div>1111 Kozma u. 13.</div>
                        </a>
                        <a href="#"
                            class="flex items-center space-x-2 py-2 hover:text-emerald-400 transition-colors duration-200">
                            <i class="fa fa-phone text-emerald-300/90"></i>
                            <div>+36 1 123 4567</div>
                        </a>
                        <a href="#"
                            class="flex items-center space-x-2 py-2 hover:text-emerald-400 transition-colors duration-200">
                            <i class="fa fa-envelope text-emerald-300/90"></i>
                            <div>info@powerandgo.com</div>
                        </a>
                    </div>

                    <div class="flex space-x-4 py-4 border-y border-emerald-500/20 mt-4">
                        <a href="" class="facebook-icon">
                            <i class="fab fa-facebook fa-2x"></i>
                        </a>
                        <a href="" class="twitter-icon">
                            <i class="fab fa-twitter fa-2x"></i>
                        </a>
                        <a href="" class="linkedin-icon">
                            <i class="fab fa-linkedin fa-2x"></i>
                        </a>
                        <a href="" class="instagram-icon">
                            <i class="fab fa-instagram fa-2x"></i>
                        </a>
                    </div>
                </div>

                <!-- info section -->
                <div class="w-full">
                    <h4 class="text-xl text-emerald-400 font-bold mb-4">Információk</h4>
                    <div class="space-y-4 border-t border-emerald-500/20 pt-2">
                        <p class="text-sm">
                            Fenntarthatóság és a megújulás a fő erényünk. Ezért tartjuk kiemelten fontosnak, hogy
                            flottánkban kizárólag elektromos autók alkossák.
                        </p>
                        <p class="text-sm">
                            Mindezekkel hozzájárulsz az élhető városi levegő kialakításához, a kényelmes és gyors
                            utazások során.
                        </p>
                    </div>
                </div>

                <!-- hasznos Linkek -->
                <div class="w-full">
                    <h4 class="text-xl text-emerald-400 font-bold mb-4">Hasznos Linkek</h4>
                    <div class="grid grid-cols-2 gap-2 border-t border-emerald-500/20 pt-2">
                        <a href="#" class="text-sm hover:text-emerald-400 transition-colors duration-200">Főoldal</a>
                        <a href="#"
                            class="text-sm hover:text-emerald-400 transition-colors duration-200">Bejelentkezés</a>
                        <a href="#" class="text-sm hover:text-emerald-400 transition-colors duration-200">Rólunk</a>
                        <a href="#"
                            class="text-sm hover:text-emerald-400 transition-colors duration-200">Regisztráció</a>
                        <a href="#"
                            class="text-sm hover:text-emerald-400 transition-colors duration-200">Kedvezmények</a>
                        <a href="#"
                            class="text-sm hover:text-emerald-400 transition-colors duration-200">Elérhetőség</a>
                        <a href="#" class="text-sm hover:text-emerald-400 transition-colors duration-200">Flotta</a>
                        <a href="#"
                            class="text-sm hover:text-emerald-400 transition-colors duration-200">Fenntarthatóság</a>
                        <a href="#" class="text-sm hover:text-emerald-400 transition-colors duration-200">Cégeknek</a>
                        <a href="#"
                            class="text-sm hover:text-emerald-400 transition-colors duration-200">Megjelenéseink</a>
                    </div>
                </div>

                <!-- feliratkozásos -->
                <div class="w-full">
                    <h4 class="text-xl text-emerald-400 font-bold mb-4">Feliratkozás</h4>
                    <div class="border-t border-emerald-500/20 pt-2">
                        <h5 class="text-sm italic mb-4">Értesülj elsőként az újdonságainkról!</h5>
                        <form action="#" class="space-y-3">
                            <input type="text" placeholder="e-mail cím"
                                class="w-full p-2 bg-slate-800/50 border border-emerald-500/20 rounded-xl text-slate-300 placeholder-slate-500 focus:outline-none focus:border-emerald-500 transition-colors duration-200" />
                            <button type="submit"
                                class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-semibold py-2 px-4 rounded-xl transition-all duration-200">
                                Hírlevél feliratkozás
                            </button>
                            <div class="text-xs italic opacity-75">
                                *A hírlevélre való feliratkozással elfogadod az általános <u
                                    class="cursor-pointer hover:text-emerald-400">szerződési feltételeket.</u>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
</template>

<style scoped>
.facebook-icon,
.twitter-icon,
.linkedin-icon,
.instagram-icon {
    @apply text-slate-300 transition-all duration-200;
}

.facebook-icon:hover {
    color: #3f73db;
}

.twitter-icon:hover {
    color: #1DA1F2;
    filter: brightness(1.2);
}

.linkedin-icon:hover {
    color: #68a9ee;
    filter: brightness(1.2);
}

.instagram-icon:hover {
    background: -webkit-linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    filter: brightness(1.2);
}
</style>