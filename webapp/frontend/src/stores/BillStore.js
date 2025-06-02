import { defineStore } from "pinia";
import { ref } from "vue";
import { http } from "@utils/http.mjs";
import { ToastService } from "@stores/Services/ToastService";

export const useBillStore = defineStore("bills", () => {
  const bills = ref([]);
  const bill = ref(null);

  async function getBills() {
    isLoading.value = true;
    error.value = null;
    try {
      const resp = await http.get("/bills");
      bills.value = resp.data.data;
      return bills.value;
    } catch (error) {
      error.value = error.message;
    } finally {
      isLoading.value = false;
    }
  }

  async function getBill(id) {
    isLoading.value = true;
    error.value = null;
    try {
      const resp = await http.get(`/bills/${id}`);
      bill.value = resp.data.data;
      return bill.value;
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

  async function createBill(data) {
    isLoading.value = true;
    error.value = null;
    const toastId = ToastService.showLoading(
      "Számla létrehozása folyamatban..."
    );
    try {
      const resp = await http.post("/bills", data);
      resp.value == 201 ??
        ToastService.updateToSuccess(toastId, "Számla sikerensn kiállítva.");
    } catch (error) {
      console.error("Hiba számla frissítése közben", error.value);
      ToastService.updateToError(toastId, "Hiba számla kiállításában!");
    } finally {
      isLoading.value = false;
    }
  }

  async function updateBill(id, data) {
    isLoading.value = true;
    error.value = null;
    const toastId = ToastService.showLoading("Számlaművelet folyamatban...");
    try {
      await http.post(`/bills/${id}`, data);
      ToastService.updateToSuccess(
        toastId,
        "A számla frissítése sikeres volt!"
      );
    } catch (error) {
      console.error("Hiba számla frissítése közben", error.value);
      ToastService.updateToError(toastId, "Hiba számla frissítése közben!");
    } finally {
      isLoading.value = false;
    }
  }

  async function deleteBill(id) {
    isLoading.value = true;
    error.value = null;
    const toastId = ToastService.showLoading("Számla törlése folyamatban...");
    try {
      await http.delete(`/bills/${id}`);
      ToastService.updateToSuccess(
        toastId,
        "A számla törlése sikeresen megtörtént!"
      );
    } catch (error) {
      error.value = error.message;
      console.error("Hiba a számla törlése közben!", error.value);
      ToastService.updateToError(toastId, "Hiba számla törlése közben!");
    } finally {
      isLoading.value = false;
    }
  }
  async function getAllCriticalChargingFines() {
    isLoading.value = true;
    error.value = null;
    try {
      const resp = await http.get("/bills/fees");
      carCritChargFines.value = resp.data.data;
    } catch (error) {
      error.value = error.message;
      console.error("Nem sikerült lekérdezni a bírságokat (fees)!", err);
    } finally {
      isLoading.value = false;
    }
  }

  function getSelectedCarCriticalChargeFine(searchCarID) {
    if (!Array.isArray(carCritChargFines.value)) return [];
    return carCritChargFines.value.filter(
      (fine) => fine.car_id === searchCarID
    );
  }

  return {
    bills,
    bill,
    carAllTickets,
    carLatestTicket,
    carRentHistory,
    carCritChargFines,
    isLoading,
    error,

    getBills,
    getBill,
    createBill,
    updateBill,
    deleteBill,
    getAllCriticalChargingFines,
    getSelectedCarCriticalChargeFine,
  };
});
