<script setup lang="ts">
import { reactive } from "vue"
import moment from "moment"
import { ApiService } from "~/services/api"
import { useStoreUser } from "../../../../../stores/user"
import * as R from "ramda"
const router = useRouter()
import { useQuasar } from "quasar"

const $q = useQuasar()
const viewNotif = (icon: any, color: string, message: string, textColor: string, position: any) => {
  $q.notify({
    icon,
    color,
    message,
    textColor,
    position,
  })
}
const data = {
  ListInitialBooking: [] as any[],
}
const state = reactive({ ListSlot: [] as Booking[], isShownModal: false, bookingClicked: {} as Booking })
const fn = {
  onClickBooking: (booking: Booking) => {
    state.bookingClicked = booking
    state.isShownModal = true
  },
  takeBooking: async (booking: Booking) => {
    try {
      console.log(useStoreUser().user)
      await ApiService.patch("bookings", { studentId: [useStoreUser().user.student], id: booking.id })
      data.ListInitialBooking = data.ListInitialBooking.filter((item: any) => item.id !== booking.id)
      viewNotif("thumb_up", "green", "Réservation effectuée", "white", "top-right")
      makeListFreeBooking(data.ListInitialBooking)
    } catch (e) {
      viewNotif("thumb_down", "red", "Vous n'avez pas assez de crédit: 2 minimum requis", "white", "top-right")
    }
    fn.resetCurrentBooking()
  },
  resetCurrentBooking: () => {
    state.bookingClicked = {} as Booking
    state.isShownModal = false
  },
}

const getListFreeSlot = async (id: string | string[]): Promise<any[]> => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/driving_schools/` + id, {
    headers: {
      Authorization: "Bearer " + localStorage.getItem("token"),
    },
  })
    .then((response) => response.json())
    .catch((error) => {
      console.error("Error:", error)
    })
}

type Booking = {
  slotBegin: string
  slotEnd: string
  statusValidate: boolean
  drivingSchoolId: string
}

const makeListFreeBooking = (data: any[]) => {
  const listFreeBooking = data.filter((item: any) => item.studentId.length === 0)
  // convert listFreeDate from date to timestamp
  const listFreeBookingWithTimestamp = listFreeBooking.map((item: any) => {
    return {
      ...item,
      slotBeginTimestamp: moment(item.slotBegin).unix(),
      slotEndTimestamp: moment(item.slotEnd).unix(),
    }
  })

  const listFreeBookingWithTimestampSorted = listFreeBookingWithTimestamp.sort((a: any, b: any) => {
    return a.slotBeginTimestamp - b.slotBeginTimestamp
  })

  // group listFreeBookingWithTimestampSorted by day
  const listFreeBookingWithTimestampSortedGroupByDay = listFreeBookingWithTimestampSorted.reduce(
    (acc: any, item: any) => {
      const date = moment(item.slotBegin).format("DD/MM/YYYY")
      if (!acc[date]) {
        acc[date] = []
      }
      acc[date].push(item)
      return acc
    },
    {},
  )

  state.ListSlot = listFreeBookingWithTimestampSortedGroupByDay
}

const loadData = async () => {
  const { params } = useRoute()
  const { id } = params
  const aData = await getListFreeSlot(id)
  data.ListInitialBooking = R.clone(aData.bookings)
  makeListFreeBooking(data.ListInitialBooking)
}

loadData()
</script>

<template>
  <div class="container">
    <q-dialog v-model="state.isShownModal">
      <q-card>
        <q-card-section>
          <div class="text-h6">
            Voulez vous vraiment réserver ce créneau du
            {{ moment(state.bookingClicked.slotBegin).locale("fr").format("DD/MM/YYYY à hh:ss") }} jusqu'à
            {{ moment(state.bookingClicked.slotEnd).format("hh:ss") }} ?
          </div>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn flat label="Annuler" color="primary" @click="fn.resetCurrentBooking" />
          <q-btn flat label="Confirmer" color="primary" @click="fn.takeBooking(state.bookingClicked)" />
        </q-card-actions>
      </q-card>
    </q-dialog>
    <h1 class="text-center">Disponibilités</h1>
    <div class="row">
      <q-card v-for="day in Object.keys(state.ListSlot)" class="container-card col-3">
        <h2>{{ day }}</h2>
        <q-chip clickable @click="fn.onClickBooking(slot)" v-for="slot in state.ListSlot[day]" class="q-my-lg">
          {{ moment(slot.slotBegin).format("hh:ss") }} -
          {{ moment(slot.slotEnd).format("hh:ss") }}
        </q-chip>
      </q-card>
    </div>
  </div>
</template>

<style scoped>
.container-card:first-child {
  gap: none !important;
}
.container-card {
  gap: 1;
}
</style>

<route lang="yaml">
meta:
  layout: student
  requiresAuth: true
  roles: user
</route>
