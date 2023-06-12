<script setup lang="ts">
import moment from "moment"
import { useStoreUser } from "../../../stores/user"
import { ApiService } from "~/services/api"

const state = reactive({
  name: useStoreUser().user.firstname + " " + useStoreUser().user.lastname,
  monitorId: useStoreUser().user.monitor.id,
  ListBookingToDone: [],
  ListBookingInFuture: [],
})

const fn = {
  validateBooking: async (booking: any) => {
    await ApiService.patch("bookings", {
      id: booking.id,
      statusDone: true,
      comment: booking.comment,
    }),
      await Promise.all([
        await ApiService.patchDecrementCountCredit(booking.studentId[0].split("/")[2], {
          studentId: booking.studentId[0].split("/")[2],
          countCredit: 2,
        }),
      ])
      state.ListBookingToDone = state.ListBookingToDone.filter((item: any) => item.id !== booking.id)
  },
  deleteBookingSlotByTheMonitor: async (booking: any) => {
    await ApiService.patch("bookings", {
      id: booking.id,
      monitorId: [],
    })
    state.ListBookingInFuture = state.ListBookingInFuture.filter((item: any) => item.id !== booking.id)
  },
}

const loadData = () => {
  const bookingsDrivingSchool = useStoreUser().ListBooking

  const ListBookingOfCurrentMonitor = bookingsDrivingSchool.filter(
    (booking: any) => booking.monitorId[0] === `/monitors/${state.monitorId}`,
  )

  state.ListBookingToDone = ListBookingOfCurrentMonitor.filter(
    (booking: any) =>
      booking.statusDone === false &&
      booking.statusValidate === true &&
      moment(booking.slotBegin).isBefore(moment()) &&
      moment(booking.slotEnd).isBefore(moment()),
  ).sort((a: any, b: any) => moment(a.slotBegin).unix() - moment(b.slotBegin).unix())

  state.ListBookingInFuture = ListBookingOfCurrentMonitor.filter(
    (booking: any) =>
      booking.statusDone === false &&
      moment(booking.slotBegin).isAfter(moment()) &&
      moment(booking.slotEnd).isAfter(moment()),
  ).sort((a: any, b: any) => moment(a.slotBegin).unix() - moment(b.slotBegin).unix())
}

loadData()
</script>

<template>
  <q-card class="q-pa-md m-4">
    <q-card-section class="q-pa-md mr-4">

      <div>
        <h1>Bonjour {{ state.name }}</h1>
        <h2>Mes créneaux</h2>

        <q-card class="q-mt-md bg-primary">
          <q-card-section>
            <h3 style="color: #E76F51; font-size: 1.5em">
              Créneaux passés à finaliser ({{ state.ListBookingToValidate.length }})</h3>
            <div class="row">
              <q-card v-for="bookingToValidate in state.ListBookingToValidate" class="container-card col-3 bg-secondary">
                <q-chip class="q-my-lg" style="background-color: #E76F51;">
                  {{ moment(bookingToValidate.slotBegin).locale("fr").format("DD/MM/YYYY à hh:ss") }} -
                  {{ moment(bookingToValidate.slotEnd).format("hh:ss") }}
                </q-chip>

                <q-input type="textarea" label="commentaire" outlined v-model="bookingToValidate.comment" class="m-4" />
                <q-btn flat label="Confirmer" @click="fn.validateBooking(bookingToValidate)" class="btn-primary m-4" />
              </q-card>
            </div>
          </q-card-section>
        </q-card>


        <q-card class="q-mt-md bg-primary">
          <q-card-section>
            <h3 class="q-mb-md" style="color: #E76F51; font-size: 1.5em">
              Créneaux à venir ({{ state.ListBookingInFuture.length }})</h3>
            <div class="row gap-2">
              <q-card v-for="bookingInFuture in state.ListBookingInFuture"
                class="container-card col-3 bg-secondary rounded-2">
                <q-chip class="q-my-lg rounded-2" style="background-color: #E76F51;">
                  {{ moment(bookingInFuture.slotBegin).locale("fr").format("DD/MM/YYYY à hh:ss") }} -
                  {{ moment(bookingInFuture.slotEnd).format("hh:ss") }}
                </q-chip>
                <q-btn flat label="Libérer le créneau" class="btn-primary m-4"
                  @click="fn.deleteBookingSlotByTheMonitor(bookingInFuture)" />
              </q-card>
            </div>

          </q-card-section>
        </q-card>
      </div>
    </q-card-section>
  </q-card>
</template>

<style lang="scss" scoped>
.btn-primary {
  background: #9999C3;

}
</style>

<route lang="yaml">
meta:
  layout: monitor
  requiresAuth: true
  roles: user
</route>
