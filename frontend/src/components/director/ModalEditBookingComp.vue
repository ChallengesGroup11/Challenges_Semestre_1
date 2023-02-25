<script setup lang="ts">
import * as R from 'ramda'
import { fetchAll } from '../../services/api'
import { ApiService } from '~/services/api'
import { useStoreUser } from '../../../stores/user'

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
  // monitorId: object[]
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
  ListStudent: [],
  ListMonitor: [],
})

const fn = {
  onClickSaveBooking: async () => {
    await ApiService.patch('bookings', state.booking)
    emit('bookingEdited', state.booking)
  },
}

const loadData = async () => {
  // charger moniteurs et users
  state.ListStudent = (await ApiService.fetchAll('students')).map((student) => {
    return {
      id: student.userId.id,
      name: student.userId.firstname + ' ' + student.userId.lastname,
    }
  })
  state.ListMonitor = useStoreUser().ListMonitor.map((monitor) => {
    return {
      id: monitor.userId.id,
      name: monitor.userId.firstname + ' ' + monitor.userId.lastname,
    }
  })
  debugger
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
            />
            <q-select
              filled
              v-model="state.booking.monitorId.id"
              :options="state.ListMonitor"
              label="Moniteur"
              option-value="id"
              option-label="name"
            /> -->

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
