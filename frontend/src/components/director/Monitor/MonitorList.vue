<script setup lang="ts">
import { reactive } from "vue"
const router = useRouter()

import { useQuasar } from "quasar"
import { useStoreUser } from "../../../../stores/user"

const drivingSchool = useStoreUser().drivingSchool

const $q = useQuasar()
const viewNotif = (icon: any, color: string, message: string, textColor: string, position: any) => {
  $q.notify({
    icon,
    color,
    message,
    textColor,
    position,
  })
}

const monitors = reactive({ value: [] })
const currentUser = reactive({ value: [] })

const state = reactive({
  currentMonitor: null as any,
  isLoading: true,
})

const fn = {
  initProvisoryPassword(): string {
    let password = ""
    for (let i = 0; i < 16; i++) {
      password += String.fromCharCode(Math.floor(Math.random() * 26) + 97)
    }
    return password
  },

  makeInitMonitor() {
    return {
      id: "",
      firstname: "",
      lastname: "",
      email: "",
      password: fn.initProvisoryPassword(),
    }
  },

  makeMonitor(monitor: any) {
    debugger
    return {
      id: monitor.userId.id,
      firstname: monitor.userId.firstname,
      lastname: monitor.userId.lastname,
      email: monitor.userId.email,
      password: "",
    }
  },
}

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
  const idDrivingSchool = drivingSchool.id
  debugger
  const res = fetch(`https://localhost/driving_schools/${idDrivingSchool}/allMonitor`, {
    headers: {
      accept: "application/ld+json",
      Authorization: `Bearer ${localStorage.getItem("token")}`,
    },
  })
    .then((response) => response.json())
    .then((data) => {
      monitors.value = data
      debugger
    })
  return res
}

// const monitors = drivingSchool.monitors
// console.log(monitors)
// return monitors
// const res = fetch("https://localhost/monitors/getAll", {
//   headers: {
//     accept: "application/ld+json",
//     Authorization: `Bearer ${localStorage.getItem("token")}`,
//   },
// })
//   .then((response) => response.json())
//   .then((data) => {
//     monitors.value = data["hydra:member"]
//     console.log(monitors.value)
//   })
// return res

const editMonitor = (monitor: any) => {
  debugger
  state.currentMonitor = fn.makeMonitor(monitor)
}

const addMonitor = () => {
  state.currentMonitor = fn.makeInitMonitor()
  console.log(state.currentMonitor)
}

const deleteMonitor = async (id: object) => {
  const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/monitors/` + id.id, {
    method: "DELETE",
    headers: {
      accept: "application/ld+json",
      Authorization: "Bearer " + localStorage.getItem("token"),
    },
  })
  if (response.status === 400) {
    viewNotif("thumb_down", "red", "Le moniteur ne peut pas être supprimé", "white", "top-right")
    return
  }
  if (response.status === 500) {
    viewNotif("thumb_down", "red", "Une erreur est survenue", "white", "top-right")
    return
  }
  if (response.status === 204) {
    viewNotif("thumb_up", "green", "Le moniteur à bien été supprimé", "white", "top-right")
  }
  await fetchMonitor()
}

const changeStatus = async (id: string) => {
  const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/users/` + id.userId.id + "/edit_status", {
    method: "PATCH",
    headers: {
      accept: "application/ld+json",
      "Content-type": "application/merge-patch+json",
      Authorization: "Bearer " + localStorage.getItem("token"),
    },
    body: "{}",
  })
  if (response.status === 400) {
    viewNotif("thumb_down", "red", "Le moniteur ne peut pas être modifié", "white", "top-right")
    return
  }
  if (response.status === 500) {
    viewNotif("thumb_down", "red", "Une erreur est survenue", "white", "top-right")
    return
  }
  if (response.status === 201) {
    viewNotif("thumb_up", "green", "Le moniteur à bien été modifié", "white", "top-right")
  }
  await fetchMonitor()
}

const loadData = async () => {
  await fetchMonitor()
  state.isLoading = false
}

loadData()
</script>

<template>
  <Teleport to="body">
    <MonitorModal :monitor="state.currentMonitor" @on-save="fetchMonitor()" />
  </Teleport>
  <!--    Tableau qui liste les auto école -->
  <div class="q-pt-lg window-height window-width row justify-center">
    <span> </span>
    <q-markup-table>
      <thead>
        <tr>
          <th colspan="13">
            <div class="text-h4 q-ml-md">
              Liste des moniteurs
              <q-btn
                class="float-right"
                color="positive"
                text-color="white"
                icon="add"
                @click="addMonitor()"
                :disable="currentUser.value.director?.drivingSchoolId === null"
              />
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
          <td>{{ monitor.lastname }}</td>
          <td>{{ monitor.firstname }}</td>
          <td v-if="monitor.status === true">
            <span> Activé </span>
          </td>
          <td v-else>
            <span> Désactivé </span>
          </td>
          <td>
            <q-btn color="warning" text-color="white" icon="edit" @click="editMonitor(monitor)" />
          </td>
          <td>
            <q-btn color="negative" text-color="white" icon="delete" @click="deleteMonitor(monitor)" />
          </td>
          <td v-if="monitor.status === true">
            <q-btn color="secondary" text-color="white" icon="sync" @click="changeStatus(monitor)" />
          </td>
          <td v-else>
            <q-btn color="warning" text-color="white" icon="sync" @click="changeStatus(monitor)" />
          </td>
        </tr>
      </tbody>
    </q-markup-table>
  </div>
</template>

<style></style>
