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
const drivingSchool = reactive({ value: [] })

onMounted(async () => {
  await fetchDrivingSchool()
})

const fetchDrivingSchool = async () => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/driving_schools`, {
    headers: {
      accept: 'application/ld+json',
      Authorization: 'Bearer ' + localStorage.getItem('token'),
    },
  })
    .then((response) => response.json())
    .then((data) => {
      drivingSchool.value = data['hydra:member']
      console.log(drivingSchool.value)
    })

}

const editDrivingSchool = (id: string) => {
  router.push('/admin/drivingSchool/Edit/' + id)
}

const addDrivingSchool = () => {
  router.push('/admin/drivingSchool/Add/DrivingSchool')
}

const deleteDrivingSchool = async (id: string) => {
  const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/driving_schools/` + id, {
    method: 'DELETE',
    headers: {
      accept: 'application/ld+json',
      Authorization: 'Bearer ' + localStorage.getItem('token'),
    },
  })
  if (response.status === 400) {
    viewNotif("thumb_down", "red", "L'auto école ne peut pas être supprimé", "white", "top-right")
    return
  }
  if (response.status === 500) {
    viewNotif("thumb_down", "red", "Une erreur est survenue", "white", "top-right")
    return
  }
  if (response.status === 200) {
    viewNotif("thumb_up", "green", "L'auto école à bien été supprimé", "white", "top-right")
  }
  await fetchDrivingSchool()

}

const changeStatus = async (id: string) => {
  const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/driving_schools/` + id + '/edit_status', {
    method: 'PATCH',
    headers: {
      accept: 'application/ld+json',
      'Content-type': 'application/merge-patch+json',
      Authorization: 'Bearer ' + localStorage.getItem('token'),
    },
    body: '{}',
  })
  viewNotif("thumb_up", "green", "L'auto école à bien été modifié", "white", "top-right")

  const data = await response.json()
  await fetchDrivingSchool()
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
              Liste des auto-écoles
              <q-btn class="float-right" color="positive" text-color="white" icon="add" @click="addDrivingSchool()" />
            </div>
          </th>
        </tr>
        <tr>
          <th>Id</th>
          <th>Nom</th>
          <th>Adresse</th>
          <th>Code Postal</th>
          <th>Ville</th>
          <th>Numéro de téléphone</th>
          <th>Numéro de SIRET</th>
          <th>Kbis</th>
          <th>Status</th>
          <th>Directeur</th>
          <th>Editer</th>
          <th>Supprimer</th>
          <th>Valider le status</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(driving, index) in drivingSchool.value" :key="index">
          <td>{{ driving.id }}</td>
          <td>{{ driving.name }}</td>
          <td>{{ driving.address }}</td>
          <td>{{ driving.zipcode }}</td>
          <td>{{ driving.city }}</td>
          <td>{{ driving.phoneNumber }}</td>
          <td>{{ driving.siret }}</td>
          <td v-if="driving.contentUrl">
            <span> <q-icon color="positive" name="thumb_up" /> </span>
          </td>
          <td v-else>
            <span> <q-icon color="negative" name="thumb_down" /> </span>
            <!--          <q-img-->
            <!--            :src="'../' + driving.contentUrl"-->
            <!--            :alt="driving.contentUrl"-->
            <!--            spinner-color="#8785A2"-->
            <!--            style="height: 140px; max-width: 150px"-->
            <!--          />-->
          </td>
          <td v-if="driving.status === true">
            <span> Valider </span>
          </td>
          <td v-else>
            <span> En attente </span>
          </td>
          <td v-if="driving.director">
            {{ driving.director.userId.firstname }}
          </td>
          <td v-else>Non renseigné</td>
          <td>
            <q-btn color="warning" text-color="white" icon="edit" @click="editDrivingSchool(driving.id)" />
          </td>
          <td>
            <q-btn color="negative" text-color="white" icon="delete" @click="deleteDrivingSchool(driving.id)" />
          </td>
          <td v-if="driving.status === true">
            <q-btn color="secondary" text-color="white" icon="sync" disabled @click="changeStatus(driving.id)" />
          </td>
          <td v-else>
            <q-btn color="warning" text-color="white" icon="sync" @click="changeStatus(driving.id)" />
          </td>
        </tr>
      </tbody>
    </q-markup-table>
  </div>
</template>

<style></style>
