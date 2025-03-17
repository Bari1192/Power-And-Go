<template>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <section class="info_section py-8 text-white border-t-4 border-lime-500 bg-teal-600">
        <div class="container mx-auto ">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Contact Info -->
                <div class="info_col">
                    <div class="info_contact">
                        <h4 class="text-2xl font-bold mb-4">Elérhetőség</h4>
                        <div class="contact_link_box space-y-3 ">
                            <a href="#" class="flex items-center space-x-2">
                                <i class="fa-solid fa-map-location-dot" aria-hidden="true"></i>
                                <span>Cím</span>
                            </a>
                            <a href="#" class="flex items-center space-x-2">
                                <i class="fa fa-phone" aria-hidden="true"></i>
                                <span>+36 1 1234567890</span>
                            </a>
                            <a href="#" class="flex items-center space-x-2">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                                <span>info@powerandgo.com</span>
                            </a>
                        </div>
                    </div>
                    <div class="info_social space-x-6 mt-6 py-4 border-y-2 max-w-fit border-gray-100 border-dashed border-opacity-20">
                        <a href="" class="text-sky-400 hover:text-sky-500">
                            <i class="fab fa-facebook fa-2x " aria-hidden="true"></i>
                        </a>
                        <a href="" class="text-yellow-200 w-4 h-4 hover:text-white">
                            <i class="fab fa-twitter fa-2x " aria-hidden="true"></i>
                        </a>
                        <a href="" class="text-sky-400 w-4 h-4 bg-white hover:text-sky-500">
                            <i class="fab fa-linkedin fa-2x" aria-hidden="true"></i>
                        </a>
                        <a href="" class="text-lime-400 w-4 h-4 hover:text-pink-500">
                            <i class="fab fa-instagram fa-2x" aria-hidden="true"></i>
                        </a>
                    </div>

                </div>

                <!-- Info Detail -->
                <div class="info_col">
                    <div class="info_detail">
                        <h4 class="text-2xl font-bold mb-4">Információk</h4>
                        <p class="py-2 font-semibold border-t-2 border-gray-100 border-opacity-10">
                            Fenntarthatóság és a megújulás a fő erényünk. Ezért tartjuk kiemelten fontosnak, hogy
                            flottánkban kizárólag elektromos autók alkossák.
                        </p>
                        <p class="border-t-2 border-gray-100 border-opacity-10 py-2 font-semibold">Mindezekkel hozzájárulsz az élhető városi levegő kialakításához, a kényelmes és gyors
                            utazások során.</p>
                    </div>
                </div>

                <div class="w-full lg:w-5/6 mx-auto info_col">
                    <div class="info_link_box">
                        <h4 class="text-2xl text-start font-bold mb-4">Hasznos Linkek</h4>

                        <div class="flex flex-row justify-between ">
                            <div class="info_links flex flex-col space-y-2 font-semibold">
                                <Router-link to="../">
                                    <span class="self-center hover:text-lime-500"> Főoldal </span></Router-link>
                                <Router-link to="../" >
                                    <span class="self-center hover:text-lime-500"> Rólunk </span></Router-link>
                                <Router-link to="../">
                                    <span class="self-center hover:text-lime-500"> Kedvezmények </span></Router-link>
                                <Router-link to="../">
                                    <span class="self-center hover:text-lime-500"> Flotta </span></Router-link>
                                <Router-link to="../">
                                    <span class="self-center hover:text-lime-500"> Cégeknek </span></Router-link>
                            </div>

                            <div class="info_links flex flex-col space-y-2 font-semibold">
                                <a class="hover:text-lime-500" href="index.html">Bejelentkezés</a>

                                <Router-link to="/registers/registerPage" active-class="text-lime-700">
                                    <span class="self-center hover:text-lime-500"> Regisztráció </span></Router-link>

                                <a class="hover:text-lime-500" href="team.html">Elérhetőség</a>
                                <a class="hover:text-lime-500" href="why.html">Fenntarthatóság</a>
                                <a class="hover:text-lime-500" href="service.html">Megjelenéseink</a>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="info_col">
                    <h4 class="text-2xl font-bold uppercase mb-4">Feliratkozás</h4>
                    <h5 class="font-semibold text-sky-200 italic mb-1">Értesülj elsőkként az újdonságokról!</h5>
                    <form action="#" class="space-y-3">
                        <input type="text" placeholder="e-mail cím"
                            class="w-full p-2 pl-4 border border-lime-400 rounded-md" />
                        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">
                            Hírlevél feliratkozás
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <router-view></router-view>

    </section>
</template>