<script setup lang="ts">
import moment from "moment"
import { useStoreUser } from "../../../stores/user"
import { ApiService } from "~/services/api"

const state = {
  name: useStoreUser().user.firstname + " " + useStoreUser().user.lastname,
  monitorId: useStoreUser().user.monitor.id,
  ListBookingToValidate: [],
  ListBookingInFuture: [],
}

const fn = {
  validateBooking: async (booking: any) => {
    await ApiService.patch("bookings", {
      id: booking.id,
      statusValidate: true,
    }),
      await Promise.all([
        await ApiService.patchDecrementCountCredit(booking.studentId[0].split("/")[2], {
          studentId: booking.studentId[0].split("/")[2],
          countCredit: 2,
        }),
      ])
  },
}

const loadData = () => {
  const bookingsDrivingSchool = useStoreUser().drivingSchool.bookings

  const ListBookingOfCurrentMonitor = bookingsDrivingSchool.filter(
    (booking: any) => booking.monitorId[0] === `/monitors/${state.monitorId}`,
  )

  state.ListBookingToValidate = ListBookingOfCurrentMonitor.filter(
    (booking: any) =>
      booking.statusValidate === false &&
      moment(booking.slotBegin).isBefore(moment()) &&
      moment(booking.slotEnd).isBefore(moment()),
  ).sort((a: any, b: any) => moment(a.slotBegin).unix() - moment(b.slotBegin).unix())

  state.ListBookingInFuture = ListBookingOfCurrentMonitor.filter(
    (booking: any) =>
      booking.statusValidate === false &&
      moment(booking.slotBegin).isAfter(moment()) &&
      moment(booking.slotEnd).isAfter(moment()),
  ).sort((a: any, b: any) => moment(a.slotBegin).unix() - moment(b.slotBegin).unix())

  console.table(state.ListBookingToValidate)
}

loadData()
</script>

<template>
  <div>
    <h1>Mon profil</h1>
    <h2>Bonjour {{ state.name }}</h2>
    <h2>Mes créneaux</h2>

    <h3>Créneaux passés à finaliser ({{ state.ListBookingToValidate.length }})</h3>
    <div class="row">
      <q-card v-for="bookingToValidate in state.ListBookingToValidate" class="container-card col-3">
        <q-chip class="q-my-lg">
          {{ moment(bookingToValidate.slotBegin).locale("fr").format("DD/MM/YYYY à hh:ss") }} -
          {{ moment(bookingToValidate.slotEnd).format("hh:ss") }}
        </q-chip>

        <q-input type="textarea" label="commentaire" outlined v-model="bookingToValidate.comment" />
        <q-btn flat label="Confirmer" color="primary" @click="fn.validateBooking(bookingToValidate)" />
      </q-card>
    </div>

    <h3>Créneaux à venir({{ state.ListBookingInFuture.length }})</h3>
    <div class="row">
      <q-card v-for="bookingInFuture in state.ListBookingInFuture" class="container-card col-3">
        <q-chip class="q-my-lg">
          {{ moment(bookingInFuture.slotBegin).locale("fr").format("DD/MM/YYYY à hh:ss") }} -
          {{ moment(bookingInFuture.slotEnd).format("hh:ss") }}
        </q-chip>
        <q-btn flat label="Confirmer" color="primary" @click="fn.validateBooking(bookingInFuture)" />
      </q-card>
    </div>
  </div>
</template>

<route lang="yaml">
meta:
  layout: monitor
  requiresAuth: true
  roles: user
</route>
