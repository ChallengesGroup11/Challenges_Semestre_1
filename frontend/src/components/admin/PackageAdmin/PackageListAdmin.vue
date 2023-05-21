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
const packages = reactive({ value: [] })

onMounted(async () => {
  await fetchPackages()
})

const fetchPackages = async () => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/packages`, {
    headers: {
      accept: 'application/ld+json',
      Authorization: 'Bearer ' + localStorage.getItem('token'),
    },
  })
    .then((response) => response.json())
    .then((data) => {
        packages.value = data['hydra:member']
      console.log(packages.value)
    })
}

const editPackage = (id: string) => {
  router.push('/admin/Package/Edit/' + id)
}

const addPackage = () => {
  router.push('/admin/Package/Add/PackageAdd')
}

const deletePackage = async (id: string) => {
  const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/packages/` + id, {
    method: 'DELETE',
    headers: {
      accept: 'application/ld+json',
      Authorization: 'Bearer ' + localStorage.getItem('token'),
    },
  })
  if (response.status === 400) {
    viewNotif("thumb_down", "red", "Le package ne peut pas être supprimé", "white", "top-right")
    return
  }
  if (response.status === 500) {
    viewNotif("thumb_down", "red", "Une erreur est survenue", "white", "top-right")
    return
  }
  if (response.status === 204) {
    viewNotif("thumb_up", "green", "Le package à bien été supprimé", "white", "top-right")
  }
  await fetchPackages()
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
              Liste des packages
              <q-btn class="float-right" color="positive" text-color="white" icon="add" @click="addPackage()" />
            </div>
          </th>
        </tr>
        <tr>
          <th>Id</th>
          <th>Nom du package</th>
          <th>Description</th>
          <th>Nombre de crédit</th>
          <th>Prix</th>
          <th>Modifier</th>
          <th>Supprimer</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(packageDS, index) in packages.value" :key="index">
          <td>{{ packageDS.id }}</td>
          <td>{{ packageDS.name }}</td>
          <td>{{ packageDS.description }}</td>
          <td>{{ packageDS.nbCredit }}</td>
          <td>{{ packageDS.price }}</td>
          <td>
            <q-btn color="warning" text-color="white" icon="edit" @click="editPackage(packageDS.id)" />
          </td>
          <td>
            <q-btn color="negative" text-color="white" icon="delete" @click="deletePackage(packageDS.id)" />
          </td>
        </tr>
      </tbody>
    </q-markup-table>
  </div>
</template>

<style></style>
