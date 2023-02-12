<script setup lang="ts">

import {reactive} from "vue";
const router = useRouter()

const currentUser = reactive({value: []});

onMounted(async () => {
  if(!localStorage.getItem('token')) {
    await router.push('/auth')
  }else {
    await getUser()
  }
});

const logoutUser = async () => {
  localStorage.removeItem('token');
  await router.push('/auth');
}

const getUser = async () => {
  return fetch("https://localhost/me", {
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
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
      <q-toolbar-title>
        <q-avatar>
          <img src="https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg"/>
        </q-avatar>
        DRIVE QUEEN
      </q-toolbar-title>
      <span>Bienvenue {{ currentUser.value.firstname }} sur notre site !</span>
    </q-toolbar>
    <q-toolbar inset>
      <q-btn  icon="home_filled" flat label="Home" to="/student"/>
      <q-btn icon="person" flat label="Mon profil" to="/student/profil"/>
      <q-btn icon="paid" flat label="Acheter des crédits" to="/student/package"/>
      <q-space/>
      <q-btn @click="logoutUser" icon="logout" flat label="Déconnexion" />
    </q-toolbar>
  </q-header>
</template>
