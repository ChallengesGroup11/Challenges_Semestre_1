<script setup lang="ts">
import { reactive, defineComponent } from "vue"
import moment from "moment"
import { log } from "console"
import { Chart, PieController, ArcElement, Legend, Title } from "chart.js"
import { useQuasar } from "quasar"
import { done } from "nprogress"
import { empty } from "ramda"

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
const router = useRouter()

const currentUser = reactive({ value: [] })

const statisticsChartUserDone = reactive({ value: [] })
const statisticsChartUserValidate = reactive({ value: [] })
const ShowStat = reactive({ value: false })
onMounted(async () => {
  if (localStorage.getItem("token") == null) {
    await router.push("/auth")
  } else {
    await getUser()
    await getUserBookingDone()
    await getUserBookingValidate()
    createChart()
  }
})

const getUser = async () => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/me`, {
    headers: {
      Authorization: "Bearer " + localStorage.getItem("token"),
    },
  })
    .then((response) => response.json())
    .then((data) => {
      currentUser.value = data
    })
    .catch((error) => {
      console.error("Error:", error)
    })
}

const getUserBookingDone = async () => {
  return fetch(
    `${import.meta.env.VITE_CHALLENGE_URL}/users_booking_done/` + currentUser.value?.director?.drivingSchoolId?.id,
    {
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
      },
    },
  )
    .then((response) => response.json())
    .then((data) => {
      if (data.length != 0 && !empty(data)) {
        ShowStat.value = true
      }
      statisticsChartUserDone.value = data
      console.log(statisticsChartUserDone.value)
    })
    .catch((error) => {
      statisticsChartUserDone.value = []
      console.error("Error:", error)
    })
}

const getUserBookingValidate = async () => {
  return fetch(
    `${import.meta.env.VITE_CHALLENGE_URL}/users_booking_validate/` + currentUser.value?.director?.drivingSchoolId?.id,
    {
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
      },
    },
  )
    .then((response) => response.json())
    .then((data) => {
      console.log(data)
      if (data.length != 0 && !empty(data)) {
        ShowStat.value = true
      }
      statisticsChartUserValidate.value = data
      console.log(statisticsChartUserValidate.value)
    })
    .catch((error) => {
      statisticsChartUserValidate.value = []
      console.error("Error:", error)
    })
}

const createChart = () => {
  Chart.register(PieController, Title, ArcElement, Legend)
  if (ShowStat.value == false) {
    return
  }
  new Chart(document.getElementById("statisticsChart"), {
    type: "pie",
    data: {
      datasets: [
        {
          label: "Nombre d'utilisateurs",
          data: [
            statisticsChartUserDone.value.map((item: any) => item.done),
            statisticsChartUserValidate.value.map((item: any) => item.validate),
          ],
          backgroundColor: ["rgb(255, 99, 132)", "rgb(54, 162, 235)"],
          hoverOffset: 4,
        },
      ],
      labels: ["Done", "Validate"],
      options: {
        plugins: {
          legend: {
            display: true,
          },
        },
      },
    },
  })
}
const editer = (id: string) => {
  router.push("/director/edit/" + id)
}

const addKbisAndSiret = (id: string) => {
  router.push("/director/add/createdrivingschool/" + id)
}

const editAutoEcole = (id: string) => {
  router.push("/director/edit/drivingschool/" + id)
}
</script>

<template>
  <div>
    <div class="q-pa-md">
      <div class="row">
        <q-card class="my-card q-ma-lg col bg-primary">
          <q-card-section class="q-pa-md col">
            <div color="white" class="text-h4">
              {{ currentUser.value?.firstname }} {{ currentUser.value?.lastname }}
              <q-icon v-if="currentUser.value?.director?.drivingSchoolId" name="o_check_circle" color="green" />
              <q-icon v-else name="o_cancel" color="red" />
            </div>
            <div class="container-index">
              <div v-if="currentUser.value?.email" class="container-infos">
                <q-icon name="email" class="color-icon" size="2em" />
                <span>
                  <p class="text-infos">{{ currentUser.value?.email }}</p>
                </span>
              </div>

              <div class="text-center q-mt-lg" v-if="!currentUser.value?.director?.drivingSchoolId">
                <q-btn
                  label="Ajouter votre auto-ecole"
                  push
                  size="md"
                  @click="addKbisAndSiret(currentUser.value.id)"
                  color="secondary"
                  icon="add"
                />
              </div>

              <div v-if="currentUser.value.director?.drivingSchoolId" class="container-infos">
                <q-icon name="directions_car" class="color-icon" size="2em" />
                <span v-if="currentUser.value.director?.drivingSchoolId?.name">
                  <p class="text-infos">{{ currentUser.value.director.drivingSchoolId.name }}</p>
                </span>
              </div>

              <div v-if="currentUser.value.director?.drivingSchoolId" class="container-infos">
                <q-icon name="location_on" class="color-icon" size="2em" />
                <span
                  v-if="
                    currentUser.value.director?.drivingSchoolId?.address &&
                    currentUser.value.director?.drivingSchoolId?.zipcode &&
                    currentUser.value.director?.drivingSchoolId?.city
                  "
                >
                  <p class="text-infos">
                    {{ currentUser.value.director.drivingSchoolId.address }},
                    {{ currentUser.value.director.drivingSchoolId.zipcode }}
                    {{ currentUser.value.director.drivingSchoolId.city }}
                  </p>
                </span>
              </div>

              <div v-if="currentUser.value.director?.drivingSchoolId" class="container-infos">
                <q-icon name="phone" class="color-icon" size="2em" />
                <span v-if="currentUser.value.director?.drivingSchoolId?.phoneNumber">
                  <p class="text-infos">{{ currentUser.value.director.drivingSchoolId.phoneNumber }}</p>
                </span>
              </div>

              <div v-if="currentUser.value.director?.drivingSchoolId" class="container-infos">
                <p class="text-infos">N° SIRET</p>
                <span v-if="currentUser.value.director?.drivingSchoolId?.siret">
                  <q-icon style="vertical-align: text-top" size="sm" color="positive" name="check" />
                </span>
              </div>
              <div v-if="currentUser.value.director?.drivingSchoolId" class="container-infos">
                <p class="text-infos">N° Kbis</p>
                <span v-if="currentUser.value.director?.drivingSchoolId?.contentUrl">
                  <q-icon style="vertical-align: text-top" size="sm" color="positive" name="check" />
                </span>
              </div>
              <q-btn
                label="Modifier l'auto école"
                push
                size="md"
                @click="editAutoEcole(currentUser.value.director?.drivingSchoolId.id)"
                class="btn-primary"
              />
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </div>
  <div v-if="ShowStat.value" class="q-pa-md mx-auto w-[500px]">
    <div>
      <q-card class="my-card q-ma-lg col-4">
        <h2>Nombre d'heure validé / terminé</h2>
        <canvas id="statisticsChart"></canvas>
      </q-card>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.container-index {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
.container-infos {
  display: flex;
  align-items: center;
  margin-top: 10px;
}

.color-icon {
  font-size: 8px;
  margin-right: 8px;
  color: #e76f51;
}

.text-infos {
  font-size: 24px;
  margin: 0;
  font-weight: bold;
  color: whitesmoke;
}

.btn-primary {
  margin-top: 8px;
  background-color: #9999c3;
  color: whitesmoke;
}
</style>

<route lang="yaml">
meta:
  layout: director
  requiresAuth: true
  roles: director
</route>
