<script setup lang="ts">

const router = useRouter()

const currentUser = reactive({ value: [] })
// -> pour chaque élève, quand on clique, get tous ses créneaux avec (moniteur, élève, date, heure début, heure fin, statut, commentaires)
onMounted(async () => {
  if (localStorage.getItem('token') == null) {
    await router.push('/auth')
  } else {
    await getUser()
  }
})

const state = reactive({
  monitor: null as any,
  student: [] as any,
})

const getUser = async () => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/me`, {
    headers: {
      Authorization: 'Bearer ' + localStorage.getItem('token'),
    },
  })
    .then((response) => response.json())
    .then((data) => {
      currentUser.value = data;
    })
    .catch((error) => {
      console.error('Error:', error)
    })
}

const loadData = async () => {
  // Mattéo : Requete qui récupère les infos de l'élève ud monitor en connecter
  // LE QUERY BUILDER EST DANS MONITOR REPOSITORy et le controller c'est GetStudentByMonitorController
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/monitors/` + + currentUser.value?.monitor.id + `/student`, {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
      Authorization: "Bearer " + localStorage.getItem("token"),
    },
  })
  .then((response) => response.json())
    .then((data) => {
      state.student = data;
      console.log(state.student)
    })
    .catch((error) => {
      console.error("Error:", error);
    });

}

loadData()


</script>
<template>
  <div>
    <q-btn class="q-ma-md" color="secondary" label="Get Student" @click="loadData()" />
  </div>
</template>

<route lang="yaml">
meta:
  layout: monitor
  requiresAuth: true
  roles: user
</route>
