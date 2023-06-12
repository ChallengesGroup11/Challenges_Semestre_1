<script setup lang="ts">
import { reactive } from "vue"
import { useStoreUser } from "../../../stores/user"
const router = useRouter()

const emit = defineEmits<{
  (e: "on-loaded", payload: boolean): void
}>()

const currentUser = reactive({ value: [] })

const logoutUser = async () => {
  localStorage.removeItem("token")
  await router.push("/auth")
}

const getUser = async () => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/me`, {
    headers: {
      Authorization: "Bearer " + localStorage.getItem("token"),
    },
  })
    .then((response) => response.json())
    .then((data) => {
      currentUser.value = data
      console.log("Success:", data)

      useStoreUser().user = data
      useStoreUser().ListMonitor = data.director?.drivingSchoolId?.monitors
      if (data.director.drivingSchoolId != null) {
        useStoreUser().drivingSchool = data.director.drivingSchoolId
      }
    })
    .catch((error) => {
      console.error("Error:", error)
    })
}

const loadData = async () => {
  if (!localStorage.getItem("token")) {
    await router.push("/auth")
  } else {
    await getUser()
  }
  emit("on-loaded", true)
}

const onClickTab = (tab: string) => {
  router.push(`/director/${tab}`)
}

loadData()
</script>

<template>
  <q-header elevated class="bg-primary text-white">
    <q-toolbar>
      <q-toolbar-title>
        <q-avatar>
          <img src="https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg" />
        </q-avatar>
        DRIVE QUEEN
      </q-toolbar-title>
      <q-space />
      <q-btn icon="home_filled" flat label="Home" to="/director" />
      <q-btn icon="person" flat label="Liste des moniteurs" to="/director/monitor" />
      <q-btn icon="school" flat label="Créneaux" to="/director/slots" />
      <q-space />
      <q-btn @click="logoutUser" icon="logout" flat label="Déconnexion" />
    </q-toolbar>
  </q-header>
</template>
