<script setup lang='ts'>

import { ref } from 'vue'
import { useQuasar } from "quasar"

const $q = useQuasar()
const viewNotif = (icon: any, color: string, message: string, textColor: string, position: any) => {
  $q.notify({
    icon,
    color,
    message,
    textColor,
    position,
  })
}
const router = useRouter()

const directors = reactive({ value: [] })
const directorSelected = ref('')

const name = ref('')
const address = ref('')
const zipcode = ref('')
const city = ref('')
const phoneNumber = ref('')
const siret = ref('')
const filePath = ref()

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
      for (let i = directors.value.length - 1; i >= 0; i--) {
        const element = directors.value[i];
        if (element.drivingSchoolId) {
          directors.value.splice(i, 1);
        }
      }
      console.log(directors.value)
    })
}


onMounted(async () => {
  await fetchDirector()
})
const onSubmit = async () => {
  console.log(directorSelected.value)
  const formData = new FormData();
  formData.append('file', filePath.value[0]);
  formData.append('name', name.value);
  formData.append('address', address.value);
  formData.append('zipcode', zipcode.value);
  formData.append('city', city.value);
  formData.append('phoneNumber', phoneNumber.value);
  formData.append('siret', siret.value);
  if (directorSelected.value != "") {
    formData.append('director', "directors/" + directorSelected.value);
  }

  const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/driving_schools`, {
    method: 'POST',
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
    },
    body: formData
  })
  if (response.status === 400) {
    viewNotif("thumb_down", "red", "Une erreur est survenue", "white", "top-right")
  }
  if (response.status === 500) {
    viewNotif("thumb_down", "red", "Une erreur est survenue", "white", "top-right")
  }
  if (response.status === 201) {
    viewNotif("thumb_up", "green", "L'auto école à bien été ajouté", "white", "top-right")
    await router.push('/admin/drivingSchool')
  }
}

</script>

<template>
  <q-page class="bg-light-grey  items-center">
    <div class=" q-mt-sl  row justify-center">
      <div class="q-pa-md">
        <h2 class="text-h5 w-100 q-mb-xl">Ajouter une auto école</h2>
        <q-form @submit.prevent="onSubmit" class="q-gutter-md">
          <q-input label="Nom de l'école de conduite" filled lazy-rules
            :rules="[val => val.length > 0 || 'Veuillez saisir un nom']" v-model="name" />
          <q-input label="Adresse" filled lazy-rules :rules="[val => val.length > 0 || 'Veuillez saisir une adresse']"
            v-model="address" />
          <q-input label="Code postal" filled lazy-rules
            :rules="[val => val.length > 0 || 'Veuillez saisir un code postal']" v-model="zipcode" />
          <q-input label="Ville" filled lazy-rules :rules="[val => val.length > 0 || 'Veuillez saisir une ville']"
            v-model="city" />
          <q-input label="Numéro de téléphone" filled lazy-rules
            :rules="[val => val.length > 0 || 'Veuillez saisir un numéro de téléphone']" v-model="phoneNumber" />
          <q-input label="Numéro de SIRET" filled lazy-rules
            :rules="[val => val.length > 0 || 'Veuillez saisir un numéro de SIRET']" v-model="siret" />
          <q-input @update:model-value="val => { file = val[0] }" filled type="file" v-model="filePath" />

          <select v-model="directorSelected" placeholder="Choisir un directeur">
            <option value="" selected>Choisir un directeur</option>
            <option v-for="director in directors.value" :value="director.id">
              {{ director.userId.firstname }}
            </option>
          </select>




          <div>
            <q-btn label="Valider" type="submit" color="primary" />
          </div>
        </q-form>
      </div>
    </div>
  </q-page>
</template>

<style></style>
<route lang="yaml">
meta:
  layout: admin
  requiresAuth: true
  roles: admin
</route>
