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
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/driving_schools`, {
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
    }
  })
    .then((response) => response.json())
    .then((data) => {
      drivingSchool.value = data["hydra:member"];

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
  router.push('/student/driving_school/bookings/' + id)
}


</script>

<template>
  <q-page>
    <div class="container">
      <div class="row">
        
        <q-input class="col-6" v-model="filterVille.value" label="Ville" outlined />

        <q-btn label="Rechercher" @click="getDrivingSchool()" class="btn-primary researcb-btn" />
      </div>
    <div class="row">
      <q-card v-for="item in filteredDrivingSchool.value" :key="item.id" class="my-card col-12 mb-5 bg-secondary text-white">
        <q-card-section>
          <div class="text-h4">{{ item.name }}</div>
          <hr/>
          <div class="text-left">
            <div class="text-h6">
              <q-icon name="location_on" class="color-icon"></q-icon>
              {{ item.address }}, {{ item.city }} {{ item.zipcode }}</div>
            <div class="text-h6">
              <q-icon name="phone" class="color-icon"></q-icon>
              {{ item.phoneNumber }}
            </div>
          </div>
          <div class="text-center">
            <q-btn label="Disponibilités" @click="showAvailability(item.id)" class="btn-primary"></q-btn>
          </div>
        </q-card-section>
      </q-card>
    </div>
    </div>
  </q-page>
</template>

<style scoped>
.container {
  margin-top: 20px;
}

.row {
  justify-content: center;
  align-items: center;
}

.my-card {
  max-width: 400px;
  margin: 20px 8px 0 8px;
}

.my-card:last-child {
  margin-bottom: 20px;
}

.text-h4 {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 10px;
}

.text-h6 {
  font-size: 16px;
  margin-top: 10px;
}

.color-icon {
  color: #E76F51;
}

.text-h10 {
  font-size: 14px;
  margin-top: 5px;
}

.text-center {
  text-align: center;
}

.text-left {
  text-align: left;
}

.btn-primary {
  background-color:  #9999C3;
  color: whitesmoke;
}

.btn-secondary {
  background-color: #4ECB71;
  color: whitesmoke;
}

.researcb-btn {
  height: 40px;
  margin-left: 10px;
}
</style>

<!-- <template>
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
</template> -->

<route lang="yaml">
meta:
  layout: student
  requiresAuth: true
  roles: user
</route>
