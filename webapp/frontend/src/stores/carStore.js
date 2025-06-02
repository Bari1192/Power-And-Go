import { defineStore } from "pinia";
import { ref } from "vue";
import { http } from "@utils/http.mjs";
import { ToastService } from "@stores/Services/ToastService";

export const useCarStore = defineStore("cars", () => {
  const cars = ref([]);
  const car = ref(null);
  const carAllTickets = ref([]);
  const carLatestTicket = ref(null);
  const carRentHistory = ref([]);
  const carFees = ref([]);
  const isLoading = ref(false);
  const error = ref(null);

  const getCarGroupCountByMotorPower = (motorpower) => {
    return cars.value.filter((car) => car.motor_power === motorpower).length;
  };

  async function getCars() {
    isLoading.value = true;
    error.value = null;
    try {
      const resp = await http.get("/cars");
      cars.value = resp.data.data;
      return cars.value;
    } catch (error) {
      error.value = error.message;
      ToastService.updateToError(
        toastId,
        `Hiba az autók betöltésekor: ${error.message}`
      );
    } finally {
      isLoading.value = false;
    }
  }

  async function getCar(id) {
    isLoading.value = true;
    error.value = null;
    const toastId = ToastService.showLoading("Autó adatainak előkészítése...");

    try {
      const resp = await http.get(`/cars/${id}`);
      car.value = resp.data.data;
      ToastService.updateToSuccess(toastId, "Autó szinkronizálva!");
      return car.value;
    } catch (error) {
      console.error("Hiba az autó lekérdezésekor", error.value);
      ToastService.updateToError(
        toastId,
        `Hiba az autó szinkronizációja közben: ${error.message}`
      );
    } finally {
      isLoading.value = false;
    }
  }

  async function createCar(data) {
    isLoading.value = true;
    error.value = null;

    try {
      await http.post("/cars", data);
    } catch (error) {
      console.error("Hiba az autó létrehozásakor", error.value);
    } finally {
      isLoading.value = false;
    }
  }

  async function updateCar(id, data) {
    isLoading.value = true;
    error.value = null;

    try {
      await http.post(`/cars/${id}`, data);
    } catch (error) {
      console.error("Hiba az autó frissítésekor", error.value);
    } finally {
      isLoading.value = false;
    }
  }

  async function deleteCar(id) {
    isLoading.value = true;
    error.value = null;

    try {
      await http.delete(`/cars/${id}`);
    } catch (error) {
      error.value = error.message;
      console.error("Hiba az autó törlésekor", error.value);
    } finally {
      isLoading.value = false;
    }
  }

  async function getCarTickets(id) {
    isLoading.value = true;
    error.value = null;
    const toastId = ToastService.showLoading("Autók betöltése folyamatban...");

    try {
      const resp = await http.get(`/cars/${id}/tickets`);
      carAllTickets.value = resp.data.data;
      carLatestTicket.value = carAllTickets.value[0] ?? null;
      ToastService.updateToSuccess(toastId, "Sikeres lekérés");
    } catch (error) {
      error.value = error?.message;
      console.error("Nem sikerült lekérdezni a  (tickets)!", error);
      ToastService.updateToError(toastId, "Hiba a lekérés közben!");
    } finally {
      isLoading.value = false;
    }
  }

  async function getRenthistory(id) {
    try {
      const resp = await http.get(`/cars/${id}/renthistory`);
      carRentHistory.value = resp.data;
      return carRentHistory.value;
    } catch (error) {
      error.value = error?.message;
    }
  }

  // EZ MÁR FÉLIG-MEDDIG A BILLS -hez tartozna!
  // Lekéri az összes büntetést
  async function getAllFinesBill() {
    isLoading.value = true;
    error.value = null;

    try {
      const resp = await http.get("/bills/fees");
      carFees.value = resp.data.data;
    } catch (err) {
      error.value = err?.message;
      console.error("Nem sikerült lekérdezni a bírságokat (fees)!", err);
    } finally {
      isLoading.value = false;
    }
  }

  function getOneFineBill(searchCarID) {
    if (!Array.isArray(carFees.value)) return [];
    return carFees.value.filter((fine) => fine.car_id === searchCarID);
  }

  ////////

  async function getCarDetails(id) {
    isLoading.value = true;
    error.value = null;
    try {
      const responses = await Promise.all([
        http.get(`/cars/${id}`),
        http.get(`/cars/${id}/tickets`),
        http.get(`/cars/${id}/renthistory`),
      ]);

      car.value = responses[0].data.data;
      carAllTickets.value = responses[1].data.data;
      carLatestTicket.value = carAllTickets.value[0] ?? null;
      carRentHistory.value = responses[2].data.data;
    } catch (err) {
      console.error("Hiba adatok lekérése közben:", err);
      error.value = err.message;
      ToastService.updateToError(toastId, `Hiba történt: ${err.message}`);
    } finally {
      isLoading.value = false;
    }
  }

  return {
    cars,
    car,
    carAllTickets,
    carLatestTicket,
    carRentHistory,
    carFees,
    isLoading,
    error,

    getCars,
    getCar,
    createCar,
    updateCar,
    deleteCar,
    getCarTickets,
    getRenthistory,
    getCarDetails,
    getAllFinesBill,
    getOneFineBill,
    getCarGroupCountByMotorPower,
  };
});
