<script setup lang="ts">


</script>

<template>
  <q-page class="bg-light-grey  items-center">

    <DirectorList />

  </q-page>
</template>

<route lang="yaml">
meta:
  layout: admin
  requiresAuth: true
  roles: admin
</route>
