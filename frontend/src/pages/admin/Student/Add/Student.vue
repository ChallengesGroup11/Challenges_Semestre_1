<script setup lang='ts'>
import {ref} from 'vue'
import {onMounted, reactive} from 'vue';
const router = useRouter()
const users = reactive({value: []});
// const director = reactive({value:[]})
const userId = ref()
const nb_hour_done = ref()
const url_code_certification = ref()
const url_cni = ref()
onMounted(async () => {
  await fetchUsers()
})
const fetchUsers = async () => {
  return fetch('https://localhost:81/users')
    .then((response) => response.json())
    .then((data) => {
      users.value = data["hydra:member"];
      // filter users that are not students, directors or monitors
      users.value = users.value.filter((user) => {
        return typeof user.student === 'undefined' && typeof user.director === 'undefined' && typeof user.monitor === 'undefined' && user.roles[0] === 'ROLE_USER'
      })
      console.log(users.value);
    });
};
const onSubmit = async () => {
  const formData = new FormData();
  
  formData.append('userId', userId.value);
  formData.append('nb_hour_done', nb_hour_done.value);
  formData.append('url_code_certification', url_code_certification.value);
  formData.append('url_cni', url_cni.value);
  console.log(formData);
  const response = await fetch('https://localhost:81/students', {
    method: 'POST',
    body: formData
  })
  const data = await response.json()
  console.log(data)
  await router.push('/admin/Student')
}
// const fetchDirector = async () => {
//   return fetch('https://localhost/directors')
//     .then((response) => response.json())
//     .then((data) => {
//       console.log(data['hydra:member'].id)
//       director.value = data['hydra:member']
//     })
//     .catch((error) => {
//       console.error('Error:', error)
//     })
// }
// console.log(director.value)
</script>

<template>
  <q-page class="bg-light-grey  items-center">
    <div class=" q-mt-sl  row justify-center">
      <div class="q-pa-md">
        <h2 class="text-h5 w-100 q-mb-xl">Ajouter un élève</h2>
        <q-form
          @submit.prevent="onSubmit"
          class="q-gutter-md"
        >
          <q-select
            filled
            v-model="userId"
            :options="users.value.map(user => user.lastname + ' ' + user.firstname)"
            label="Utilisateur"
            lazy-rules
          />
          <q-input
            filled
            v-model="nb_hour_done"
            label="Nombre d'heure effectuées"
            lazy-rules
            :rules="[ val => val && val.length > 0 || 'Please type something']"
          />
          <q-input
            @update:model-value="val => { file = val[0] }"
            filled
            type="file"
            v-model="url_code_certification"
          />
          <q-input
            @update:model-value="val => { file = val[0] }"
            filled
            type="file"
            v-model="url_cni"
          />
          <div>
            <q-btn label="Valider" @submit="onSubmit" type="submit" color="primary"/>
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