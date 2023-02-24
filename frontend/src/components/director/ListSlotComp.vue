<script setup lang="ts">
import { ApiService } from '~/services/api'
import { API_URL } from '../../services/api'
import { useStoreUser } from '../../../stores/user'

interface Booking {
  slotBegin: string
  slotEnd: string
  comment: string
  statusValidate: boolean
  statusDone: boolean
  drivingSchoolId: []
  monitorId: []
}

const ListColumn = [
  {
    name: 'slotBegin',
    label: 'slot begin',
    align: 'left',
    sortable: true,
    field: (row) => row.slotBegin,
  },
  {
    name: 'slotEnd',
    label: 'slot end',
    align: 'left',
    sortable: true,
    field: (row) => row.slotEnd,
  },
  {
    name: 'comment',
    label: 'comment',
    align: 'left',
    sortable: true,
    field: (row) => row.comment,
  },
  {
    name: 'statusValidate',
    label: 'status validate',
    align: 'left',
    sortable: true,
    field: (row) => row.statusValidate,
  },
  {
    name: 'statusDone',
    label: 'status done',
    align: 'left',
    sortable: true,
    field: (row) => row.statusDone,
  },
]
const state = reactive({
  columns: ListColumn,
  rows: [] as Booking[],
  isLoading: false,
  isShownModal: false,
  newSlot: {
    slotBegin: '',
    slotEnd: '',
  },
})

const fn = {
  async onClickSaveBooking() {
    // state.rows.push({
    //   slotBegin: '2021-06-01',
    //   slotEnd: '2021-06-01',
    //   comment: 'comment',
    //   statusValidate: true,
    //   statusDone: true,
    //   drivingSchoolId: 1,
    //   studentId: 1,
    //   monitorId: 1,
    // })
    console.log(useStoreUser().drivingSchool)
    const newRowDb = {
      slotBegin: state.newSlot.slotBegin,
      slotEnd: state.newSlot.slotEnd,
      comment: '',
      statusValidate: false,
      statusDone: false,
      drivingSchoolId: useStoreUser().drivingSchool,
    }

    const res = await ApiService.insert(API_URL.BOOKINGS, newRowDb)
    console.log(res)
    state.rows.push(res.data)

    state.isShownModal = false
  },
  removeRow() {
    const slot = state.rows.pop()
    // const id = slot['@id']
    // // get only the last string after the last slash
    // const idNumber = id.substring(id.lastIndexOf('/') + 1)

    // fetch('https://localhost/bookings/' + idNumber, {
    //   method: 'DELETE',
    //   headers: {
    //     Authorization: 'Bearer ' + localStorage.getItem('token'),
    //   },
    // })
    //   .then((response) => 1)
    //   .then((data) => {
    //     console.log('Success:', data)
    //   })
    //   .catch((error) => {
    //     console.error('Error:', error)
    //   })
  },
}

const fetchAll = async () => {
  const response = await fetch('https://localhost/bookings', {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`,
      'Content-Type': 'application/json',
    },
  })
  const data = await response.json()
  const allData = data['hydra:member']

  state.rows = allData
}

const loadData = async () => {
  state.rows = []
  fetchAll()
}

loadData()
</script>

<template>
  <div class="q-pa-md">
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
    <q-table title="Treats" :rows="state.rows" :columns="state.columns" row-key="id" :loading="state.isLoading">
      <template v-slot:top>
        <q-btn
          color="primary"
          :disable="state.isLoading"
          label="Ajouter un créneau"
          @click="state.isShownModal = true"
        />
        <q-btn
          class="q-ml-sm"
          color="primary"
          :disable="state.isLoading"
          label="Retirer un créneau"
          @click="fn.removeRow"
        />
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
