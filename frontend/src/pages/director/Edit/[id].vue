<script setup lang="ts">
const router = useRouter()
import { onMounted, reactive } from 'vue';
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

const director = reactive({
  id: "",
  firstname: "",
  lastname: "",
  email: "",
  drivingSchoolId: "",
});

onMounted(async () => {
  const { params } = useRoute();
  const { id } = params;
  await fetchOneDirector(id);
});

const fetchOneDirector = async (id: string | string[]) => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/directors/` + id,
    {
      headers: {
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
      viewNotif("thumb_down", "red", "Une erreur est survenue", "white", "top-right")
    })
};

const onSubmit = async (id: string) => {
  const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/users/` + id, {
    method: 'PATCH',
    headers: {
      'Content-Type': 'application/merge-patch+json',
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
    },
    body: JSON.stringify(director),
  });
  if (response.status === 400) {
    viewNotif("thumb_down", "red", "Le directeur ne peut pas être modifié", "white", "top-right")
    return
  }
  if (response.status === 500) {
    viewNotif("thumb_down", "red", "Une erreur est survenue", "white", "top-right")
    return
  }
  if (response.status === 200) {
    viewNotif("thumb_up", "green", "Le directeur à bien été modifié", "white", "top-right")
    await router.push('/director')

  }
};
</script>

<template>
  <q-page class="bg-light-grey  items-center">
    <div class=" q-mt-sl  row justify-center">
      <div class="q-pa-md">
        <h2 class="text-h5 w-100 q-mb-xl">Editer un Directeur d'auto-école </h2>
        <q-form @submit="onSubmit(director.id)" class="q-gutter-md">
          <q-input v-model="director.firstname" label="Prénom" filled lazy-rules
            :rules="[val => val && val.length > 0 || 'Veuillez écrire quelque chose']" />
          <q-input filled v-model="director.lastname" label="Nom"  lazy-rules
            :rules="[val => val && val.length > 0 || 'Veuillez écrire quelque chose']" />
          <q-input filled v-model="director.email" label="Email"  lazy-rules
            :rules="[val => val && val.length > 0 || 'Veuillez écrire quelque chose']" />
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
  layout: director
  requiresAuth: true
  roles: director
</route>

