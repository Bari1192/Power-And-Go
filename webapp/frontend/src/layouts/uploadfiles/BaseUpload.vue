<template>
    <div class="document-upload-wizard mt-8">
        <!-- Dokumentum típusok/Lépegetős -->
        <BaseDocumentUploadSteps :documents="documents" :currentStep="currentDocIndex" />

        <div class="grid grid-cols-3 gap-4 mb-4 border-y-4 py-8 px-1 border-lime-50/75 rounded-2xl">
            <!-- Bal infók 2/3 -->
            <div class="col-span-2">
                <DocumentUploadInstructor />
                <!-- Feltöltési útmutató rész -->
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

        <div class="upload-panel bg-emerald-800/65 p-6 rounded-2xl border-2 border-emerald-400/75">
            <DocumentumUploadPanel :document="documents[currentDocIndex]" :current-index="currentDocIndex"
                :total-documents="documents.length" :uploaded-files="uploadedFiles" @file-uploaded="onFileUploaded"
                @all-documents-uploaded="onAllDocumentsUploaded" />
        </div>
    </div>
</template>

<script setup>
import { ref, computed, reactive, watch, onMounted } from 'vue';
import { toast } from 'vue3-toastify';
import BaseDocumentUploadSteps from './BaseDocumentUploadSteps.vue';
import DocumentUploadInstructor from './DocumentUploadInstructor.vue';
import DocumentumUploadPanel from './DocumentumUploadPanel.vue';

const preview = ref(null);
const currentDocIndex = ref(0);
const uploadProgress = ref(0);
const statusMessage = ref('');
const fileInput = ref(null);
const buttonText = ref('Fájl feltöltése');
const fileSize = ref(null);

const emit = defineEmits([
    'update:preview',
    'update:fileSize',
    'update:buttonText',
    'reset-upload-state',
    'all-documents-uploaded',
    'update:modelValue'
]);

// Definiáljuk a props-ot, hogy megkapjuk a modelValue-t
const props = defineProps({
    modelValue: {
        type: Array,
        default: () => []
    }
});

const documents = ref([
    {
        name: 'Személyigazolvány első oldala', required: true, uploaded: false, accept: 'image/jpeg,image/png,application/pdf', maxSize: 5
    },
    {
        name: 'Személyigazolvány hátsó oldala', required: true, uploaded: false, accept: 'image/jpeg,image/png,application/pdf', maxSize: 5
    },
    {
        name: 'Lakcímkártya első oldala', required: true, uploaded: false, accept: 'image/jpeg,image/png,application/pdf', maxSize: 5
    },
    {
        name: 'Jogosítvány első oldala', required: true, uploaded: false, accept: 'image/jpeg,image/png,application/pdf', maxSize: 5
    },
    {
        name: 'Jogosítvány hátsó oldala', required: true, uploaded: false, accept: 'image/jpeg,image/png,application/pdf', maxSize: 5
    }
]);
const currentDocument = ref(null);
const uploadedFiles = ref([]);
const tempDocumentStore = reactive({
    files: new Map(),
    previews: new Map()
});

// Ell. az aktuális dokumentum fel van-e már töltve?
const isCurrentDocumentUploaded = computed(() => {
    return documents.value[currentDocIndex.value].uploaded;
});

const handleDocumentUpload = (file) => {
    if (file) {
        documents.value[currentDocIndex.value].uploaded = true;
        try {
            if (file instanceof File || file instanceof Blob) {
                const objectUrl = URL.createObjectURL(file);
                tempDocumentStore.files.set(currentDocIndex.value, file);
                tempDocumentStore.previews.set(currentDocIndex.value, objectUrl);
                if (currentDocIndex.value < documents.value.length - 1) {
                    nextDoc();
                }
            } else {
                console.error('Invalid file object:', file);
            }
        } catch (error) {
            console.error('Error creating object URL:', error);
        }
    }
};
const onFileUploaded = (fileData) => {
    documents.value[fileData.index].uploaded = true;
    const existingIndex = uploadedFiles.value.findIndex(doc => doc.index === fileData.index);
    if (existingIndex >= 0) {
        uploadedFiles.value[existingIndex] = fileData;
    } else {
        uploadedFiles.value.push(fileData);
    }
    if (fileData.file) {
        tempDocumentStore.files.set(fileData.index, fileData.file);
        if (fileData.file.preview) {
            tempDocumentStore.previews.set(fileData.index, fileData.file.preview);
        }
    }
};
const onAllDocumentsUploaded = () => {
    console.log('Minden dokumentum feltöltve!');
    // Itt fog majd backendre menni.
};

// Navi a doksik között
const nextDoc = () => {
    if (currentDocIndex.value < documents.value.length - 1) {
        currentDocIndex.value++;
        currentDocument.value = null;
    }
};
const prevDoc = () => {
    if (currentDocIndex.value > 0) {
        currentDocIndex.value--;
        currentDocument.value = null;
    }
};
const areAllDocumentsUploaded = computed(() => {
    return documents.value.every(doc => doc.uploaded === true);
});
watch(() => tempDocumentStore.files.size, (newSize) => {
    if (newSize === documents.value.length) {
        emit('all-documents-uploaded', true);
    }
});
const isCurrentValid = computed(() => {
    return tempDocumentStore.files.has(currentDocIndex.value);
});
const loadStoredDocument = () => {
    const storedFile = tempDocumentStore.files.get(currentDocIndex.value);
    const storedPreview = tempDocumentStore.previews.get(currentDocIndex.value);

    if (storedFile && storedPreview) {
        preview.value = storedPreview;
        fileSize.value = (storedFile.size / (1024 * 1024)).toFixed(1);
        buttonText.value = 'Módosítás';

        emit('update:preview', preview.value);
        emit('update:fileSize', fileSize.value);
        emit('update:buttonText', buttonText.value);
    } else {
        resetUploadState();
    }
};
const resetUploadState = () => {
    preview.value = null;
    uploadProgress.value = 0;
    statusMessage.value = '';
    fileSize.value = null;
    buttonText.value = 'Fájl feltöltése';
    emit('reset-upload-state');
    emit('update:preview', null);
    emit('update:fileSize', null);
    emit('update:buttonText', 'Fájl feltöltése');
};

const handleDrop = async (event) => {
    event.preventDefault();
    const file = event.dataTransfer.files[0];

    if (file) {
        const input = fileInput.value;
        if (input) {
            try {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                input.files = dataTransfer.files;
                handleDocumentUpload(file);
            } catch (error) {
                console.error('Error handling file drop:', error);
            }
        }
    }
};

const handleDragOver = (event) => {
    event.preventDefault();
};

const finalizeUpload = () => {
    if (areAllDocumentsUploaded.value) {
        // Az összes dokumentum megy a a szülő komponensnek! >>
        const documentData = [...tempDocumentStore.files.entries()].map(([index, file]) => {
            return {
                index,
                file,
                documentName: documents.value[index].name
            };
        });

        emit('update:modelValue', documentData);
        toast.success('Dokumentumok sikeresen feltöltve!', { autoClose: 3000 });
    } else {
        toast.error('Kérjük töltsön fel minden kötelező dokumentumot!', { autoClose: 3000 });
    }
};
watch(() => props.modelValue, (newVal) => {
    if (newVal && newVal.length > 0) {
        newVal.forEach(item => {
            if (item.file && item.index !== undefined) {
                tempDocumentStore.files.set(item.index, item.file);
                if (item.file.preview) {
                    tempDocumentStore.previews.set(item.index, item.file.preview);
                }
                documents.value[item.index].uploaded = true;
            }
        });
    }
}, { immediate: true });

watch(currentDocIndex, () => {
    loadStoredDocument();
});

onMounted(() => {
    resetUploadState();

    const setupDragAndDrop = () => {
        const container = document.querySelector('.upload-panel');
        if (container) {
            container.addEventListener('dragover', handleDragOver);
            container.addEventListener('drop', handleDrop);

            return () => {
                container.removeEventListener('dragover', handleDragOver);
                container.removeEventListener('drop', handleDrop);
            };
        }
        return () => { };
    };

    const cleanup = setupDragAndDrop();
    const unmountCallback = () => {
        cleanup();
        tempDocumentStore.previews.forEach((preview) => {
            if (typeof preview === 'string' && preview.startsWith('blob:')) {
                URL.revokeObjectURL(preview);
            }
        });
    };
    if (typeof window !== 'undefined') {
        window.__UNMOUNT_CALLBACKS = window.__UNMOUNT_CALLBACKS || [];
        window.__UNMOUNT_CALLBACKS.push(unmountCallback);
    }
});
</script>

<style scoped>
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
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15), inset 0 0 0 1px rgba(255, 255, 255, 0.2);
}

.success {
    @apply bg-emerald-600 text-white;
}

.upload-panel {
    @apply transition-all duration-300 relative;
    box-shadow: 0 0 15px rgba(52, 211, 153, 0.2), inset 0 0 20px rgba(52, 211, 153, 0.1);
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