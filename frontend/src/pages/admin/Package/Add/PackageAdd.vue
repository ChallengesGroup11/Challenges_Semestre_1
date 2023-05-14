<script setup lang='ts'>
import { number } from 'zod';

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
const packageDS = reactive({
  name: "",
  description: "",
  nb_credit: 0,
  price: 0,
});

const onSubmit = async () => {
  const requestData = {
    name: packageDS.name,
    description: packageDS.description,
    nbCredit: packageDS.nb_credit,
    price: packageDS.price,
    };
  const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/packages`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(requestData),
  });
  if (response.status === 400) {
    viewNotif("thumb_down", "red", "Le package ne peut pas être ajouté", "white", "top-right")
    return
  }
  if (response.status === 500) {
    viewNotif("thumb_down", "red", "Une erreur est survenue", "white", "top-right")
    return
  }
  if (response.status === 201) {
    viewNotif("thumb_up", "green", "Le package à bien été ajouté", "white", "top-right")
    await router.push("/admin/Package")
  }


}

</script>

<template>
  <q-page class="bg-light-grey  items-center">
    <div class=" q-mt-sl  row justify-center">
      <div class="q-pa-md">
        <h2 class="text-h5 w-100 q-mb-xl">Ajouter un package</h2>
        <q-form @submit.prevent="onSubmit" class="q-gutter-md">
          <q-input label="Nom" filled lazy-rules :rules="[val => val.length > 0 || 'Veuillez saisir le nom du package']"
            v-model="packageDS.name" />
          <q-input label="Description" filled lazy-rules :rules="[val => val.length > 0 || 'Veuillez saisir saisir la description du package']"
            v-model="packageDS.description" />
          <q-input type="number" label="Nombre de crédit" filled lazy-rules :rules="[val => val > 0 || 'Veuillez saisir le nombre de crédit']"
            v-model.number="packageDS.nb_credit" />
          <q-input type="number" label="Prix" filled lazy-rules :rules="[val => val > 0 || 'Veuillez saisir le prix du package']"
            v-model.number="packageDS.price" />
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
