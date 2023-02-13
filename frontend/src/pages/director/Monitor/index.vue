<script setup lang="ts">



</script>

<template>
  <q-page class="bg-light-grey  items-center">

    <MonitorList />

  </q-page>
</template>

<route lang="yaml">
meta:
  layout: director
  requiresAuth: true
  roles: director
</route>
