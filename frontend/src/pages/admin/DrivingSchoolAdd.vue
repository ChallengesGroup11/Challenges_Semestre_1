<script setup lang='ts'>

import { ref, reactive } from 'vue'
const router = useRouter()

const director = reactive({value:[]})

const name = ref('')
const address = ref('')
const zipcode = ref('')
const city = ref('')
const phoneNumber = ref('')
const siret = ref('')
const kbis = ref('')


onMounted(async () => {
  await fetchDirector()
})
const onSubmit = async () => {
  const response = await fetch('https://localhost/driving_schools', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      name: name.value,
      address: address.value,
      zipcode: zipcode.value,
      city: city.value,
      phoneNumber: phoneNumber.value,
      siret: siret.value,
      kbis: kbis.value,
      director: director.value,
    }),
  })
  const data = await response.json()
  console.log('Success:', data)
  await router.push('/admin')
}

const fetchDirector = async () => {
  return fetch('https://localhost/users')
    .then((response) => response.json())
    .then((data) => {
      console.log(data)
      director.value = data["hydra:member"].filter(
        (user) => user.director !== null
      )
    })
    .catch((error) => {
      console.error('Error:', error)
    })
}
console.log(director.value)

</script>

<template>
  <q-page class="bg-light-grey  items-center">
    <div class="q-pa-md" style="max-width: 400px">
        <q-form
        @submit.prevent="onSubmit"
        class="q-gutter-md"
      >
        <q-input
          label="Nom de l'école de conduite"
          filled
          lazy-rules
          :rules="[val => val.length > 0 || 'Veuillez saisir un nom']"
         model-value=""/>
        <q-input
          label="Adresse"
          filled
          lazy-rules
          :rules="[val => val.length > 0 || 'Veuillez saisir une adresse']"
         model-value=""/>
        <q-input
          label="Code postal"
          filled
          lazy-rules
          :rules="[val => val.length > 0 || 'Veuillez saisir un code postal']"
         model-value=""/>
        <q-input
          label="Ville"
          filled
          lazy-rules
          :rules="[val => val.length > 0 || 'Veuillez saisir une ville']"
         model-value=""/>
        <q-input
          label="Numéro de téléphone"
          filled
          lazy-rules
          :rules="[val => val.length > 0 || 'Veuillez saisir un numéro de téléphone']"
         model-value=""/>
        <q-input
          label="Numéro de SIRET"
          filled
          lazy-rules
          :rules="[val => val.length > 0 || 'Veuillez saisir un numéro de SIRET']"
         model-value=""/>
        <q-input
          label="Numéro de KBIS"
          filled
          lazy-rules
          :rules="[val => val.length > 0 || 'Veuillez saisir un numéro de KBIS']"
         model-value=""/>
        <q-select
          filled
          label="Directeur"
          :options="director"
          :option-value="director"
          option-label="firstName"
          v-model="director"
        />
        <div>
          <q-btn label="Valider" type="submit" color="primary" />
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
