<template>
    <div class="main-container mx-auto flex -mb-12">
        <div class="car-section w-3/5 p-4">
            <div class="car-container mx-auto">
                <img src="http://backend.vm1.test/storage/carsImages/citigomodel.png" alt="Skoda Citigo képe"
                    class="car-image max-w-full max-h-full">
                <div
                    v-for="marker in markers"
                    :key="marker.name"
                    class="marker"
                    :style="{ top: marker.top, left: marker.left, backgroundColor: marker.name === selectedLocation ? 'rgba(180, 0, 0, 0.9)' : 'rgb(223, 238, 244)' }"
                    @click="selectLocation(marker.name)"
                >
                </div>
            </div>
        </div>

        <div class="form-section w-2/5 p-4 -mt-5">
            <DemageForm :selected-location="selectedLocation" />
        </div>
    </div>
</template>

<script>
import DemageForm from '@layouts/reportforms/vandalism/DemageForm.vue';

export default {
    components: {
        DemageForm,
    },
    data() {
        return {
            selectedLocation: '',
            markers: [
                { name: 'Bal hátsó lökhárító', top: '18%', left: '82%' },
                { name: 'Bal hátsó ajtó', top: '24%', left: '82%' },
                { name: 'Bal első ajtó', top: '33%', left: '82%' },
                { name: 'Bal visszapillantó', top: '37%', left: '90%' },
                { name: 'Bal első sárvédő', top: '66%', left: '79%' },
                { name: 'Rendszám', top: '70%', left: '48%' },
                { name: 'Hátsó lökhárító', top: '7%', left: '50%' },
                { name: 'Jobb első sárvédő', top: '66%', left: '18%' },
                { name: 'Jobb visszapillantó tükör', top: '37%', left: '10%' },
                { name: 'Jobb hátsó ajtó', top: '24%', left: '18%' },
                { name: 'Jobb első ajtó', top: '33%', left: '18%' },
                { name: 'Jobb hátsó lökhárító', top: '18%', left: '18%' },
                { name: 'Szélvédő', top: '35%', left: '50%' },
                { name: 'Tető', top: '18%', left: '50%' },
            ],
        };
    },
    methods: {
        selectLocation(name) {
            this.selectedLocation = name; 
        },
    },
};
</script>

<style scoped>
.main-container {
    display: flex;
    gap: 20px;
}

.car-section {
    display: flex;
    justify-content: center;
    align-items: center;
    border-top: 2px solid rgba(27, 132, 31, 0.6);
    border-bottom: 2px solid rgba(27, 132, 31, 0.6);
    border-radius: 26rem;
    background-color: rgba(43, 171, 0, 0.02);
}

.car-container {
    position: relative;
    width: 300px;
    height: 550px;
}

.car-image {
    width: 100%;
    height: auto;
}
.car-image,.marker {
margin-top: 3rem;
}

.marker {
    position: absolute;
    width: 20px;
    height: 20px;
    background-color: rgb(223, 238, 244);
    border-radius: 50%;
    border: 2px rgb(2, 149, 2) solid;
    transform: translate(-50%, -50%);
    cursor: pointer;
}

.marker:hover {
    background-color: rgba(130, 46, 57, 0.9);
}
</style>
