<script setup lang="ts">
import {reactive, defineComponent} from "vue";
import moment from 'moment';

const router = useRouter()

const currentUser = reactive({value: []});

onMounted(async () => {
  if (localStorage.getItem('token') == null) {
    await router.push('/auth')
  } else {
    await getUser()
  }
});


const dateJour = (date: moment.MomentInput) => {
  return moment(date).format(" DD/MM/YYYY ");
}
const dateHeure = (date: moment.MomentInput) => {
  return moment(date).format("hh:ss ");
}

const getUser = async () => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/me`, {
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
    }
  })
    .then((response) => response.json())
    .then((data) => {
      currentUser.value = data;
    })
    .catch((error) => {
      console.error("Error:", error);
    });
};

const editer = (id: string) => {
 router.push('/student/edit/' + id)
}

const addCodeAndCni = (id: string) => {
  router.push('/student/add/codecertification/' + id)
}

</script>


<template>
  <div class="q-pa-md  ">
    <div class=" row ">
      <q-card class="my-card q-ma-lg col">
        <q-card-section>
          <div class="text-h4"> {{ currentUser.value.firstname }} {{ currentUser.value.lastname }} <q-btn size="sm" round color="warning" @click="editer(currentUser.value.id)" icon="edit"></q-btn></div>
          <div class="text-left">
            <div class="text-h6">Mon email : {{ currentUser.value.email }}</div>
            <div v-if="!currentUser.value.student" class="text-center q-mt-lg">
              <q-btn label="Ajouter votre Code et CNI" push size="md" @click='addCodeAndCni(currentUser.value.id)' color="positive" icon="add"/>
            </div>
            <div v-if="currentUser.value.student" class="text-h6">
              Code de la route
              <span v-if="currentUser.value.student.contentUrlCode">
              <q-icon style="vertical-align: text-top" size="sm" color="positive" name="check"/>
            </span>
            </div>
            <div v-if="currentUser.value.student" class="text-h6">
              CNI
              <span v-if="currentUser.value.student.contentUrlCni">
              <q-icon style="vertical-align: text-top" size="sm" color="positive" name="check"/>
            </span>
            </div>
          </div>
        </q-card-section>
      </q-card>

      <q-card class="my-card q-ma-lg bg-secondary col">
        <q-card-section>
          <div class="text-h4">Mon cr??dit</div>
          <div v-if="currentUser.value.student" class="vertical text-h1">
            <span v-if="currentUser.value.student.countCredit !== null">
              {{ currentUser.value.student.countCredit }}
            </span>
            <span v-else class="vertical text-h1"> 0</span>
          </div>
          <div v-else class="vertical text-h1"> 0</div>
        </q-card-section>
      </q-card>

      <q-card class="my-card q-ma-lg  col ">
        <q-card-section class="vertical-middle">
          <div class="text-h4">Nombre d'heure effectu??(e)</div>
          <div v-if="currentUser.value.student" class="vertical text-h1">
            <span v-if="currentUser.value.student.nbHourDone !== null">
              {{ currentUser.value.student.nbHourDone }}
            </span>
            <span v-else class="vertical text-h1"> 0</span>
          </div>

        </q-card-section>
      </q-card>
    </div>

    <div v-if="currentUser.value.student && currentUser.value.student.bookings">
    <q-card class="my-card q-ma-lg">
      <q-card-section>
        <div class="text-h5">Mes R??servations</div>
        <q-markup-table class="q-ma-md  ">

          <thead>
          <tr>
            <th> Jour de la session</th>
            <th>Heure de d??but</th>
            <th>Heure de fin</th>
            <th>Nom de l'??cole</th>
            <th>Num??ro de t??l??phone</th>
            <th>Adresse</th>
            <th> Valider ?</th>
            <th> Heure termin??</th>
            <th> Action</th>
          </tr>
          </thead>
          <tbody v-if="currentUser.value.student && currentUser.value.student.bookings">
          <tr v-for="(bookings, index ) in currentUser.value.student.bookings" :key="index">
            <td v-if="!bookings" colspan="9">Pas de r??servation de session effectu??(e)</td>
            <td>{{ dateJour(bookings.slotBegin) }}</td>
            <td>{{ dateHeure(bookings.slotBegin) }}</td>
            <td>{{ dateHeure(bookings.slotEnd) }}</td>
            <td> {{ bookings.drivingSchoolId[0].name }}</td>
            <td>{{ bookings.drivingSchoolId[0].phoneNumber }}</td>
            <td>{{ bookings.drivingSchoolId[0].city }}</td>
            <td v-if="bookings.statusValidate === true">
              <q-icon name="mood" color="positive" size="20px"/>
            </td>
            <td v-else>
              <q-icon name="mood_bad" color="negative" size="20px"/>
            </td>
            <td v-if="bookings.statusDone === true">
              <q-icon name="mood" color="positive" size="20px"/>
            </td>
            <td v-else>
              <q-icon name="mood_bad" color="negative" size="20px"/>
            </td>
            <td v-if="bookings.statusDone === true">
              <q-btn disabled color="secondary" label="Annuler"/>
            </td>
            <td v-else>
              <q-btn color="primary" label="Annuler"/>
            </td>
          </tr>
          </tbody>

        </q-markup-table>
      </q-card-section>
    </q-card>
    </div>
  </div>
</template>


<route lang="yaml">
meta:
  layout: student
  requiresAuth: true
  roles: user
</route>
