<script setup lang="ts">
const router = useRouter()
import {onMounted, reactive} from 'vue';
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
const monitor = reactive({
  id: "",
  firstname: "",
  lastname: "",
  email: "",
  drivingSchoolId: "",
});

onMounted(async () => {
  const {params} = useRoute();
  const {id} = params;
  await fetchOneMonitor(id);
});

const fetchOneMonitor = async (id: string | string[]) => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/monitors/`+id,
    {
      headers:{
        'Authorization': 'Bearer ' + localStorage.getItem('token'),
      }
    }
  )
    .then((response) => response.json())
    .then((data) => {
        console.log(data)
      monitor.id = data.userId.id;
      monitor.firstname = data.userId.firstname;
      monitor.lastname = data.userId.lastname;
      monitor.email = data.userId.email;
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
    body: JSON.stringify(monitor),
  });
  if (response.status === 400) {
    viewNotif("thumb_down", "red", "Le moniteur ne peut pas être modifié", "white", "top-right")
    return
  }
  if (response.status === 500) {
    viewNotif("thumb_down", "red", "Une erreur est survenue", "white", "top-right")
    return
  }
  if (response.status === 200) {
    viewNotif("thumb_up", "green", "Le moniteur à bien été modifié", "white", "top-right")
    await router.push('/admin/Monitor')
  }

};
</script>

<template>
  <q-page class="bg-light-grey  items-center">
    <div class=" q-mt-sl  row justify-center">
      <div class="q-pa-md">
        <h2 class="text-h5 w-100 q-mb-xl">Editer un moniteur d'auto-école </h2>
        <q-form
          @submit="onSubmit(monitor.id)"
          class="q-gutter-md"
        >
          <q-input
            v-model="monitor.firstname"
            label="Firstname"
            filled
            lazy-rules
            :rules="[ val => val && val.length > 0 || 'Please type something']"
          />
          <q-input
            filled
            v-model="monitor.lastname"
            label="Lastname"
            lazy-rules
            :rules="[ val => val && val.length > 0 || 'Please type something']"
          />
          <q-input
            filled
            v-model="monitor.email"
            label="Email"
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

