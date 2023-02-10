<script setup lang='ts'>

import {ref} from 'vue'

const router = useRouter()

// const director = reactive({value:[]})

const name = ref('')
const address = ref('')
const zipcode = ref('')
const city = ref('')
const phoneNumber = ref('')
const siret = ref('')
const filePath = ref()


onMounted(async () => {
  // await fetchDirector()
})
const onSubmit = async () => {
  const formData = new FormData();
  formData.append('file', filePath.value[0]);
  formData.append('name', name.value);
  formData.append('address', address.value);
  formData.append('zipcode', zipcode.value);
  formData.append('city', city.value);
  formData.append('phoneNumber', phoneNumber.value);
  formData.append('siret', siret.value);

  const response = await fetch('https://localhost/driving_schools', {
    method: 'POST',
    body: formData
  })
  const data = await response.json()
  console.log('Success:', data)
  await router.push('/admin/drivingSchool')
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
        <h2 class="text-h5 w-100 q-mb-xl">Ajouter une auto école</h2>
        <q-form
          @submit.prevent="onSubmit"
          class="q-gutter-md"
        >
          <q-input
            label="Nom de l'école de conduite"
            filled
            lazy-rules
            :rules="[val => val.length > 0 || 'Veuillez saisir un nom']"
            v-model="name"
          />
          <q-input
            label="Adresse"
            filled
            lazy-rules
            :rules="[val => val.length > 0 || 'Veuillez saisir une adresse']"
            v-model="address"
          />
          <q-input
            label="Code postal"
            filled
            lazy-rules
            :rules="[val => val.length > 0 || 'Veuillez saisir un code postal']"
            v-model="zipcode"
          />
          <q-input
            label="Ville"
            filled
            lazy-rules
            :rules="[val => val.length > 0 || 'Veuillez saisir une ville']"
            v-model="city"
          />
          <q-input
            label="Numéro de téléphone"
            filled
            lazy-rules
            :rules="[val => val.length > 0 || 'Veuillez saisir un numéro de téléphone']"
            v-model="phoneNumber"
          />
          <q-input
            label="Numéro de SIRET"
            filled
            lazy-rules
            :rules="[val => val.length > 0 || 'Veuillez saisir un numéro de SIRET']"
            v-model="siret"
          />
          <q-input
            @update:model-value="val => { file = val[0] }"
            filled
            type="file"
            v-model="filePath"
          />

          <!--        <q-select-->
          <!--          filled-->
          <!--          label="Directeur"-->
          <!--          :options="director.value"-->
          <!--          :option-value="opt => Object(opt) === opt && 'userId' in opt ? opt.id : null"-->
          <!--          option-label="firstName"-->
          <!--          v-model="director"-->
          <!--        >-->
          <!--          <template v-slot:option="scope">-->
          <!--            <q-item v-bind="scope.itemProps">-->
          <!--              <q-item-section>-->
          <!--                <q-item-label>{{ scope.opt.userId.firstname }}</q-item-label>-->
          <!--              </q-item-section>-->
          <!--            </q-item>-->
          <!--          </template>-->
          <!--        </q-select>-->
          <div>
            <q-btn label="Valider" type="submit" color="primary"/>
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
