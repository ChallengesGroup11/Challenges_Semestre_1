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
  <div>
    <h1>Mon profil</h1>
    <h2>Bonjour {{ state.name }}</h2>

    <h2>Mes créneaux</h2>
    <q-card>
      <h3>Créneaux passés à finaliser ({{ state.ListBookingToDone.length }})</h3>
      <div class="row">
        <q-card v-for="bookingToValidate in state.ListBookingToDone" class="container-card col-3">
          <q-chip class="q-my-lg">
            {{ moment(bookingToValidate.slotBegin).locale("fr").format("DD/MM/YYYY à hh:ss") }} -
            {{ moment(bookingToValidate.slotEnd).format("hh:ss") }}
          </q-chip>

          <q-input type="textarea" label="commentaire" outlined v-model="bookingToValidate.comment" />
          <q-btn flat label="Confirmer" color="primary" @click="fn.validateBooking(bookingToValidate)" />
        </q-card>
      </div>
    </q-card>
    <q-card class="mt-3">
      <h3>Créneaux à venir({{ state.ListBookingInFuture.length }})</h3>
      <div class="row">
        <q-card v-for="bookingInFuture in state.ListBookingInFuture" class="container-card col-3">
          <q-chip class="q-my-lg">
            {{ moment(bookingInFuture.slotBegin).locale("fr").format("DD/MM/YYYY à hh:ss") }} -
            {{ moment(bookingInFuture.slotEnd).format("hh:ss") }}
          </q-chip>
          <q-btn
            flat
            label="Libérer le créneau"
            color="primary"
            @click="fn.deleteBookingSlotByTheMonitor(bookingInFuture)"
          />
        </q-card>
      </div>
    </q-card>
  </div>
</template>

<route lang="yaml">
meta:
  layout: monitor
  requiresAuth: true
  roles: user
</route>
