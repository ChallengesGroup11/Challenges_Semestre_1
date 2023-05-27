<script setup lang="ts">
import { reactive, defineComponent } from "vue";
import moment from 'moment';
import { log } from "console";
import { Chart, PieController, ArcElement, Legend, Title } from 'chart.js';
const router = useRouter()

const currentUser = reactive({ value: [] })

const statisticsChartUserDone = reactive({ value: [] });
const statisticsChartUserValidate = reactive({ value: [] });
onMounted(async () => {
  if (localStorage.getItem('token') == null) {
    await router.push('/auth')
  } else {
    await getUser()
    await getUserBookingDone();
    await getUserBookingValidate();
    await createChart();
  }

})


const getUser = async () => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/me`, {
    headers: {
      Authorization: 'Bearer ' + localStorage.getItem('token'),
    },
  })
    .then((response) => response.json())
    .then((data) => {
      currentUser.value = data;
    })
    .catch((error) => {
      console.error('Error:', error)
    })
}

const getUserBookingDone = async () => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/users_booking_done/` + currentUser.value?.director?.drivingSchoolId?.id,
    {
      headers: {
        Authorization: 'Bearer ' + localStorage.getItem('token'),
      },
    })
    .then((response) => response.json())
    .then((data) => {
      statisticsChartUserDone.value = data;
      console.log(statisticsChartUserDone.value);
    })
    .catch((error) => {
      console.error('Error:', error)
    })
}

const getUserBookingValidate = async () => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/users_booking_validate/` + currentUser.value?.director?.drivingSchoolId?.id,
    {
      headers: {
        Authorization: 'Bearer ' + localStorage.getItem('token'),
      },
    })
    .then((response) => response.json())
    .then((data) => {
      statisticsChartUserValidate.value = data;
      console.log(statisticsChartUserValidate.value);
    })
    .catch((error) => {
      console.error('Error:', error)
    })
}

const createChart = () => {
  Chart.register(PieController, Title, ArcElement, Legend);
  new Chart(document.getElementById('statisticsChart'), {
    type: 'pie',
    data: {
      datasets: [{
        label: 'Nombre d\'utilisateurs',
        data: [statisticsChartUserDone.value.map((item: any) => item.done), statisticsChartUserValidate.value.map((item: any) => item.validate)],
        backgroundColor: [
          'rgb(255, 99, 132)',
          'rgb(54, 162, 235)',
        ],
        hoverOffset: 4
      }],
      labels: ['Done', 'Validate'],
      options: {
        plugins: {
          legend: {
            display: true,
          }
        }
      }
    },
  });
}
const editer = (id: string) => {
  router.push('/director/edit/' + id)
}

const addKbisAndSiret = (id: string) => {
  router.push('/director/add/createdrivingschool/' + id)
}

const editAutoEcole = (id: string) => {
  router.push('/director/edit/drivingschool/' + id)
}
</script>

<template>
  <div>
    <h1>Directeur</h1>
    <div class="q-pa-md  ">
      <div class=" row ">
        <q-card class="my-card q-ma-lg col">
          <q-card-section>
            <div class="text-h4"> {{ currentUser.value?.firstname }} {{ currentUser.value?.lastname }} <q-btn size="sm"
                round color="warning" @click="editer(currentUser.value?.director.id)" icon="edit"></q-btn></div>
            <div class="text-left">
              <div class="text-h6">Mon email : {{ currentUser.value?.email }}</div>
              <div class="text-center q-mt-lg" v-if="!currentUser.value?.director?.drivingSchoolId">
                <q-btn label="Ajouter votre auto-ecole" push size="md" @click='addKbisAndSiret(currentUser.value.id)'
                  color="positive" icon="add" />
              </div>

              <div v-if="currentUser.value.director?.drivingSchoolId" class="text-h6">
                Nom de l'auto-ecole :
                <span v-if="currentUser.value.director?.drivingSchoolId?.name">
                  <p>{{ currentUser.value.director.drivingSchoolId.name }}</p>
                </span>
              </div>

              <div v-if="currentUser.value.director?.drivingSchoolId" class="text-h6">
                Adresse de l'auto-ecole :
                <span v-if="currentUser.value.director?.drivingSchoolId?.address">
                  <p>{{ currentUser.value.director.drivingSchoolId.address }}</p>
                </span>
              </div>

              <div v-if="currentUser.value.director?.drivingSchoolId" class="text-h6">
                Ville de l'auto-ecole :
                <span v-if="currentUser.value.director?.drivingSchoolId?.city">
                  <p>{{ currentUser.value.director.drivingSchoolId.city }}</p>
                </span>
              </div>

              <div v-if="currentUser.value.director?.drivingSchoolId" class="text-h6">
                Téléphone de l'auto-ecole :
                <span v-if="currentUser.value.director?.drivingSchoolId?.phoneNumber">
                  <p>{{ currentUser.value.director.drivingSchoolId.phoneNumber }}</p>
                </span>
              </div>

              <div v-if="currentUser.value.director?.drivingSchoolId" class="text-h6">
                N° SIRET
                <span v-if="currentUser.value.director?.drivingSchoolId?.siret">
                  <q-icon style="vertical-align: text-top" size="sm" color="positive" name="check" />
                </span>
              </div>
              <div v-if="currentUser.value.director?.drivingSchoolId" class="text-h6">
                N° Kbis
                <span v-if="currentUser.value.director?.drivingSchoolId?.contentUrl">
                  <q-icon style="vertical-align: text-top" size="sm" color="positive" name="check" />
                </span>
              </div>
              <q-btn label="Modifier l'auto école" push size="md"
                @click='editAutoEcole(currentUser.value.director?.drivingSchoolId.id)' color="positive" icon="add" />
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </div>
  <div class="q-pa-md  ">
      <div class=" row ">
      <q-card class="my-card q-ma-lg col-4">
        <h2>Nombre d'heure validé / terminé </h2>
        <canvas id="statisticsChart"></canvas>
      </q-card>

    </div>
  </div>
</template>

<route lang="yaml">
meta:
  layout: director
  requiresAuth: true
  roles: director
</route>
