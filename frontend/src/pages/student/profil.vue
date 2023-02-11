<script setup lang="ts">
import {reactive, defineComponent} from "vue";
import moment from 'moment';

const currentUser = reactive({value: []});

onMounted(async () => {

  await getUser()
});


const date = (date: moment.MomentInput) => {
  return moment(date).format(" DD/MM/YYYY ");
}


const getUser = async () => {
  return fetch("https://localhost/me", {
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
    }
  })
    .then((response) => response.json())
    .then((data) => {
      console.log(data);
      currentUser.value = data;
    })
    .catch((error) => {
      console.error("Error:", error);
    });
};


</script>


<template>
  <div class="q-pa-md  ">
    <div class=" row ">
    <q-card class="my-card q-ma-lg col" >
      <q-card-section>
        <div class="text-h5">Mon profil</div>
        <div class="text-left">
          <div class="text-h6">Nom : {{ currentUser.value.firstname }}</div>
          <div class="text-h6">Prénom : {{ currentUser.value.lastname }}</div>
          <div class="text-h6">Email : {{ currentUser.value.email }}</div>
          <div v-if="currentUser.value.student" class="text-h6">Nombre d'heure de conduite effectuée : {{
              currentUser.value.student.nbHourDone
            }}
          </div>
          <div v-if="currentUser.value.student" class="text-h6">Url du code de la route : {{
              currentUser.value.student.urlCodeCertification
            }}
          </div>
          <div v-if="currentUser.value.student" class="text-h6">Url de la CNI : {{ currentUser.value.student.urlCni }}
          </div>
        </div>
        <!--        <div class="text-h6">Réservation : {{ currentUser.value.student.bookings }}</div>-->
      </q-card-section>
    </q-card>
      <q-card class="my-card q-ma-lg  col " >
        <q-card-section>
          <div class="text-h5">Mon crédit</div>
            <div class="vertical text-h1"> {{ currentUser.value.student.countCredit }}</div>

          <!--        <div class="text-h6">Réservation : {{ currentUser.value.student.bookings }}</div>-->
        </q-card-section>
      </q-card>
    </div>
    <q-card class="my-card q-ma-lg">
      <q-card-section>
        <div class="text-h5">Mes Réservations</div>

        <q-markup-table class="q-ma-md  ">

          <thead>
          <tr>
            <th>Heure de début</th>
            <th>Heure de fin</th>
            <th>Nom de l'école</th>
            <th>Numéro de téléphone</th>
            <th>Adresse</th>
            <th> Valider ?</th>
            <th> Heure terminé</th>
            <th> Action</th>
          </tr>
          </thead>
          <tbody v-if="currentUser.value.student && currentUser.value.student.bookings">
          <tr v-for="(bookings, index ) in currentUser.value.student.bookings" :key="index">
            <td>{{ date(bookings.slotBegin) }}</td>
            <td>{{ date(bookings.slotEnd) }}</td>
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

        <!--        <div class="text-h6">Réservation : {{ currentUser.value.student.bookings }}</div>-->
      </q-card-section>


      <!--        DATA     -->
      <!--        <div class=" text-h6">{{ currentUser.value.firstname }} - {{currentUser.value.lastname}}</div>-->
      <!--        <div class="text-h6">{{ currentUser.value.email }}</div>-->
      <!--        <div v-if="currentUser.value.student">-->
      <!--        <div class="text-h6">{{ currentUser.value.student.nbHourDone}}</div>-->
      <!--        <div class="text-h6">{{ currentUser.value.student.urlCodeCertification }}</div>-->
      <!--        <div class="text-h6">{{ currentUser.value.student.urlCni }}</div>-->
      <!--          <div v-if="currentUser.value.student.bookings">-->
      <!--            <div v-for="(bookings, index ) in currentUser.value.student.bookings" :key="index">-->
      <!--              <div class="text-h6">{{ bookings.slotBegin }}</div>-->
      <!--              <div class="text-h6">{{ bookings.slotEnd }}</div>-->
      <!--              <div v-if="bookings.drivingSchoolId" class="text-h6">{{ bookings.drivingSchoolId[0].name }}</div>-->
      <!--              <div v-if="bookings.drivingSchoolId" class="text-h6">{{ bookings.drivingSchoolId[0].phoneNumber }}</div>-->
      <!--            </div>-->
      <!--          </div>-->
      <!--        </div>-->
    </q-card>
  </div>
</template>


<route lang="yaml">
meta:
  layout: student
  requiresAuth: true
  roles: student
</route>
