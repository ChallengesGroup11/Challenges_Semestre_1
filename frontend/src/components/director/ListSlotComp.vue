<script setup lang="ts">
import moment from "moment"
import { ApiService } from "~/services/api"
import { API_URL } from "../../services/api"
import { useStoreUser } from "../../../stores/user"
import { useQuasar } from "quasar"
import DrivingSchoolList from "../admin/DrivingSchool/DrivingSchoolList.vue"

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
interface Booking {
  slotBegin: string
  slotEnd: string
  comment: string
  statusValidate: boolean
  statusDone: boolean
  drivingSchoolId: []
  monitorId: []
  studentId: []
  id: number
}

const ListColumn = [
  {
    name: "slotBegin",
    label: "slot begin",
    align: "left",
    sortable: true,
    field: (row: Booking) => fn.formatDisplayDate(row.slotBegin),
  },
  {
    name: "slotEnd",
    label: "slot end",
    align: "left",
    sortable: true,
    field: (row: Booking) => fn.formatDisplayDate(row.slotEnd),
  },
  {
    name: "comment",
    label: "comment",
    align: "left",
    sortable: true,
    field: (row: { comment: any }) => row.comment,
  },
  {
    name: "statusValidate",
    label: "status validate",
    align: "left",
    sortable: true,
    field: (row: { statusValidate: any }) => row.statusValidate,
  },
  {
    name: "statusDone",
    label: "status done",
    align: "left",
    sortable: true,
    field: (row: { statusDone: any }) => row.statusDone,
  },
  {
    name: "studentId",
    label: "élève",
    align: "left",
    sortable: true,
    field: (row: { studentId: string }) => row.firstname + " " + row.lastname,
  },
  {
    name: "monitorId",
    label: "moniteur",
    align: "left",
    sortable: true,
    field: (row: { monitorId: string }) => row.Monitorfirstname + " " + row.Monitorlastname,
  },
]
const state = reactive({
  columns: ListColumn,
  rows: [] as Booking[],
  isLoading: false,
  isShownModal: false,
  newSlot: {
    slotBegin: "",
    slotEnd: "",
  },
  currentItemSelected: [] as Booking[],
  isShownModalEditing: false,
})

const fn = {
  async onClickResetRow() {
    const data = {
      id: state.currentItemSelected[0].id,
      slotBegin: state.currentItemSelected[0].slotBegin,
      slotEnd: state.currentItemSelected[0].slotEnd,
      comment: state.currentItemSelected[0].comment,
      statusValidate: state.currentItemSelected[0].statusValidate,
      statusDone: state.currentItemSelected[0].statusDone,
      drivingSchoolId: state.currentItemSelected[0].drivingSchoolId,
      monitorId: [],
      studentId: [],
    }
    await ApiService.patch("bookings", data)
    //todo mettre à jour la liste
    state.rows = state.rows.map((row) => {
      if (row.id === data.id) {
        // mettre studentID et monitorID à vide et non undefined

        data.firstname = ""
        data.lastname = ""
        data.Monitorfirstname = ""
        data.Monitorlastname = ""

        return data
      }
      return row
    })
    viewNotif("thumb_up", "green", "Votre session à bien été vidé", "white", "top-right")
  },

  onBookingEdited(booking: Booking) {
    state.rows = state.rows.map((row) => {
      if (row.id === booking.id) {
        return booking
      }
      return row
    })
    state.isShownModalEditing = false
  },
  formatDisplayDate(date: moment.MomentInput) {
    return moment(date).format("DD/MM/YYYY HH:ss")
  },

  async onClickSaveBooking() {
    const resVerification = fn.verifySlot(state.newSlot.slotBegin, state.newSlot.slotEnd)

    if (resVerification !== true) {
      return
    }

    const newRowDb = {
      slotBegin: state.newSlot.slotBegin,
      slotEnd: state.newSlot.slotEnd,
      comment: "",
      statusValidate: false,
      statusDone: false,
      drivingSchoolId: "/driving_schools/" + useStoreUser().drivingSchool.id,
    }

    const res = await ApiService.insert(API_URL.BOOKINGS, newRowDb)
    viewNotif("thumb_up", "green", "Votre session à bien été créée", "white", "top-right")

    //mettre monitor et student à vide avant de push
    res.firstname = ""
    res.lastname = ""
    res.Monitorfirstname = ""
    res.Monitorlastname = ""

    state.rows.push(res)

    state.isShownModal = false
  },
  async onClickDeleteRow() {
    const itemToDelete = state.currentItemSelected[0]
    await ApiService.deleteById(API_URL.BOOKINGS, itemToDelete.id)
    viewNotif("thumb_up", "green", "Votre seession à bien été supprimée ", "white", "top-right")
    state.rows = state.rows.filter((item) => item.id !== itemToDelete.id)
  },

  onClickModifyRow() {
    state.isShownModalEditing = true
  },

  verifySlot(slotBegin: string, slotEnd: string) {
    if (slotBegin > slotEnd)
      return viewNotif(
        "thumb_down",
        "red",
        "La date de début doit être inférieure à la date de fin",
        "white",
        "top-right",
      )

    if (slotBegin === slotEnd)
      return viewNotif(
        "thumb_down",
        "red",
        "La date de début doit être inférieure à la date de fin",
        "white",
        "top-right",
      )

    if (slotBegin < moment().add(1, "days").format("YYYY-MM-DD"))
      return viewNotif(
        "thumb_down",
        "red",
        "La date de début doit être supérieure à la date du jour",
        "white",
        "top-right",
      )

    const resDif = moment(slotEnd).diff(moment(slotBegin), "minutes")

    if (resDif !== 60 && resDif !== 120)
      return viewNotif("thumb_down", "red", "La durée du créneau doit être de 1h ou 2h", "white", "top-right")

    return true
  },
}

const fetchAll = async () => {
  if (useStoreUser().drivingSchool.id === undefined) return
  const response = await ApiService.fetchbById(API_URL.DRIVING_SHCOOLS, useStoreUser().drivingSchool.id)
  const ListBooking = response.bookings
  console.log(ListBooking)
  for await (const booking of ListBooking) {
    if (booking.studentId.length !== 0) {
      const responeStudent = await ApiService.fetchbById(API_URL.STUDENTS, booking.studentId[0].id)
      const firstname = responeStudent.userId.firstname
      const lastname = responeStudent.userId.lastname

      booking.firstname = firstname
      booking.lastname = lastname
    } else {
      booking.firstname = ""
      booking.lastname = ""
    }
    if (booking.monitorId.length !== 0) {
      const responseMonitor = await ApiService.fetchbById(API_URL.MONITORS, booking.monitorId[0].id)
      const firstnameMonitor = responseMonitor.userId.firstname
      const lastnameMonitor = responseMonitor.userId.lastname

      booking.Monitorfirstname = firstnameMonitor
      booking.Monitorlastname = lastnameMonitor
    } else {
      booking.Monitorfirstname = ""
      booking.Monitorlastname = ""
    }
  }
  state.rows = ListBooking
}

const loadData = async () => {
  state.rows = []
  fetchAll()
}

loadData()
</script>

<template>
  <div class="q-pa-md">
    <ModalEditBookingComp
      v-if="state.isShownModalEditing"
      :booking="state.currentItemSelected[0]"
      @booking-edited="fn.onBookingEdited"
    />

    <q-dialog v-model="state.isShownModal">
      <q-card style="width: 600px">
        <q-card-section>
          <div class="text-h6">Ajouter un créneau</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
          <q-form>
            <div class="q-pa-md" style="max-width: 300px">
              <q-input filled v-model="state.newSlot.slotBegin" label="début du créneau">
                <template v-slot:prepend>
                  <q-icon name="event" class="cursor-pointer">
                    <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                      <q-date v-model="state.newSlot.slotBegin" mask="YYYY-MM-DD HH:mm">
                        <div class="row items-center justify-end">
                          <q-btn v-close-popup label="Close" color="primary" flat />
                        </div>
                      </q-date>
                    </q-popup-proxy>
                  </q-icon>
                </template>

                <template v-slot:append>
                  <q-icon name="access_time" class="cursor-pointer">
                    <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                      <q-time v-model="state.newSlot.slotBegin" mask="YYYY-MM-DD HH:mm" format24h>
                        <div class="row items-center justify-end">
                          <q-btn v-close-popup label="Close" color="primary" flat />
                        </div>
                      </q-time>
                    </q-popup-proxy>
                  </q-icon>
                </template>
              </q-input>
            </div>

            <div class="q-pa-md" style="max-width: 300px">
              <q-input filled v-model="state.newSlot.slotEnd" label="fin du créneau">
                <template v-slot:prepend>
                  <q-icon name="event" class="cursor-pointer">
                    <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                      <q-date v-model="state.newSlot.slotEnd" mask="YYYY-MM-DD HH:mm">
                        <div class="row items-center justify-end">
                          <q-btn v-close-popup label="Close" color="primary" flat />
                        </div>
                      </q-date>
                    </q-popup-proxy>
                  </q-icon>
                </template>

                <template v-slot:append>
                  <q-icon name="access_time" class="cursor-pointer">
                    <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                      <q-time v-model="state.newSlot.slotEnd" mask="YYYY-MM-DD HH:mm" format24h>
                        <div class="row items-center justify-end">
                          <q-btn v-close-popup label="Close" color="primary" flat />
                        </div>
                      </q-time>
                    </q-popup-proxy>
                  </q-icon>
                </template>
              </q-input>
            </div>
          </q-form>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Enregistrer" color="primary" @click="fn.onClickSaveBooking" />
        </q-card-actions>
      </q-card>
    </q-dialog>
    <q-table
      v-model:selected="state.currentItemSelected"
      title="Treats"
      selection="single"
      :rows="state.rows"
      :columns="state.columns"
      row-key="id"
      :loading="state.isLoading"
    >
      <template v-slot:top>
        <q-btn
          color="primary"
          :disable="state.isLoading"
          label="Ajouter un créneau"
          @click="state.isShownModal = true"
          class="mr-1"
        />
        <template v-if="state.currentItemSelected[0]">
          <q-btn
            v-if="state.currentItemSelected"
            color="primary"
            label="Modifier"
            @click="fn.onClickModifyRow"
            class="mr-1"
          />
          <q-btn
            v-if="state.currentItemSelected"
            color="primary"
            label="Supprimer"
            @click="fn.onClickDeleteRow"
            class="mr-1"
          />
          <q-btn
            v-if="state.currentItemSelected"
            color="primary"
            label="Liberer le créneau"
            @click="fn.onClickResetRow"
            class="mr-1"
          />
        </template>
        <q-space />
        <q-input borderless dense debounce="300" color="primary">
          <template v-slot:append> </template>
        </q-input>
      </template>
    </q-table>
  </div>
</template>
