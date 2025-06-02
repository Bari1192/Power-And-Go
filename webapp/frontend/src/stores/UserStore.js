import { defineStore } from "pinia";
import { ref } from "vue";
import { http } from "@utils/http.mjs";
import { ToastService } from "@stores/Services/ToastService";

export const useUserStore = defineStore("users", () => {
  const users = ref([]);
  const user = ref(null);
  const error = ref(null);
  const userSearch = ref([]);
  const basedOnSearchLetters = ref(null);

  async function getUsers() {
    error.value = null;
    const toastId = ToastService.showLoading("Felhasználók lekérdezése...");
    try {
      const resp = await http.get("/users");
      users.value = resp.data.data;
      ToastService.updateToSuccess(toastId, "Felhasználók szinkronizálva.");
    } catch (err) {
      error.value = err.message;
      ToastService.updateToError(
        toastId,
        `Hiba a felhasználók lekérdezésében: ${error.value}`
      );
    }
  }

  async function getUser(id) {
    error.value = null;
    try {
      const resp = await http.get(`/users/${id}`);
      user.value = resp.data.data;
    } catch (error) {
      error.value = error.message;
      throw error;
    }
  }

  const startSearchBy = (parameters) => {
    basedOnSearchLetters.value = parameters;
    applyFilter();
  };

  const applyFilter = () => {
    if (!basedOnSearchLetters) {
      userSearch.value = users.value;
      return;
    }
    const userTypedStringSetToLowerCase =
      basedOnSearchLetters.value.toLowerCase();
    userSearch.value = users.value.filter((user) => {
      const username = user.user_name.toLowerCase();
      return username.includes(userTypedStringSetToLowerCase);
    });
  };

  return {
    users,
    user,
    userSearch,
    basedOnSearchLetters,

    getUsers,
    getUser,
    startSearchBy,
    applyFilter,
  };
});
