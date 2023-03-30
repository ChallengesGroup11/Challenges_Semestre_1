<script setup lang="ts">
const router = useRouter()
import {onMounted, reactive} from 'vue';

const director = reactive({
  id: "",
  firstname: "",
  lastname: "",
  email: "",
  drivingSchoolId: "",
});

onMounted(async () => {
  const {params} = useRoute();
  const {id} = params;
  await fetchOneDirector(id);
});

const fetchOneDirector = async (id: string | string[]) => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/directors/`+id,
    {
      headers:{
        'Authorization': 'Bearer ' + localStorage.getItem('token'),
      }
    }
  )
    .then((response) => response.json())
    .then((data) => {
        console.log(data)
      director.id = data.userId.id;
      director.firstname = data.userId.firstname;
      director.lastname = data.userId.lastname;
      director.email = data.userId.email;
    //   director.drivingSchoolId.name = data.drivingSchoolId.name;
    })
    .catch((error) => {
      console.error('Error:', error);
    })
};

const onSubmit = async (id: string) => {
  const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/users/`+id, {
    method: 'PATCH',
    headers: {
      'Content-Type': 'application/merge-patch+json',
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
    },
    body: JSON.stringify(director),
  });
  const data = await response.json();
  await router.push('/admin/Director')
};
</script>

<template>
  <q-page class="bg-light-grey  items-center">
    <div class=" q-mt-sl  row justify-center">
      <div class="q-pa-md">
        <h2 class="text-h5 w-100 q-mb-xl">Editer un Directeur d'auto-école </h2>
        <q-form
          @submit="onSubmit(director.id)"
          class="q-gutter-md"
        >
          <q-input
            v-model="director.firstname"
            label="Firstname"
            filled
            lazy-rules
            :rules="[ val => val && val.length > 0 || 'Please type something']"
          />
          <q-input
            filled
            v-model="director.lastname"
            label="Lastname"
            hint="Address"
            lazy-rules
            :rules="[ val => val && val.length > 0 || 'Please type something']"
          />
          <q-input
            filled
            v-model="director.email"
            label="Email"
            hint="Zipcode"
            lazy-rules
            :rules="[ val => val && val.length > 0 || 'Please type something']"
          />
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

