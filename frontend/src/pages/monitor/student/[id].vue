<script setup lang="ts">
import { domUtil } from "~/utils/domUtil"
import { API_URL, ApiService } from "~/services/api"

// -> pour chaque élève, quand on clique, get tous ses créneaux avec (moniteur, élève, date, heure début, heure fin, statut, commentaires)

const state = reactive({
  monitor: null as any,
})

const loadData = async () => {
  state.monitor = await ApiService.fetchbById(API_URL.STUDENTS, domUtil.extractIdRouter())
}

loadData()

// const state = reactive ({
//   monitor:
// })
</script>
<template>
  <!-- <div v-if="currentUser.value.student">
    <q-card class="my-card q-ma-lg bg-secondary">
      <q-card-section>
        <div class="text-h4">Mes Réservations</div>
        <q-markup-table class="q-ma-md">
          <thead>
            <tr>
              <th>Jour de la session</th>
              <th>Heure de début</th>
              <th>Heure de fin</th>
              <th>Nom de l'école</th>
              <th>Numéro de téléphone</th>
              <th>Adresse</th>
              <th>Valider ?</th>
              <th>Heure terminé</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody v-if="currentUser.value.student">
            <tr v-if="!currentUser.value.student.bookings">
              <td colspan="9">Pas de réservation de session effectué(e)</td>
            </tr>
            <tr v-for="(bookings, index) in currentUser.value.student.bookings" :key="index">
              <td>{{ dateJour(bookings.slotBegin) }}</td>
              <td>{{ dateHeure(bookings.slotBegin) }}</td>
              <td>{{ dateHeure(bookings.slotEnd) }}</td>
              <td>{{ bookings.drivingSchoolId[0].name }}</td>
              <td>{{ bookings.drivingSchoolId[0].phoneNumber }}</td>
              <td>{{ bookings.drivingSchoolId[0].city }}</td>
              <td v-if="bookings.statusValidate === true">
                <q-icon name="mood" color="positive" size="20px" />
              </td>
              <td v-else>
                <q-icon name="mood_bad" color="negative" size="20px" />
              </td>
              <td v-if="bookings.statusDone === true">
                <q-icon name="mood" color="positive" size="20px" />
              </td>
              <td v-else>
                <q-icon name="mood_bad" color="negative" size="20px" />
              </td>
              <td v-if="bookings.statusDone === true">
                <q-btn disabled color="secondary" label="Annuler" />
              </td>
              <td v-else>
                <q-btn color="primary" label="Annuler" @click="fn.cancelBooking(bookings)" />
              </td>
            </tr>
          </tbody>
        </q-markup-table>
      </q-card-section>
    </q-card>
  </div> -->
</template>
<route lang="yaml">
meta:
  layout: monitor
  requiresAuth: true
  roles: user
</route>
