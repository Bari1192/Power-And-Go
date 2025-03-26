<template>
    <div class="document-upload-wizard mt-8">
        <!-- Dokumentumtípusok / lépsek -->
        <div class="flex justify-between mb-6 ">
            <div v-for="(doc, index) in documents" :key="index"
                :class="['step-indicator', { 'active': currentDocIndex === index }]"
                class="text-sm cursor-not-allowed">
                {{ doc.name }}
            </div>
        </div>

        <!-- Feltöltési útmutatós rész -->
        <div class="grid grid-cols-3 gap-4 mb-4 border-y-4 py-8 px-1 border-lime-50/75 rounded-2xl ">

            <!-- Bal infók 2/3 -->
            <div class="col-span-2 ">
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
            </div>

            <!-- Jobb gombok 1/3 -->
            <div class="flex items-center justify-center">
                <div class="flex flex-col lg:px-4 space-y-4 w-full">
                    <button @click="prevDoc" :disabled="currentDocIndex === 0" class="nav-button w-full">
                        Előző Dokumentum
                    </button>
                    <button @click="nextDoc" :disabled="currentDocIndex === documents.length - 1 || !isCurrentValid"
                        class="nav-button w-full">
                        Következő Dokumentum
                    </button>

                    <button @click="finalizeUpload"
                            v-if="currentDocIndex === documents.length - 1 && areAllDocumentsUploaded"
                            class="nav-button w-full success">
                        Befejezés
                    </button>
                </div>
            </div>
        </div>

        <!-- Akt. doksi feltöltő -->
        <div class="upload-panel bg-emerald-800/65 p-6 rounded-2xl border-2 border-emerald-400/75">
            <div class="text-2xl text-center font-semibold mb-6 text-amber-50 underline underline-offset-4">
                {{ documents[currentDocIndex].name }} feltöltése
            </div>

            <div class="flex flex-col md:flex-row gap-4">
                <!-- Előnézeti kiskép [] rész -->
                <div
                    class="col  preview-container bg-lime-100/5 rounded-2xl p-2 flex items-center justify-center h-[250px] w-[250px]">
                    <img v-if="preview" :src="preview" alt="Előnézet" class="h-[200px] w-[200px] object-cover" />
                    <div v-else
                        class="text-lime-100/70 text-center flex justify-center items-center align-middle italic px-8 h-[200px] w-[200px]">
                        Előnézeti kép
                    </div>
                </div>

                <div class="upload-area flex-1 cursor-pointer" @click="$refs.fileInput.click()">
                    <div class="rounded-lg text-center min-h-[200px] border-2 border-dashed border-lime-100/40 
                    flex justify-center items-center text-lime-100/70 italic text-base ">
                        <p v-if="FileSize" class="text-gray-100 flex justify-center ">
                            Fájl mérete: {{ FileSize }} MB
                        </p>
                        <p v-if="!FileSize">
                            Válassza ki a fájlt, vagy csak húzza ide!
                        </p>
                    </div>


                    <div class="flex justify-center mt-6">
                        <input type="file" @change="handleFileUpload" accept="image/*" class="hidden" ref="fileInput" />
                        <button @click.stop="$refs.fileInput.click()"
                            class="font-semibold px-4 py-2 bg-yellow-500/75 text-white/75 rounded-lg hover:bg-yellow-600 border border-white/25 transition-colors duration-500">
                            {{ buttonText }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Feltöltési státusz -->
            <div v-if="uploadProgress" class="upload-status mt-4">
                <div class="w-full h-3 bg-lime-950/40 rounded-full overflow-hidden border border-lime-600/20">
                    <div class="loadingbar h-full rounded-full transition-all duration-300 ease-out"
                        :style="{ width: uploadProgress + '%' }">
                        <div class="animated-stripe h-full w-full"></div>
                    </div>
                </div>
                <span class="text-white text-center text-lg mt-2 block">{{ statusMessage }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, reactive, watch } from 'vue';
import { toast } from 'vue3-toastify';

const emit = defineEmits(['document-uploaded', 'all-documents-uploaded', 'update:modelValue']);
const tempDocumentStore = reactive({
    files: new Map(),
    previews: new Map()
});


const currentDocIndex = ref(0);
const preview = ref(null);
const uploadProgress = ref(0);
const statusMessage = ref('');
const fileInput = ref(null);
const buttonText = ref('Fájl feltöltése');
const FileSize = ref(null);

const documents = [
    { name: 'Személyigazolvány első oldalának feltöltése', required: true, uploaded: false },
    { name: 'Személyigazolvány hátoldal', required: true, uploaded: false },
    { name: 'Lakcímkártya elülső oldala', required: true, uploaded: false },
    { name: 'Jogosítvány elülső oldala', required: true, uploaded: false },
    { name: 'Jogosítvány hátoldal', required: true, uploaded: false }
];

const areAllDocumentsUploaded = computed(() => {
    return documents.every(doc => doc.uploaded === true);
});
watch(() => tempDocumentStore.files.size, (newSize) => {
    if (newSize === documents.length) {
        emit('all-documents-uploaded', true);
    }
});
const validateFile = (file) => {
    const maxSize = 5; // Maximum 5MB
    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    
    if (!allowedTypes.includes(file.type)) {
        throw new Error('Csak JPG és PNG képek engedélyezettek!');
    }
    
    const sizeMB = file.size / (1024 * 1024);
    if (sizeMB > maxSize) {
        throw new Error(`A fájl mérete nem lehet nagyobb ${maxSize}MB-nál!`);
    }
    
    return sizeMB.toFixed(1);
};
const isCurrentValid = computed(() => {
    return tempDocumentStore.files.has(currentDocIndex.value);
});
const handleFileUpload = async (event) => {
    const file = event.target.files[0];
    if (!file) return;

    try {
        FileSize.value = validateFile(file);
        const previewUrl = URL.createObjectURL(file);
        preview.value = previewUrl;
        
        // Fájl & előnézet tárolása
        tempDocumentStore.files.set(currentDocIndex.value, file);
        tempDocumentStore.previews.set(currentDocIndex.value, previewUrl);

        // Feltöltés szimulálás
        uploadProgress.value = 0;
        statusMessage.value = 'Feltöltés folyamatban...';
        
        for (let i = 0; i <= 100; i += 10) {
            await new Promise(resolve => setTimeout(resolve, 100));
            uploadProgress.value = i;
        }
        documents[currentDocIndex.value].uploaded = true;
        statusMessage.value = 'Sikeres feltöltés ✅';
        buttonText.value = 'Módosítás';
        emit('document-uploaded', {
            index: currentDocIndex.value,
            file: file
        });

        if (areAllDocumentsUploaded.value) {
            emit('all-documents-uploaded', true);
        }

        toast.success('Dokumentum sikeresen feltöltve!', {
            autoClose: 1500,
            position: toast.POSITION.TOP_CENTER,
        });

    } catch (error) {
        resetUploadState();
        toast.error(error.message);
    }
};


const prevDoc = () => {
    if (currentDocIndex.value > 0) {
        currentDocIndex.value--;
        loadStoredDocument();
    }
};

const nextDoc = () => {
    if (currentDocIndex.value < documents.length - 1 && isCurrentValid.value) {
        currentDocIndex.value++;
        loadStoredDocument();
    }
    buttonText.value = 'Fájl feltöltése';
};
const loadStoredDocument = () => {
    const storedFile = tempDocumentStore.files.get(currentDocIndex.value);
    const storedPreview = tempDocumentStore.previews.get(currentDocIndex.value);

    if (storedFile && storedPreview) {
        preview.value = storedPreview;
        FileSize.value = (storedFile.size / (1024 * 1024)).toFixed(1);
        buttonText.value = 'Módosítás';
    } else {
        resetUploadState();
        buttonText.value = 'Fájl feltöltése';
    }
};
const resetUploadState = () => {
    preview.value = null;
    uploadProgress.value = 0;
    statusMessage.value = '';
    FileSize.value = null;
};
const handleDrop = async (event) => {
    event.preventDefault();
    const file = event.dataTransfer.files[0];
    if (file) {
        const input = fileInput.value;
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        input.files = dataTransfer.files;
        await handleFileUpload({ target: input });
    }
};
const handleDragOver = (event) => {
    event.preventDefault();
};
const finalizeUpload = () => {
    if (areAllDocumentsUploaded.value) {
        finalizeDocuments();
        emit('all-documents-uploaded', true);
        toast.success('Dokumentumok sikeresen véglegesítve!', {
            autoClose: 2000,
            position: toast.POSITION.TOP_CENTER,
        });
    }
};
</script>

<style scoped>
.loadingbar {
    background-image: linear-gradient(145deg,
            rgba(95, 138, 15, 0.8) 0%,
            rgba(132, 185, 34, 0.8) 25%,
            rgba(139, 194, 36, 0.9) 50%,
            rgba(117, 217, 2, 0.6) 75%,
            rgba(59, 217, 2, 0.8) 100%);
    box-shadow: 0 0 10px rgba(139, 194, 36, 0.5);
}

.animated-stripe {
    background-image: linear-gradient(45deg,
            rgba(255, 255, 255, 0.15) 25%,
            transparent 25%,
            transparent 50%,
            rgba(255, 255, 255, 0.15) 50%,
            rgba(255, 255, 255, 0.15) 75%,
            transparent 75%,
            transparent);
    background-size: 20px 20px;
    animation: move-stripe 1s linear infinite;
}

.step-indicator {
    @apply flex items-center justify-center px-3 py-2 rounded-full bg-lime-800/25 border-2 border-lime-700/25 text-center mx-2;
}

.step-indicator.active {
    @apply bg-amber-50/30 border-2 text-white border-lime-700/65 font-medium p-3 text-center tracking-wider cursor-pointer;
}

.nav-button {
    @apply px-4 py-2 bg-white text-lime-500 rounded-lg border-4;

}

.nav-button:disabled {
    @apply opacity-55 cursor-not-allowed border-4 border-gray-600/85 font-semibold text-lime-600;
}

.nav-button:enabled {
    color: rgba(255, 255, 255, 0.9);
    font-weight: 600;
    background-image: linear-gradient(90deg,
            rgba(139, 194, 36, 0.8) 0%,
            rgba(217, 138, 2, 0.6) 50%,
            rgba(139, 194, 36, 0.9) 75%,
            rgba(217, 138, 2, 0.8) 100%);
    transition: all .4s ease-in-out;
    border-color: rgb(77, 114, 53);
}
.nav-button:enabled:hover {
    background-position: 100% 100%;
    box-shadow:
        0 6px 20px rgba(0, 0, 0, 0.15),
        inset 0 0 0 1px rgba(255, 255, 255, 0.2);
}

.upload-panel {
    @apply transition-all duration-300 relative;
    box-shadow:
        0 0 15px rgba(52, 211, 153, 0.2),
        inset 0 0 20px rgba(52, 211, 153, 0.1);
    border-radius: 1rem;
    overflow: hidden;
}

.preview-container {
    min-height: 200px;
    @apply border-2 border-dashed border-lime-100/40;
}

@media (max-width: 640px) {
    .document-upload-wizard {
        @apply px-2;
    }

    .preview-container {
        @apply w-full;
    }
}
</style>
