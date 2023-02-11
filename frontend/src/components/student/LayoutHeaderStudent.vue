<script setup lang="ts">

import {reactive} from "vue";

const currentUser = reactive({value: []});

onMounted(async () => {
  await getUser()
});


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
      <q-btn icon="paid" flat label="Acheter des crédits" to="/student/buy_credit"/>
      <q-space/>
      <q-btn icon="logout" flat label="Déconnexion" to="/logout"/>
    </q-toolbar>
  </q-header>
</template>
