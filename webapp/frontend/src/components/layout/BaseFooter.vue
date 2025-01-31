<template>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <section class="info_section py-8 text-white border-t-4 border-sky-700">
        <div class="container mx-auto ">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Contact Info -->
                <div class="info_col">
                    <div class="info_contact">
                        <h4 class="text-2xl font-bold mb-4">Elérhetőség</h4>
                        <div class="contact_link_box space-y-3">
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
                    <div class="info_social space-x-6 mt-6">
                        <a href="" class="text-sky-300 hover:text-lime-500">
                            <i class="fab fa-facebook fa-2x " aria-hidden="true"></i>
                        </a>
                        <a href="" class="text-sky-300 w-4 h-4 hover:text-lime-500">
                            <i class="fab fa-twitter fa-2x" aria-hidden="true"></i>
                        </a>
                        <a href="" class="text-sky-300 w-4 h-4 hover:text-lime-500">
                            <i class="fab fa-linkedin fa-2x" aria-hidden="true"></i>
                        </a>
                        <a href="" class="text-sky-300 w-4 h-4 hover:text-lime-500">
                            <i class="fab fa-instagram fa-2x" aria-hidden="true"></i>
                        </a>
                    </div>

                </div>

                <!-- Info Detail -->
                <div class="info_col">
                    <div class="info_detail">
                        <h4 class="text-2xl font-bold mb-4">Információk</h4>
                        <p class="mb-1">
                            Fenntarthatóság és a megújulás a fő erényünk. Ezért tartjuk kiemelten fontosnak, hogy
                            flottánkban kizárólag elektromos autók alkossák.
                        </p>
                        <p>Mindezekkel hozzájárulsz az élhető városi levegő kialakításához, a kényelmes és gyors
                            utazások során.</p>
                    </div>
                </div>

                <!-- Links -->
                <div class="w-full lg:w-1/4 mx-auto info_col">
                    <div class="info_link_box">
                        <h4 class="text-2xl font-bold mb-4">Links</h4>
                        <div class="info_links flex flex-col space-y-2 font-semibold">
                            <a class="hover:text-lime-500" href="index.html">Főoldal</a>
                            <a class="hover:text-lime-500" href="about.html">Rólunk</a>
                            <a class="hover:text-lime-500" href="team.html">Kedvezmények</a>
                            <a class="hover:text-lime-500" href="why.html">Flotta</a>
                            <a class="hover:text-lime-500" href="service.html">Cégeknek</a>
                        </div>
                    </div>
                </div>

                <div class="info_col">
                    <h4 class="text-2xl font-bold uppercase mb-4">Feliratkozás</h4>
                    <h5 class="font-semibold text-sky-200 italic mb-1">Értesülj elsőkként az újdonságokról!</h5>
                    <form action="#" class="space-y-3">
                        <input type="text" placeholder="e-mail cím"
                            class="w-full p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-300" />
                        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">
                            Hírlevél feliratkozás
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>
