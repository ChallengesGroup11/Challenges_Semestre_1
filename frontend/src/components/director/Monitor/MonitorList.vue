<script setup lang="ts">
import { onMounted, reactive } from "vue"
const router = useRouter()

const monitors = reactive({ value: [] })
const currentUser = reactive({ value: [] })

onMounted(async () => {
  if (localStorage.getItem("token") == null) {
    await router.push("/auth")
  } else {
    await getUser()
    await fetchMonitor()
  }
})

const getUser = async () => {
  return fetch("https://localhost/me", {
    headers: {
      Authorization: `Bearer ${localStorage.getItem("token")}`,
    },
  })
    .then((response) => response.json())
    .then((data) => {
      currentUser.value = data
    })
    .catch((error) => {
      console.error("Error:", error)
    })
}

const fetchMonitor = async () => {
  return fetch("https://localhost/monitors/getAll", {
    headers: {
      accept: "application/ld+json",
      Authorization: `Bearer ${localStorage.getItem("token")}`,
    },
  })
    .then((response) => response.json())
    .then((data) => {
      monitors.value = data["hydra:member"]
      console.log(monitors.value)
    })
}

const editMonitor = (id: string) => {
  router.push(`/director/Monitor/Edit/${id}`)
}

const addMonitor = () => {
  router.push("/director/Monitor/Add")
}

const deleteMonitor = async (id: string) => {
  const response = await fetch(`https://localhost/monitors/${id}/delete`, {
    method: "DELETE",
    headers: {
      accept: "application/ld+json",
      Authorization: `Bearer ${localStorage.getItem("token")}`,
    },
  })
  await fetchMonitor()
}

const changeStatus = async (id: string) => {
  const response = await fetch(`https://localhost/monitors/${id}/edit_status`, {
    method: "PATCH",
    headers: {
      accept: "application/ld+json",
      "Content-type": "application/merge-patch+json",
      Authorization: `Bearer ${localStorage.getItem("token")}`,
    },
    body: "{}",
  })
  const data = await response.json()
  console.log("Success:", data)
  await fetchMonitor()
}

console.log(monitors)
</script>

<template>
  <!--    Tableau qui liste les auto école -->
  <div class="q-pt-lg window-height window-width row justify-center">
    <q-markup-table>
      <thead>
        <tr>
          <th colspan="13">
            <div class="text-h4 q-ml-md">
              Liste des moniteurs
              <q-btn class="float-right" color="positive" text-color="white" icon="add" @click="addMonitor()" />
            </div>
          </th>
        </tr>
        <tr>
          <th>Id</th>
          <th>Nom</th>
          <th>Prénom</th>
          <th>Statut</th>
          <th>Modifier</th>
          <th>Supprimer</th>
          <th>Changer le statut</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(monitor, index) in monitors.value" :key="index">
          <td>{{ monitor.id }}</td>
          <td>{{ monitor.userId.lastname }}</td>
          <td>{{ monitor.userId.firstname }}</td>
          <td v-if="monitor.userId.status === true">
            <span> Activé </span>
          </td>
          <td v-else>
            <span> Désactivé </span>
          </td>
          <td>
            <q-btn color="warning" text-color="white" icon="edit" @click="editMonitor(monitor.id)" />
          </td>
          <td>
            <q-btn color="negative" text-color="white" icon="delete" @click="deleteMonitor(monitor.id)" />
          </td>
          <td v-if="monitor.status === true">
            <q-btn color="secondary" text-color="white" icon="sync" @click="changeStatus(monitor.id)" />
          </td>
          <td v-else>
            <q-btn color="warning" text-color="white" icon="sync" @click="changeStatus(monitor.id)" />
          </td>
        </tr>
      </tbody>
    </q-markup-table>
  </div>
</template>

<style></style>
