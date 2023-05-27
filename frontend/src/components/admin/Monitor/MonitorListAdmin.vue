<script setup lang="ts">
const router = useRouter()
import { onMounted, reactive } from 'vue'
import { useQuasar } from "quasar"

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

onMounted(async () => {
  await fetchMonitor()
})

const fetchMonitor = async () => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/monitors`, {
    headers: {
      accept: 'application/ld+json',
      Authorization: 'Bearer ' + localStorage.getItem('token'),
    },
  })
    .then((response) => response.json())
    .then((data) => {
        monitors.value = data['hydra:member']
      console.log(monitors.value)
    })
}

const editMonitor = (id: string) => {
  router.push('/admin/Monitor/Edit/' + id)
}

const addMonitor = () => {
  router.push('/admin/Monitor/Add/Monitor')
}

const deleteMonitor = async (id: string) => {
  const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/monitors/` + id, {
    method: 'DELETE',
    headers: {
      accept: 'application/ld+json',
      Authorization: 'Bearer ' + localStorage.getItem('token'),
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
  console.log(id)
  const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/users/` + id + '/edit_status', {
    method: 'PATCH',
    headers: {
      accept: 'application/ld+json',
      'Content-type': 'application/merge-patch+json',
      Authorization: 'Bearer ' + localStorage.getItem('token'),
    },
    body: '{}',
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
          <th>Prénom</th>
          <th>Nom</th>
		      <th>Email</th>
		      <th>Auto-école</th>
          <th>Status</th>
          <th>Editer</th>
          <th>Supprimer</th>
          <th>Valider le status</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(monitor, index) in monitors.value" :key="index">
          <td>{{ monitor.userId.firstname }}</td>
          <td>{{ monitor.userId.lastname }}</td>
          <td>{{ monitor.userId.email }}</td>
          <td v-if="monitor.drivingSchoolId">
            {{ monitor.drivingSchoolId.name }}
          </td>
          <td v-else>
            <span> Pas d'auto école </span>
          </td>
          <td v-if="monitor.userId.status === true">
            <span> Valider </span>
          </td>
          <td v-else>
            <span> En attente </span>
          </td>
          <td>
            <q-btn color="warning" text-color="white" icon="edit" @click="editMonitor(monitor.id)" />
          </td>
          <td>
            <q-btn color="negative" text-color="white" icon="delete" @click="deleteMonitor(monitor.id)" />
          </td>
          <td v-if="monitor.userId.status === true">
            <q-btn color="secondary" text-color="white" icon="sync" disabled @click="changeStatus(monitor.userId.id)" />
          </td>
          <td v-else>
            <q-btn color="warning" text-color="white" icon="sync" @click="changeStatus(monitor.userId.id)" />
          </td>
        </tr>
      </tbody>
    </q-markup-table>
  </div>
</template>

<style></style>
