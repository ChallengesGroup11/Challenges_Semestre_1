<script setup lang="ts">
const router = useRouter()
import {onMounted, reactive} from 'vue';

const drivingSchool = reactive({
  id: "",
  name: "",
  address: "",
  zipcode: "",
  city: "",
  phoneNumber: "",
  siret: "",
  kbis: "",
});

onMounted(async () => {
  const {params} = useRoute();
  const {id} = params;
  await fetchOneDrivingSchool();
});

const fetchOneDrivingSchool = async () => {
  return fetch('https://localhost/driving_schools/6')
    .then((response) => response.json())
    .then((data) => {
      console.log(data)
      drivingSchool.id = data.id;
      drivingSchool.name = data.name;
      drivingSchool.address = data.address;
      drivingSchool.zipcode = data.zipcode;
      drivingSchool.city = data.city;
      drivingSchool.phoneNumber = data.phoneNumber;
      drivingSchool.siret = data.siret;
      drivingSchool.kbis = data.kbis;
    })
    .catch((error) => {
      console.error('Error:', error);
    })
};

const onSubmit = async () => {
  const response = await fetch('https://localhost/driving_schools/6', {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(drivingSchool),
  });
  const data = await response.json();
  await router.push('/admin')
  console.log('Success:', data);
};
// console.log(drivingSchool);
</script>


<template>
  <q-page class="bg-light-grey  items-center">
      <div class="q-pa-md" style="max-width: 400px">
        <q-form
          @submit="onSubmit"
          class="q-gutter-md"
        >
          <q-input
            filled
            v-model="drivingSchool.name"
            label="Your name *"
            hint="Name and surname"
            lazy-rules
            :rules="[ val => val && val.length > 0 || 'Please type something']"
          />
          <q-input
            filled
            v-model="drivingSchool.address"
            label="Your address *"
            hint="Address"
            lazy-rules
            :rules="[ val => val && val.length > 0 || 'Please type something']"
          />
          <q-input
            filled
            v-model="drivingSchool.zipcode"
            label="Your zipcode *"
            hint="Zipcode"
            lazy-rules
            :rules="[ val => val && val.length > 0 || 'Please type something']"
          />
          <q-input
            filled
            v-model="drivingSchool.city"
            label="Your city *"
            hint="City"
            lazy-rules
            :rules="[ val => val && val.length > 0 || 'Please type something']"
          />
          <q-input
            filled
            v-model="drivingSchool.phoneNumber"
            label="Your phone number *"
            hint="Phone number"
            lazy-rules
            :rules="[ val => val && val.length > 0 || 'Please type something']"
          />
          <q-input
            filled
            v-model="drivingSchool.siret"
            label="Your siret *"
            hint="Siret"
            lazy-rules
            :rules="[ val => val && val.length > 0 || 'Please type something']"
          />
          <div>
            <q-btn label="Submit" type="submit" color="primary"/>
          </div>
        </q-form>
      </div>
  </q-page>
</template>


<style>


</style>


<route lang="yaml">
meta:
  layout: admin
  requiresAuth: true
  roles: admin
</route>

