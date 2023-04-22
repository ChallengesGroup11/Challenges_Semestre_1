<script setup lang="ts">

import { reactive, defineComponent } from "vue";
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


const user = reactive({
  id : "",
  firstname: "",
  lastname: "",
});


onMounted(async () => {
  if (localStorage.getItem('token') == null) {
    await router.push('/auth')
  } else {
    const {params} = useRoute();
    const {id} = params;
    await getUser(id)
  }
});

const getUser=(id: string | string[])=>{
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/users/`+id,{
    headers:{
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
    }
  })
    .then((response) => response.json())
    .then((data) => {
      user.id = data.id;
      user.firstname = data.firstname;
      user.lastname = data.lastname;
    })
    .catch((error) => {
      console.error('Error:', error);
    })
}

const onSubmit = async (id: string | string[]) =>{
  const response =  await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/users/`+id ,{
    method: 'PATCH',
      headers: {
      'Content-Type': 'application/merge-patch+json',
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
    },
    body: JSON.stringify(user),
  });
  const data = await response.json();
  viewNotif("thumb_up", "green", "Votre profil à bien été édité", "white", "top-right")
  await router.push('/student/profil')
}

</script>


<template>
  <q-page class="bg-light-grey  items-center">
    <div class=" q-mt-sl  row justify-center">
      <div class="q-pa-md">
        <h2 class="text-h5 w-100 q-mb-xl">Editer votre compte </h2>
        <q-form
          @submit="onSubmit(user.id)"
          class="q-gutter-md"
        >
          <q-input
            v-model="user.firstname"
            label="Prénom"
            hint="Prénom"
            filled
            lazy-rules
            :rules="[ val => val && val.length > 0 || 'Please type something']"
          />
          <q-input
            filled
            v-model="user.lastname"
            label="Nom"
            hint="Nom"
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


<route lang="yaml">
meta:
  layout: student
  requiresAuth: true
  roles: user
</route>
