<script setup lang="ts">
const state = reactive({
  leftDrawerOpen: false,
})

const fn = {
  toggleLeftDrawer() {
    state.leftDrawerOpen = !state.leftDrawerOpen
  },
}
</script>

<template>
  <q-layout view="hHh lpR fFf">
    <q-header elevated class="bg-primary text-black">
      <q-toolbar>
        <q-btn dense flat round icon="menu" @click="fn.toggleLeftDrawer" />

        <q-toolbar-title>
          <q-avatar>
            <img src="https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg" />
          </q-avatar>
          Driving School
        </q-toolbar-title>
      </q-toolbar>
    </q-header>

    <q-drawer v-model="state.leftDrawerOpen" class="bg-primary text-black" show-if-above side="left" bordered>
      ELEMENT MENU
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>
