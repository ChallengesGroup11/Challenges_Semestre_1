<script setup lang="ts">
const state = reactive({
  isLoaded: false,
})
</script>

<template>
  <q-layout view="hHh lpR fFf">
    <LayoutHeaderMonitor @on-loaded="state.isLoaded = true" />
    <q-page-container v-if="state.isLoaded === true" class="px-5">
      <router-view />
    </q-page-container>
  </q-layout>
</template>
