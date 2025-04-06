import { defineStore } from "pinia";
import { http } from "@utils/http.mjs";
import { ToastService } from "@layouts/toasts/ToastService.js";
import { computed, ref } from "vue";

export const useAuthStore = defineStore("auth", () => {
  // State
  const user = ref(null);
  const token = ref(null);
  const role = ref(null);
  const isLoggedIn = ref(false);
  const initialized = ref(false);
  const error = ref(null);

  // "Getters"
  const isAuthenticated = computed(() => isLoggedIn.value);
  const userRole = computed(() => role.value);
  const isAdmin = computed(() => role.value == "admin");
  const isDeveloper = computed(() => role.value == "developer");
  const isUser = computed(() => role.value == "user");

  // "Actions"
  function initializeFromStorage() {
    const storedToken = localStorage.getItem("token");
    const storedUser = localStorage.getItem("user");
    if (storedToken && storedUser) {
      token.value = storedToken;
      user.value = JSON.parse(storedUser);
      role.value = user.value.role || "user";
      isLoggedIn.value = true;
      // A Token beállítása itt történik a
      // HTTP kliens "alapértelmezett" headörébe.
      http.defaults.headers.common["Authorization"] = `Bearer ${token.value}`;
    }
    initialized.value = true;
  }

  function setUserData(newToken, userData) {
    token.value = newToken;
    user.value = userData;
    role.value = userData.role || "user"; // Kiküszöbölve a réseket/hibát előre is!
    isLoggedIn.value = true;

    // Lementés
    localStorage.setItem("token", newToken);
    localStorage.setItem("user", JSON.stringify(userData));

    // ismét beállítjuk a headörbe
    http.defaults.headers.common["authorization"] = `Bearer ${newToken}`;
  }

  async function login(email, password) {
    const toastId = ToastService.showLoading("Bejelentkezés...");
    error.value = null;
    try {
      const resp = await http.post("/authenticatelogin", {
        email,
        password,
      });
      const { token: newToken, user: userData } = resp.data.data;
      setUserData(newToken, userData);
      ToastService.updateToSuccess(toastId, "Sikeres bejelentkezés");
    } catch (err) {
      error.value = err.message;
      ToastService.updateToError(
        toastId,
        `Sikertelen bejelentkezés: ${error.value}`
      );
    }
  }

  function logout() {
    user.value = null;
    token.value = null;
    role.value = null;
    isLoggedIn.value = false;
    
    // Az Authorization header törlése
    delete http.defaults.headers.common["authorization"];
    
    // localStorage tisztítása
    localStorage.removeItem("token");
    localStorage.removeItem("user");
  }

  return {
    // state-ek
    user,
    token,
    role,
    isLoggedIn,
    initialized,
    // getter-ek
    isAuthenticated,
    userRole,
    isAdmin,
    isDeveloper,
    isUser,
    // action-z részek
    initializeFromStorage,
    login,
    logout,
    setUserData,
  };
}, {
  // pinia-plugin-persistedstate használata miatt van
  persist: true,
});