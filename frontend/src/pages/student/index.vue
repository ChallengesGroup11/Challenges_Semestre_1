<script setup lang="ts">
import { reactive, defineComponent } from "vue"
import moment from "moment"
import { ApiService } from "~/services/api"
import { useStoreUser } from "../../../stores/user"

const router = useRouter()

const currentUser = reactive({ value: [] })

const dateJour = (date: moment.MomentInput) => {
  return moment(date).format(" DD/MM/YYYY ")
}
const dateHeure = (date: moment.MomentInput) => {
  return moment(date).format("hh:ss ")
}

const getUser = async () => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/me`, {
    headers: {
      Authorization: "Bearer " + localStorage.getItem("token"),
    },
  })
    .then((response) => response.json())
    .then((data) => {
      currentUser.value = data
      useStoreUser().user = data
    })
    .catch((error) => {
      console.error("Error:", error)
    })
}

const editer = (id: string) => {
  router.push("/student/edit/" + id)
}

const addCodeAndCni = (id: string) => {
  router.push("/student/add/codecertification/" + id)
}

const redirectToBuyPackage = () => {
  router.push("/student/package")
}

const redirectToDrivingSchoolList = () => {
  router.push("/student/driving_school/list")
}



const fn = {
  async cancelBooking(booking: any) {
    await ApiService.patch("bookings", {
      id: booking.id,
      studentId: [],
    })
    currentUser.value.student.bookings = currentUser.value.student.bookings.filter((b: any) => b.id !== booking.id)
  },
}

const loadData = () => {
  getUser()
}

loadData()
</script>

<template>
  <div class="q-pa-md">
    <div class="row">


      <q-card class="my-card q-ma-lg bg-secondary col">
        <q-card-section>
          <div class="text-h4">Mes crédits</div>
          <div v-if="currentUser?.value?.student" class="vertical text-counter">
            <span v-if="currentUser?.value?.student?.countCredit !== null &&  currentUser?.value?.student?.countCredit > 0">
              {{ currentUser?.value?.student.countCredit }}
            </span>
            <span v-else class="vertical text-counter"> 0</span>
          </div>
          <div v-else class="vertical text-counter">0</div>
          <div>
            <q-btn
              class="btn-primary"
              label="Acheter des crédits"
              push size="md"
              @click='redirectToBuyPackage()'
              :disable="!currentUser?.value?.student?.contentUrlCode && !currentUser?.value?.student?.contentUrlCni"
          />
          </div>
        </q-card-section>
      </q-card>

      <q-card class="my-card q-ma-lg bg-secondary col">
        <q-card-section class="vertical-middle">
          <div class="text-h4">Mes heures effectuées</div>
          <div v-if="currentUser.value.student" class="vertical text-counter">
            <span v-if="currentUser.value.student.nbHourDone !== null">
              {{ currentUser.value.student.nbHourDone }}
            </span>
            <span v-else class="vertical text-counter">0</span>
          </div>
          <span v-else class="vertical text-counter">0</span>
          <div>
            <q-btn
              class="btn-primary"
              label="Réserver des heures"
              push size="md"
              @click='redirectToDrivingSchoolList()'
              :disable="!currentUser?.value?.student?.contentUrlCode && !currentUser?.value?.student?.contentUrlCni"
                />
          </div>
        </q-card-section>
      </q-card>
    </div>

    <div>
      <q-card class="my-card q-ma-lg bg-primary column">
        <q-card-section class="column">
          <div class="text-h4 row-center">
            {{ currentUser.value.firstname }} {{ currentUser.value.lastname }}
            <q-icon v-if="currentUser?.value?.student?.contentUrlCode && currentUser?.value?.student?.contentUrlCni" style="margin-left: 8px;" color="positive" name="o_check_circle" />
            <q-icon v-else style="margin-left: 8px; color: #C10015;" size="sm" color="error" name="o_cancel" />
          </div>
          <div style="align-self: center;">
            <div class="text-left text-h6 ">{{ currentUser.value.email }}</div>
            <div v-if="!currentUser.value.student" class="text-center q-mt-lg">
              <q-btn
                label="Ajouter votre Code et CNI"
                push
                size="md"
                @click="addCodeAndCni(currentUser.value.id)"
                color="positive"
                icon="add"
              />
            </div>
            <div v-if="currentUser.value.student" class="text-left text-h6">
              Code de la route
                <q-icon  v-if="currentUser.value.student.contentUrlCode" style="vertical-align: text-top" size="sm" color="positive" name="check" />
                <q-icon v-else style="vertical-align: text-top; color: #C10015;" size="sm" color="error" name="o_cancel" />
            </div>
            <div v-if="currentUser.value.student" class="text-left text-h6">
              CNI
                <q-icon v-if="currentUser.value.student.contentUrlCni" style="vertical-align: text-top" size="sm" color="positive" name="check" />
                <q-icon v-else style="vertical-align: text-top; color: #C10015;" size="sm" color="error" name="o_cancel" />
              </div>
          </div>

          <div class="action-btn q-mt-lg" v-if="currentUser.value.student">
            <q-btn v-if="!currentUser.value.student.contentUrlCode || !currentUser.value.student.contentUrlCni"
              class="btn-secondary"
              label="Ajouter mes documents"
              push
              size="md"
              @click='addCodeAndCni(currentUser.value.id)'
            />
            <q-btn
              class="btn-primary q-ml-md"
              label="Modifier mes informations"
              push
              size="md"
              @click='editer(currentUser.value.id)'
            />
          </div>
        </q-card-section>
      </q-card>
    </div>

    <div v-if="currentUser.value.student">
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
                <td>{{ bookings.drivingSchoolId.name }}</td>
                <td>{{ bookings.drivingSchoolId.phoneNumber }}</td>
                <td>{{ bookings.drivingSchoolId.city }}</td>
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
    </div>
  </div>
</template>

<style>
.text-h4 {
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 1.5rem;
  font-weight: 500;
  line-height: 2rem;
  letter-spacing: 0.0125em;
  text-transform: uppercase;
  color: whitesmoke;
}

.text-counter {
font-weight: 700;
font-size: 128px;
line-height: 150px;
color: #E76F51;
-webkit-text-stroke: 2px whitesmoke
}

.text-h6 {
  color: #E76F51;

}

.btn-primary {
  background-color:  #9999C3;
  color: whitesmoke;
}

.btn-secondary {
  background-color: #4ECB71;
  color: whitesmoke;
}


</style>

<route lang="yaml">
meta:
  layout: student
  requiresAuth: true
  roles: user
</route>
