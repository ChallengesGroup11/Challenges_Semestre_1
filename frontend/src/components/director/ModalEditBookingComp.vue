<script setup lang="ts">
import * as R from 'ramda'
import { fetchAll } from '../../services/api'
import { ApiService } from '~/services/api'
import { useStoreUser } from '../../../stores/user'
import moment from 'moment'
import { useQuasar } from "quasar"
import { boolean } from 'zod'

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
const emit = defineEmits<{
  (e: 'bookingEdited', payload: object): void
}>()

interface Booking {
  slotBegin: string
  slotEnd: string
  comment: string
  statusValidate: boolean
  statusDone: boolean
  drivingSchoolId: object[]
  monitorId: []
  // studentId: object[]
  id: number
}

const props = defineProps({
  booking: {
    type: Object as () => Booking,
    required: true,
  },
})



const state = reactive({
  booking: R.clone(props?.booking),
  isShownModal: true,
  ListStudent: [] as any[],
  ListMonitor: [] as any[],
})

const fn = {
  onClickSaveBooking: async () => {

    if(state.booking.statusValidate === false && state.booking.statusDone === false) {
      var resVerification = fn.verifySlot(state.booking.slotBegin, state.booking.slotEnd)
    }else{
      resVerification = true;
    }

    if (resVerification !== true) {
      return alert(resVerification)
    }
    // const editedBooking = R.clone(state.booking)
    // editedBooking.monitorId = editedBooking.monitorId.id
    if(state.booking.monitorId.length > 0) {
      state.booking.monitorId = ['/monitors/' + state.booking.monitorId[0].id]
    }
    if(state.booking.studentId.length > 0) {
      state.booking.studentId = ['/students/'+state.booking.studentId[0].id]
    }

    await ApiService.patch('bookings', state.booking)
    viewNotif("thumb_up", "green", "Votre session à bien été modifié", "white", "top-right")

    emit('bookingEdited', state.booking)
  },
  verifySlot(slotBegin: string, slotEnd: string) {
    if (slotBegin > slotEnd)
      return viewNotif("thumb_down", "red", "La date de début doit être inférieure à la date de fin", "white", "top-right")

    if (slotBegin === slotEnd)
      return viewNotif("thumb_down", "red", "La date de début doit être inférieure à la date de fin", "white", "top-right")


    if (slotBegin < moment().add(1, "days").format("YYYY-MM-DD"))
      return viewNotif("thumb_down", "red", "La date de début doit être supérieure à la date du jour", "white", "top-right")

    const resDif = moment(slotEnd).diff(moment(slotBegin), "minutes")

    if (resDif !== 60 && resDif !== 120)
      return viewNotif("thumb_down", "red", "La durée du créneau doit être de 1h ou 2h", "white", "top-right")

    return true
  },
}

const loadData = async () => {
}

loadData()
</script>

<template>
  <q-dialog v-model="state.isShownModal">
    <q-card style="width: 600px">
      <q-card-section>
        <div class="text-h6">Modifier un créneau</div>
      </q-card-section>

      <q-card-section class="q-pt-none">
        <q-form>
          <div class="q-pa-md" style="max-width: 300px">
            <q-input filled v-model="state.booking.slotBegin" label="début du créneau">
              <template v-slot:prepend>
                <q-icon name="event" class="cursor-pointer">
                  <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                    <q-date v-model="state.booking.slotBegin" mask="YYYY-MM-DD HH:mm">
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
                    <q-time v-model="state.booking.slotBegin" mask="YYYY-MM-DD HH:mm" format24h>
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
            <q-input filled v-model="state.booking.slotEnd" label="fin du créneau">
              <template v-slot:prepend>
                <q-icon name="event" class="cursor-pointer">
                  <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                    <q-date v-model="state.booking.slotEnd" mask="YYYY-MM-DD HH:mm">
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
                    <q-time v-model="state.booking.slotEnd" mask="YYYY-MM-DD HH:mm" format24h>
                      <div class="row items-center justify-end">
                        <q-btn v-close-popup label="Close" color="primary" flat />
                      </div>
                    </q-time>
                  </q-popup-proxy>
                </q-icon>
              </template>
            </q-input>

            <q-input filled v-model="state.booking.comment" label="Commentaire" />
            <!-- <q-select
              filled
              v-model="state.booking.studentId.id"
              :options="state.ListStudent"
              label="Élève"
              option-value="id"
              option-label="name"
            /> -->
            <!-- <q-select
              filled
              v-model="state.booking.monitorId"
              :options="state.ListMonitor"
              label="Moniteur"
              option-value="id"
              option-label="name"
            />
            monitor: {{ state.booking.monitorId }} ListMonitor : {{ state.ListMonitor }} -->

            <q-checkbox v-model="state.booking.statusValidate" label="Validé" />
            <q-checkbox v-model="state.booking.statusDone" label="Terminé" />
          </div>
        </q-form>
      </q-card-section>

      <q-card-actions align="right">
        <q-btn flat label="Enregistrer" color="primary" @click="fn.onClickSaveBooking" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>
