<script setup lang="ts">
const router = useRouter()
import {onMounted, reactive} from 'vue';


const directors = reactive({value:[]})

const drivingSchool = reactive({
  id: "",
  name: "",
  address: "",
  zipcode: "",
  city: "",
  phoneNumber: "",
  siret: "",
  kbis: "",
  director: ""
});

onMounted(async () => {
  const {params} = useRoute();
  const {id} = params;
  await fetchOneDrivingSchool(id);
  await fetchDirector()
});

const fetchDirector = async () => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/directors`, {
    headers: {
      accept: 'application/ld+json',
      Authorization: 'Bearer ' + localStorage.getItem('token'),
    },
  })
    .then((response) => response.json())
    .then((data) => {
      directors.value = data['hydra:member']
      
      console.log(directors.value)
    })
}

const fetchOneDrivingSchool = async (id: string | string[]) => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/driving_schools/`+id,
    {
      headers:{
        'Authorization': 'Bearer ' + localStorage.getItem('token'),
      }
    }
  )
    .then((response) => response.json())
    .then((data) => {
      drivingSchool.id = data.id;
      drivingSchool.name = data.name;
      drivingSchool.address = data.address;
      drivingSchool.zipcode = data.zipcode;
      drivingSchool.city = data.city;
      drivingSchool.phoneNumber = data.phoneNumber;
      drivingSchool.siret = data.siret;
      drivingSchool.kbis = data.kbis;
      drivingSchool.director = data.director.filter((item) => item.drivingSchoolId === null);
      console.log(drivingSchool.director)
     })
    .catch((error) => {
      console.error('Error:', error);
    })
};

const onSubmit = async (id: string) => {
  const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/driving_schools/`+id, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
    },
    body: JSON.stringify(drivingSchool),
  });
  const data = await response.json();
  await router.push('/admin/drivingSchool')
};
</script>


<template>
  <q-page class="bg-light-grey  items-center">
    <div class=" q-mt-sl  row justify-center">
      <div class="q-pa-md">
        <h2 class="text-h5 w-100 q-mb-xl">Editer une auto-école </h2>
        <q-form
          @submit="onSubmit(drivingSchool.id)"
          class="q-gutter-md"
        >
          <q-input
            v-model="drivingSchool.name"
            label="Nom de l'école de conduite"
            filled
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
          <select v-model="drivingSchool.director" placeholder="Choisir un directeur">
              <option selected v-if="drivingSchool?.director" :value="drivingSchool.director.id">{{   drivingSchool.director.userId.firstname }}</option>
              <option v-else value="">Choisir un directeur</option>
              <option v-for="director in directors.value" :value="directors.id">
                {{ director.userId.firstname }}
              </option>
            </select>
          <div>
            <q-btn label="Submit" type="submit" color="primary"/>
          </div>
        </q-form>
      </div>
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

