import { defineStore } from "pinia";
import { ref } from "vue";
import { http } from "@utils/http.mjs";
import { ToastService } from "@layouts/toasts/ToastService.js";

export const useFleetStore = defineStore("fleets", () => {
  const fleets = ref([]);
  const fleet = ref(null);

  const error = ref(false);

  async function getFleets() {
    error.value = null;
    const toastId = ToastService.showLoading("Flotta betöltése folyamatban...");
    try {
      const resp = await http.get("/fleets");
      fleets.value = resp.data.data;
      ToastService.updateToSuccess(toastId, "Adatok szinkornizálva!");
    } catch (error) {
      error.value = error.message;
      ToastService.updateToError(toastId, "Hiba a flotta lekérdezésében");
    }
  }
  async function getFleet(id) {
    error.value = null;
    try {
      const resp = await http.get(`/fleets/${id}`);
      fleet.value = resp.data.data;
    } catch (error) {
      error.value = error.message;
      throw error;
    }
  }
  async function updateFleet(id, fleetData) {
    error.value = null;
    const toastId = ToastService.showLoading("Frissítés folyamatban");
    try {
      const resp = await http.put(`/fleets/${id}`, fleetData);
      const idX = this.fleets.findIndex((d) => d.id === id);
      this.fleets.splice(idX, 1, resp.data.data);
      ToastService.updateToSuccess(toastId, "Frissítés sikeres!");
      getFleets();
    } catch (error) {
      error.value = error.message;
      ToastService.updateToError(toastId, "Hiba a frissítés közben!");
    }
  }
  async function createFleet(data) {
    error.value = null;
    const toastId = ToastService.showLoading("Frissítés folyamatban!");

    try {
      await http.post("/fleets", data);
      ToastService.updateToSuccess(toastId, "Flotta sikeresen hozzáadva!");
      return true;
    } catch (err) {
      error.value = err.message;
      ToastService.updateToError(
        toastId,
        `Hiba a flotta hozzáadása közben: ${err.message}`
      );
      return false;
    }
  }
  async function deleteFleet(id) {
    error.value = null;
    try {
      await http.delete(`/fleets/${id}`);
      const idX = this.fleets.findIndex((d) => d.id === id);
      this.fleets.splice(idX, 1);
      ToastService.updateToSuccess("Sikeres törlés!");
    } catch (error) {
      error.value = error.message;
      ToastService.updateToError("Hiba a törlés közben!", error.value);
    }
  }

  return {
    fleets,
    fleet,

    getFleets,
    getFleet,
    updateFleet,
    deleteFleet,
    createFleet,
  };
});
