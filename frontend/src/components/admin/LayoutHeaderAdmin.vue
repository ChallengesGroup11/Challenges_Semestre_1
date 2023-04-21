<script setup lang="ts">

import {reactive} from "vue";
const router = useRouter()

const currentUser = reactive({value: []});

onMounted(async () => {
  await getUser()
});

const logoutUser = async () => {
  localStorage.removeItem('token')
  await router.push('/auth')
}

const getUser = async () => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/me`, {
    headers:{
      'Authorization': 'Bearer ' + localStorage.getItem('token') ,
    }
  })
    .then((response) => response.json())
    .then((data) => {
      currentUser.value = data;
    })
    .catch((error) => {
      console.error("Error:", error);
    });
};

</script>


<template>
  <q-header elevated class="bg-primary text-white">
    <q-toolbar>

      <q-space />
      <q-toolbar-title>
        <q-avatar>
          <img src="https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg" />
        </q-avatar>
       DRIVE QUEEN
      </q-toolbar-title>
    </q-toolbar>
  </q-header>

  <q-header elevated class="bg-primary text-white">
    <q-toolbar>
      <q-toolbar-title>
        <q-avatar>
          <img src="https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg" />
        </q-avatar>
        DRIVE QUEEN
      </q-toolbar-title>
      <span>Bienvenue {{ currentUser.value.firstname }} sur notre site !</span>
    </q-toolbar>
    <q-toolbar inset>
      <q-btn flat label="Home" to="/admin" />
      <q-btn flat label="Auto-école" to="/admin/DrivingSchool" />
      <q-btn flat label="Directors" to="/admin/Director" />
      <q-btn flat label="Monitor" to="/admin/Monitor" />
      <q-btn flat label="Student" to="/admin/Student" />
      <q-btn flat label="Package" to="/admin/Package" />
      <q-space />
      <q-btn @click="logoutUser" icon="logout" flat label="Déconnexion" />
    </q-toolbar>
  </q-header>
</template>
