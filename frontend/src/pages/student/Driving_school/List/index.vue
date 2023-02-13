<script setup lang="ts">

import {reactive} from "vue";

const router = useRouter();

const drivingSchool = reactive({value: []});
const filterVille = reactive({value: ""});
const filteredDrivingSchool = reactive({value: []});


onMounted(
  async () => {
    if (localStorage.getItem('token') == null) {
      await router.push('/auth')
    } else {
      await getDrivingSchool()
    }
  }
)

const getDrivingSchool = async () => {
  return fetch("https://localhost/driving_schools", {
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
    }
  })
    .then((response) => response.json())
    .then((data) => {
      drivingSchool.value = data["hydra:member"];
      console.log(drivingSchool.value);

      if (filterVille.value != "") {
        filteredDrivingSchool.value = drivingSchool.value.filter((item: any) => {
          return item.city.toLowerCase().includes(filterVille.value.toLowerCase());
        });
      } else {
        filteredDrivingSchool.value = drivingSchool.value;
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
};

const showAvailability = (id: any) => {
  console.log(id)
  router.push('/student/driving_school/bookings/' + id)
}


</script>


<template>
  <q-page>
    <div class="container">
      <div class="row">
        <div class="col-12">
          <h1 class="text-center">Liste des auto-écoles</h1>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <q-input class="col-6" v-model="filterVille.value" label="Ville" outlined />
        <q-btn label="Rechercher" @click="getDrivingSchool()" color="positive" icon="search"></q-btn>
      </div>
    </div>
    <div class="row">
      <q-card v-for="item in filteredDrivingSchool.value" :key="item.id" class="my-card q-ma-lg col">
        <q-card-section>
          <div class="text-h4"> {{ item.name }} </div>
          <div class="text-left">
            <div class="text-h10">{{ item.address }}, {{ item.city }} {{ item.zipcode }}</div>
            <div class="text-h6">Téléphone : {{ item.phoneNumber }} </div>
          </div>
          <div class="text-center">
            <q-btn label="Voir disponibilités" @click="showAvailability(item.id)" color="positive" icon="calendar_month"></q-btn>
          </div>
        </q-card-section>

      </q-card>
    </div>
  </q-page>
</template>

<route lang="yaml">
meta:
  layout: student
  requiresAuth: true
  roles: user
</route>
