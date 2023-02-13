<script setup lang="ts">

import {reactive} from "vue";
const currentUser = reactive({value: []});

onMounted(async () => {
  await getUser()
});



const getUser = async () => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/me`, {
    headers:{
      'Authorization': 'Bearer ' + localStorage.getItem('token') ,
    }
  })
    .then((response) => response.json())
    .then((data) => {
      console.log(data);
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
      <q-btn flat label="Home" to="/admin" />
      <q-btn flat label="Auto-école" to="/admin/DrivingSchool" />
      <q-btn flat label="Users" to="/about" />
      <q-space />
      <q-toolbar-title>
        <q-avatar>
          <img src="https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg" />
        </q-avatar>
       DRIVE QUEEN
      </q-toolbar-title>
    </q-toolbar>
  </q-header>
</template>
