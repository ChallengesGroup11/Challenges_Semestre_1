<script setup lang="ts">

import DrivingSchoolList from "~/components/admin/DrivingSchoolList.vue";
</script>

<template>
  <q-page class="bg-light-grey  items-center">
  <div class="container">
<!--  Créer un   Menu avec des sous composant-->

        <DrivingSchoolList />


  </div>
  </q-page>
</template>

<route lang="yaml">
meta:
  layout: admin
  requiresAuth: true
  roles: admin
</route>
