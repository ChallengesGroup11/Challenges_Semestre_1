<script setup lang="ts">
import { onMounted, reactive } from 'vue';
const router = useRouter()
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

const packageDS = reactive({
  id: "",
  name: "",
  description: "",
  nbCredit: "",
  price: "",
});

onMounted(async () => {
  const { params } = useRoute();
  const { id } = params;
  await fetchOnePackage(id);
});

const fetchOnePackage = async (id: string | string[]) => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/packages/` + id,
    {
      headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('token'),
      }
    }
  )
    .then((response) => response.json())
    .then((data) => {
      packageDS.id = data.id;
      packageDS.name = data.name;
      packageDS.description = data.description;
      packageDS.nbCredit = data.nbCredit;
      packageDS.price = data.price;
    })
    .catch((error) => {
      viewNotif("thumb_down", "red", "Une erreur est survenue", "white", "top-right")
    })
};

const onSubmit = async (id: string) => {
  const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/packages/` + id, {
    method: 'PATCH',
    headers: {
      'Access-Control-Allow-Headers':'*',
      'Access-Control-Allow-Methods': 'PATCH, OPTIONS',
      'Content-Type': 'application/merge-patch+json',
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
    },
    body: JSON.stringify(packageDS),
  });
  if (response.status === 400) {
    viewNotif("thumb_down", "red", "Le package ne peut pas être modifié", "white", "top-right")
    return
  }
  if (response.status === 500) {
    viewNotif("thumb_down", "red", "Une erreur est survenue", "white", "top-right")
    return
  }
  if (response.status === 200) {
    viewNotif("thumb_up", "green", "Le package à bien été modifié", "white", "top-right")
    await router.push('/admin/Package')
  }

};
</script>

<template>
  <q-page class="bg-light-grey  items-center">
    <div class=" q-mt-sl  row justify-center">
      <div class="q-pa-md">
        <h2 class="text-h5 w-100 q-mb-xl">Editer un package</h2>
        <q-form @submit="onSubmit(packageDS.id)" class="q-gutter-md">
          <q-input v-model="packageDS.name" label="Nom du package" filled lazy-rules
            :rules="[val => val && val.length > 0 || 'Veuillez écrire quelque chose']" />
          <q-input filled v-model="packageDS.description" label="Description" lazy-rules
            :rules="[val => val && val.length > 0 || 'Veuillez écrire quelque chose']" />
          <q-input filled v-model.number="packageDS.nbCredit" label="Nombre de crédit" lazy-rules
            :rules="[val => val && val > 0 || 'Veuillez écrire quelque chose']" />
          <q-input filled v-model.number="packageDS.price" label="Prix" lazy-rules
            :rules="[val => val && val > 0 || 'Veuillez écrire quelque chose']" />
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

