<script setup lang="ts">

import {reactive} from "vue";
const router = useRouter();

const drivingSchool = reactive({value: []});


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
    })
    .catch((error) => {
      console.error("Error:", error);
    });
};

// const showAvailability = (item: any) => {
//   router.push('/student/driving_school/bookings/' + item.id)
// }


</script>


<template>
  <q-page>
    <div class="row">
      <q-card v-for="item in drivingSchool.value" :key="item.id" class="my-card q-ma-lg col">
        <q-card-section>
          <div class="text-h4"> {{ item.name }} </div>
          <div class="text-left">
            <div class="text-h10">{{ item.address }}, {{ item.city }} {{ item.zipcode }}</div>
            <div class="text-h6">Téléphone : {{ item.phoneNumber }} </div>
          </div>
          <!-- <div class="text-center">
            <q-btn label="Voir disponibilités" @click="showAvailability(item)" color="positive" icon="calendar"></q-btn>
          </div> -->
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
