<template>
    <div class="text-2xl text-center font-semibold mb-6 text-amber-50 underline underline-offset-4">
        {{ document.name }} </div>

    <div class="flex flex-col md:flex-row gap-4">
        <!-- Előnézeti kiskép [] rész -->
        <div
            class="col preview-container bg-lime-100/5 rounded-2xl p-2 flex items-center justify-center h-[250px] w-[250px]">
            <img v-if="preview" :src="preview" alt="Előnézet" class="h-[200px] w-[200px] object-cover" />
            <div v-else
                class="text-lime-100/70 text-center flex justify-center items-center align-middle italic px-8 h-[200px] w-[200px]">
                Előnézeti kép
            </div>
        </div>

        <div class="upload-area flex-1 cursor-pointer" @click="$refs.fileInput.click()">
            <div
                class="rounded-lg text-center min-h-[200px] border-2 border-dashed border-lime-100/40 flex justify-center items-center text-lime-100/70 italic text-base">
                <p v-if="fileSize" class="text-gray-100 flex justify-center">
                    Fájl mérete: {{ fileSize }} MB
                </p>
                <p v-if="!fileSize">
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
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';

const props = defineProps({
  document: {
    type: Object,
    required: true,
  },
  currentIndex: {
    type: Number,
    required: true
  },
  totalDocuments: {
    type: Number,
    required: true
  },
  // Új prop a már feltöltött fájlok tárolására
  uploadedFiles: {
    type: Array,
    default: () => []
  }
});

const emit = defineEmits(['file-uploaded', 'all-documents-uploaded']);

const fileInput = ref(null);
const selectedFile = ref(null);
const preview = ref(null);
const uploadProgress = ref(0);
const errorMessage = ref('');
const uploadComplete = ref(false);
const dragover = ref(false);

const buttonText = computed(() => {
  return uploadComplete.value ? 'Módosítás' : 'Fájl kiválasztása';
});

const statusMessage = computed(() => {
  if (uploadComplete.value) {
    return 'Feltöltés befejezve!';
  } else if (uploadProgress.value > 0) {
    return `Feltöltés folyamatban: ${Math.round(uploadProgress.value)}%`;
  }
  return '';
});

const fileSize = computed(() => {
  if (selectedFile.value) {
    return (selectedFile.value.size / (1024 * 1024)).toFixed(2);
  }
  return null;
});

const validateFile = (file) => {
  errorMessage.value = '';

  // Fájltípus ellenőrzése
  if (props.document.accept) {
    const fileType = file.type;
    const acceptTypes = props.document.accept.split(',').map(type => type.trim());
    const isValidType = acceptTypes.some(type => {
      if (type.startsWith('.')) {
        // Kiterjesztés
        const extension = '.' + file.name.split('.').pop().toLowerCase();
        return extension === type.toLowerCase();
      } else {
        return fileType.match(new RegExp(type.replace('*', '.*')));
      }
    });

    if (!isValidType) {
      errorMessage.value = `Csak a következő típusú fájlok engedélyezettek: ${props.document.accept}`;
      return false;
    }
  }

  if (props.document.maxSize && file.size > props.document.maxSize * 1024 * 1024) {
    errorMessage.value = `A fájl mérete nem lehet nagyobb, mint ${props.document.maxSize} MB.`;
    return false;
  }

  return true;
};

const handleFileDrop = (event) => {
  dragover.value = false;
  const file = event.dataTransfer?.files[0];
  if (file) {
    processFile(file);
  }
};

const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    processFile(file);
  }
};

const processFile = (file) => {
  if (!validateFile(file)) {
    return;
  }
  
  selectedFile.value = file;
  uploadComplete.value = false;

  // Előnézet generálása
  if (file.type.startsWith('image/')) {
    const reader = new FileReader();
    reader.onload = (e) => {
      preview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  } else {
    preview.value = null;
  }

  uploadFile(file);
};

const uploadFile = (file) => {
  // Feltöltés szimuláció
  uploadProgress.value = 0;
  
  const simulateUpload = () => {
    const interval = setInterval(() => {
      uploadProgress.value += Math.random() * 10;
      
      if (uploadProgress.value >= 100) {
        uploadProgress.value = 100;
        clearInterval(interval);
        uploadComplete.value = true;

        // A fájl információk, amit a szülő komponensnek átadunk
        const fileData = {
          index: props.currentIndex,
          file: {
            name: file.name,
            size: file.size,
            type: file.type,
            lastModified: file.lastModified,
            preview: preview.value, // Elmentsük a preview-t is
            uploadId: Math.random().toString(36).substring(2)
          }
        };
        emit('file-uploaded', fileData);

        // Ha ez volt az utolsó doksi
        if (props.currentIndex === props.totalDocuments - 1) {
          emit('all-documents-uploaded');
        }
      }
    }, 200);
  };

  setTimeout(simulateUpload, 300);
};

const resetUpload = () => {
  uploadComplete.value = false;
  uploadProgress.value = 0;
};

const checkForExistingUpload = () => {
  if (props.uploadedFiles && props.uploadedFiles.length > 0) {
    const existingFile = props.uploadedFiles.find(file => file.index === props.currentIndex);
    
    if (existingFile && existingFile.file) {
      // Ha van már feltöltött fájl, beállítjuk azt
      selectedFile.value = {
        name: existingFile.file.name,
        size: existingFile.file.size,
        type: existingFile.file.type
      };
      preview.value = existingFile.file.preview;
      uploadComplete.value = true;
      uploadProgress.value = 100;
    } else {
      // Ha nincs, alaphelyzetbe 
      resetState();
    }
  } else {
    resetState();
  }
};

const resetState = () => {
  selectedFile.value = null;
  preview.value = null;
  uploadProgress.value = 0;
  errorMessage.value = '';
  uploadComplete.value = false;
  dragover.value = false;
};

onMounted(() => {
  checkForExistingUpload();
  const component = document.querySelector('.upload-area');
  if (component) {
    component.addEventListener('dragover', (e) => {
      e.preventDefault();
      dragover.value = true;
    });
    
    component.addEventListener('dragleave', () => {
      dragover.value = false;
    });
    
    component.addEventListener('drop', (e) => {
      e.preventDefault();
      handleFileDrop(e);
    });
  }
});
watch(() => props.currentIndex, (newIndex) => {
  checkForExistingUpload();
});
</script>

<style>
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
.transition-all {
    animation: fadeIn 1s ease-in-out;
}
</style>
