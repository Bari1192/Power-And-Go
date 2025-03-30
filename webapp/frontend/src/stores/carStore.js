import { defineStore } from "pinia";
import { http } from "@utils/http.mjs";
import { ToastService } from "@layouts/toasts/ToastService.js";

export const useCarStore = defineStore("cars", {
  state: () => ({
    cars: [],
    car: null,
    isLoading: false,
    error: null,
    currentToastLoading: null,
  }),
  actions: {
    async getCars() {
      this.isLoading = true;
      this.error = null;
      const toastId = ToastService.showLoading("Autók betöltése folyamatban...");
      try {
        const resp = await http.get("/cars");
        this.cars = resp.data.data;
        ToastService.updateToSuccess(toastId, "Autók sikeresen betöltve!");
      } catch {
        this.error = error.message;
        ToastService.updateToError(toastId, `Hiba az autók betöltésekor: ${error.message}`);
      } finally {
        this.isLoading = false;
      }
    },

    async getCar(id) {
      this.isLoading = true;
      this.error = null;
      const toastId = ToastService.showLoading("Autó adatainak előkészítése...");
      try {
        const resp = await http.get(`/cars/${id}`);
        this.car = resp.data.data;
        ToastService.updateToSuccess(toastId,"Autó szinkronizálva!")
      } catch (error) {
        console.error("Hiba az autó lekérdezésekor", this.error);
        ToastService.updateToError(toastId,`Hiba az autó szinkronizációja közben: ${error.message}`)
      } finally {
        this.isLoading = false;
      }
    },

    async createCar(data) {
      this.isLoading = true;
      this.error = null;
      try {
        await http.post("/cars", data);
      } catch (error) {
        console.error("Hiba az autó létrehozásakor", this.error);
      } finally {
        this.isLoading = false;
      }
    },

    async updateCar(id, data) {
      this.isLoading = true;
      this.error = null;
      try {
        await http.post(`/cars/${id}`, data);
      } catch (error) {
        console.error("Hiba az autó frissítésekor", this.error);
      } finally {
        this.isLoading = false;
      }
    },

    async deleteCar(id) {
      this.isLoading = true;
      this.error = null;
      try {
        await http.delete(`/cars/${id}`);
      } catch (error) {
        console.error("Hiba az autó törlésekor", this.error);
      } finally {
        this.isLoading = false;
      }
    },
  },
});
