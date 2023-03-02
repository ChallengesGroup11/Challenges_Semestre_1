<script setup lang="ts">
import { reactive } from 'vue'
import moment from 'moment'
const router = useRouter()

const state = reactive({ ListSlot: [] as Booking[] })

const fn = {
  takeBooking: async (id: string) => {
    console.log(id)
  },
}

const getListFreeSlot = async (id: string | string[]) => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/driving_schools/` + id, {
    headers: {
      Authorization: 'Bearer ' + localStorage.getItem('token'),
    },
  })
    .then((response) => response.json())
    .catch((error) => {
      console.error('Error:', error)
    })
}

type Booking = {
  slotBegin: string
  slotEnd: string
  statusValidate: boolean
  drivingSchoolId: string
}

const loadData = async () => {
  const { params } = useRoute()
  const { id } = params
  const data = await getListFreeSlot(id)
  state.ListSlot = data.bookings.filter((item: any) => item.statusValidate === false)
}

loadData()
</script>

<template>
  <div class="container">
    <h1 class="text-center">Disponibilités</h1>
    <div class="row">
      <div v-for="slot in state.ListSlot" class="col-4 q-my-lg">
        {{ moment(slot.slotBegin).format('DD/MM/YYYY hh:ss') }} -
        {{ moment(slot.slotEnd).format('DD/MM/YYYY hh:ss') }}
        <q-button class="q-ml-md" color="primary" label="Réserver" @click="fn.takeBooking(slot)">Réserver</q-button>
      </div>
    </div>
  </div>
</template>

<route lang="yaml">
meta:
  layout: student
  requiresAuth: true
  roles: user
</route>
