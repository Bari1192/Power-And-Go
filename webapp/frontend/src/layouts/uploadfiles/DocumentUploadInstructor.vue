<template>
    <div class="upload-guidelines bg-emerald-800/65 bg- border-2 border-emerald-400/30 p-4 rounded-lg h-full 
                shadow-xl shadow-emerald-900/30 hover:shadow-emerald-800/40 transition-colors ease-out duration-300">
        <h4 class="text-white text-xl font-extrabold tracking-wider mb-4 
                   border-b-2 border-emerald-400/60 pb-2">
            Feltöltési útmutató
        </h4>
        <ul class="text-base lg:ml-4 text-emerald-50 space-y-3  cursor-pointer" role="list"
            aria-label="Feltöltési követelmények">
            <li class="font-medium flex items-center group 
                       bg-emerald-900/30 p-3 rounded-lg border-2 border-emerald-300/20
                       hover:bg-emerald-600/40 transition-all duration-300">
                <span class="text-emerald-200 text-2xl pr-3 group-hover:scale-110 transition-transform"
                    aria-hidden="true">✓</span>
                <span class="group-hover:text-yellow-300 cursor-pointer text-yellow-50 transition-colors">
                    Kérjük megfelelő fényviszonyok között készítse a képeket!
                </span>
            </li>
            <li class="font-medium flex items-center group 
                       bg-emerald-900/30 p-3 rounded-lg border-2 border-emerald-300/20
                       hover:bg-emerald-600/40 transition-all duration-300">
                <span class="text-emerald-200 text-xl pr-3 group-hover:scale-110 transition-transform"
                    aria-hidden="true">✓</span>
                <span class="group-hover:text-yellow-300 cursor-pointer text-yellow-50 transition-colors">
                    A dokumentum minden részének olvashatónak kell lennie!
                </span>
            </li>
            <li class="font-medium flex items-center group 
                       bg-emerald-900/30 p-3 rounded-lg border-2 border-emerald-300/20
                       hover:bg-emerald-600/40 transition-all duration-300">
                <span class="text-emerald-200 text-xl pr-3 group-hover:scale-110 transition-transform"
                    aria-hidden="true">✓</span>
                <span class="group-hover:text-yellow-300 cursor-pointer text-yellow-50 transition-colors">
                    Kérjük kerülje a tükröződést, árnyékokat, elmosódást!
                </span>
            </li>
        </ul>
    </div>
</template>