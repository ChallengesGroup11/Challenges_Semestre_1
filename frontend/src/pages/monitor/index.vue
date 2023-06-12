<script setup lang="ts">
import moment from "moment"
import { ApiService } from "~/services/api"
import { useStoreUser } from "../../../stores/user"

const ListColumn = [
  {
    name: "slotBegin",
    label: "slot begin",
    align: "left",
    sortable: true,
    field: (row: any) => fn.formatDisplayDate(row.slotBegin),
  },
  {
    name: "slotEnd",
    label: "slot end",
    align: "left",
    sortable: true,
    field: (row: any) => fn.formatDisplayDate(row.slotEnd),
  },
]

const state = reactive({
  drivingSchoolName: useStoreUser().drivingSchool,
  ListBookingToConfirm: [],
  ListColumn: ListColumn as any[],
  ListRow: [] as any[],
  ListCurrentItemSelected: [] as any[],
  isLoading: true,
})

const fn = {
  formatDisplayDate(date: moment.MomentInput) {
    return moment(date).format("DD/MM/YYYY - hh:ss")
  },
  async handleTakingSlots() {
    for (let i = 0; i < state.ListCurrentItemSelected.length; i++) {
      await ApiService.patch("bookings", {
        id: state.ListCurrentItemSelected[i].id,
        statusValidate: true,
        monitorId: [`/monitors/${useStoreUser().user.monitor.id}`],
      })
      // update in useStoreUser the list of bookings with the new booking
      const currentBookingInStore = useStoreUser().ListBooking.find(
        (item: any) => item.id === state.ListCurrentItemSelected[i].id,
      )
      currentBookingInStore.monitorId = [`/monitors/${useStoreUser().user.monitor.id}`]
    }

    fn.removeFromListRowTheListCurrentItemSelected()
  },
  removeFromListRowTheListCurrentItemSelected() {
    for (let i = 0; i < state.ListCurrentItemSelected.length; i++) {
      state.ListRow = state.ListRow.filter((item: any) => item.id !== state.ListCurrentItemSelected[i].id)
    }
    state.ListCurrentItemSelected = []
  },
}

const loadData = async () => {
  const getUser = async () => {
    return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/me`, {
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
      },
    })
      .then((response) => response.json())
      .then((data) => {
        console.log(data)
        if (data.monitor.drivingSchoolId != null) {
          state.drivingSchoolName = data.monitor.drivingSchoolId.name
          if (data.monitor.drivingSchoolId.bookings != null) {
            state.ListBookingToConfirm = data.monitor.drivingSchoolId.bookings.filter(
              (item: any) => item.monitorId.length === 0 && item.studentId.length !== 0,
            )
          }
        }
      })
      .catch((error) => {
        console.error("Error:", error)
      })
  }

  await getUser()
  console.log(state.ListBookingToConfirm)
  state.ListRow = state.ListBookingToConfirm.map((item: any) => {
    return {
      id: item.id,
      slotBegin: item.slotBegin,
      slotEnd: item.slotEnd,
      // studentName: item.studentId[0].userId.firstname + " " + item.studentId[0].userId.lastname,
    }
  }).filter((item: any) => item.slotBegin > moment().format())
  state.isLoading = false
}
loadData()
</script>

<template>
  <div>
    <h1>Tableau de bord</h1>
    <h2>Mon auto-école: {{ state.drivingSchoolName }}</h2>
    <h2>Nombre de réservations à confirmer: {{ state.ListBookingToConfirm.length }}</h2>
    <q-table
      v-model:selected="state.ListCurrentItemSelected"
      title="Créneaux à confirmer"
      selection="multiple"
      :rows="state.ListRow"
      :columns="state.ListColumn"
      row-key="id"
      :loading="state.isLoading"
      class="mx-6"
    >
      <template v-slot:top>
        <template v-if="state.ListCurrentItemSelected[0]">
          <q-btn
            v-if="state.ListCurrentItemSelected"
            color="primary"
            label="Prendre les créneaux"
            @click="fn.handleTakingSlots"
          />
        </template>
        <q-space />
        <q-input borderless dense debounce="300" color="primary">
          <template v-slot:append>
            <q-icon name="search" />
          </template>
        </q-input>
      </template>
    </q-table>
  </div>
</template>

<route lang="yaml">
meta:
  layout: monitor
  requiresAuth: true
  roles: monitor
</route>
