<script setup lang="ts">
import { reactive } from "vue"
import { useStoreUser } from "../../../stores/user"
const router = useRouter()

const currentUser = reactive({ value: [] })

onMounted(async () => {
  if (!localStorage.getItem("token")) {
    await router.push("/auth")
  } else {
    await getUser()
  }

  isActiveButton()
})

onUpdated(async () => {
  isActiveButton()
})

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
      useStoreUser().user = data
    })
    .catch((error) => {
      console.error("Error:", error)
    })
}

const hasProvidedCni = computed(() => {
  return useStoreUser().user.student !== null
})

const isActiveButton = async() => {

  const path = await router.currentRoute.value.path

  console.log(path);
    
}



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
      <q-btn icon="home_filled" flat label="Home" to="/student" />
      <q-btn v-if="currentUser?.value?.student?.contentUrlCode && currentUser?.value?.student?.contentUrlCni" icon="paid" flat label="Acheter des crédits" to="/student/package" />
      <q-btn v-if="currentUser?.value?.student?.contentUrlCode && currentUser?.value?.student?.contentUrlCni" icon="list" flat label="Liste des auto-écoles" to="/student/driving_school/list" />
      <q-btn @click="logoutUser" icon="logout" flat label="Déconnexion" />
    </q-toolbar>
  </q-header>
</template>
