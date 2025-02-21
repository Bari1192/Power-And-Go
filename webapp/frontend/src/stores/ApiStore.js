import { defineStore } from "pinia"
import { http } from "@utils/http.mjs"

export const useApiStore = defineStore("api", {
  state: () => ({
    cars: [],
    currentCar: {
      details: null,
      tickets: [],
      description: null,
      rentHistory: null,
      fees: []
    },
    loading: false,
    error: null
  }),

  actions: {
    async fetchCars() {
      this.loading = true
      try {
        const response = await http.get("/cars")
        this.cars = response.data.data
      } catch (error) {
        console.error(error)
      } finally {
        this.loading = false
      }
    },

    async loadCarData(id) {
      this.loading = true
      try {
        const details = await http.get(`/cars/${id}`)
        const tickets = await http.get(`/cars/${id}/tickets`)
        const description = await http.get(`/cars/${id}/description`)
        const rentHistory = await http.get(`/cars/${id}/renthistory`)
        const fees = await http.get(`/bills/${id}/fees`)

        this.currentCar = {
          details: details.data.data,
          tickets: tickets.data.data,
          description: description.data.data,
          rentHistory: rentHistory.data.data,
          fees: fees.data.data
        }
      } catch (error) {
        console.error(error)
      } finally {
        this.loading = false
      }
    }
  }
})