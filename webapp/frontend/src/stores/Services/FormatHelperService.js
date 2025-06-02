import { defineStore } from "pinia";

export const useFormatStore = defineStore("format", () => {
  const formatToOneThousandPrice = (price) => {
    try {
      if (!price && price !== 0) return "0";
      const numPrice = Number(price);
      if (isNaN(numPrice)) return "0";
      return numPrice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    } catch (error) {
      console.error("Hiba az ár formázása során:", error);
      return "0";
    }
  };

  return {
    formatToOneThousandPrice,
  };
});
