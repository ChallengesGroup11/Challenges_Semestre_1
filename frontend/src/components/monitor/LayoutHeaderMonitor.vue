<script setup lang="ts">
import { reactive } from "vue"
import { useStoreUser } from "../../../stores/user"
const router = useRouter()

const currentUser = reactive({ value: [] })

const logoutUser = async () => {
  localStorage.removeItem("token")
  await router.push("/auth")
}

const emit = defineEmits<{
  (e: "on-loaded", payload: boolean): void
}>()

// const getUser = async () => {
//   return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/me`, {
//     headers: {
//       Authorization: "Bearer " + localStorage.getItem("token"),
//     },
//   })
//     .then((response) => response.json())
//     .then((data) => {
//       currentUser.value = data
//       useStoreUser().user = data
//     })
//     .catch((error) => {
//       console.error("Error:", error)
//     })
// }

const getUser = async () => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/me`, {
    headers: {
      Authorization: "Bearer " + localStorage.getItem("token"),
    },
  })
    .then((response) => response.json())
    .then((data) => {
      currentUser.value = data
      useStoreUser().user = data
      if (data.monitor.drivingSchoolId != null) {
        useStoreUser().drivingSchool = data.monitor.drivingSchoolId
        if (data.monitor.drivingSchoolId.bookings != null) {
          useStoreUser().ListBooking = data.monitor.drivingSchoolId.bookings
        }
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
      <span>Bienvenue {{ currentUser.value.firstname }} sur notre site !</span>
    </q-toolbar>
    <q-toolbar inset>
      <q-btn icon="home_filled" flat label="Home" to="/monitor" />
      <q-btn icon="person" flat label="Mon profil" to="/monitor/profil" />
      <q-btn icon="people" flat label="Mes élèves" to="/monitor/student" />
      <q-space />
      <q-btn @click="logoutUser" icon="logout" flat label="Déconnexion" />
    </q-toolbar>
  </q-header>
</template>
