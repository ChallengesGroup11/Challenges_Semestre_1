import { defineStore } from 'pinia'

export const useStoreUser = defineStore('counter', {
  state: () => ({
    user: {},
    drivingSchool: [],
  }),
  getters: {},
})
