<script setup lang="ts">
import { ref } from "vue"
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
const router = useRouter()

const directors = reactive({ value: [] })
const directorSelected = ref("")

const drive = reactive({
  name: "",
  address: "",
  city: "",
  zipcode: "",
  phoneNumber: "",
  siret: "",
  filePath: "",
})

const isLoading = ref(false)
const fetchDirector = async () => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/directors`, {
    headers: {
      accept: "application/ld+json",
      Authorization: "Bearer " + localStorage.getItem("token"),
    },
  })
    .then((response) => response.json())
    .then((data) => {
      directors.value = data["hydra:member"]
      for (let i = directors.value.length - 1; i >= 0; i--) {
        const element = directors.value[i]
        if (element.drivingSchoolId) {
          directors.value.splice(i, 1)
        }
      }
      console.log(directors.value)
    })
}

onMounted(async () => {
  await fetchDirector()
})
const onSubmit = async () => {
  if (drive.filePath == "" || drive.siret == "") {
    alert("Veuillez remplir tous les champs")
    return
  }
  if (drive.zipcode.length != 5) {
    viewNotif("thumb_down", "red", "Le code postal n'est pas valide", "white", "top-right")
  } else if (drive.phoneNumber.length != 10) {
    viewNotif("thumb_down", "red", "Le numéro de téléphone n'est pas valide", "white", "top-right")
  } else if (drive.siret.length != 14) {
    viewNotif("thumb_down", "red", "Le numéro de siret n'est pas valide", "white", "top-right")
  } else {
    console.log(directorSelected.value)
    const formData = new FormData()
    formData.append("file", drive.filePath)
    formData.append("name", drive.name)
    formData.append("address", drive.address)
    formData.append("zipcode", drive.zipcode)
    formData.append("city", drive.city)
    formData.append("phoneNumber", drive.phoneNumber)
    formData.append("siret", drive.siret)
    if (directorSelected.value != "") {
      formData.append("director", "directors/" + directorSelected.value)
    }
    try {
      isLoading.value = true
      const checked = await fetch(`${import.meta.env.VITE_KYC_URL}/api/director`, {
        headers: {
          Authorization: "Bearer " + localStorage.getItem("token"),
        },
        method: "POST",
        body: formData,
      })
      const validDocs = await checked
      if (validDocs.status == 200) {
        const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/driving_schools`, {
          method: "POST",
          headers: {
            Authorization: "Bearer " + localStorage.getItem("token"),
          },
          body: formData,
        })
        isLoading.value = false
        if (response.ok) {
          viewNotif("thumb_up", "green", "Votre auto école à bien été créée", "white", "top-right")
          await router.push("/admin/drivingSchool")
        } else {
          viewNotif(
            "warning",
            "red",
            "Vos documents n'ont pas été enregistrer, veuillez réessayer",
            "white",
            "top-right",
          )
        }
      } else {
        viewNotif("warning", "red", "Vos documents ne sont pas valide, veuillez réessayer", "white", "top-right")
      }
    } catch (error) {
      viewNotif("thumb_down", "red", "Un problème st survenue", "white", "top-right")
      console.error("Error:", error)
    }
  }
}
</script>

<template>
  <q-page class="bg-light-grey items-center">
    <div class="q-mt-sl row justify-center">
      <div class="q-pa-md">
        <h2 class="text-h5 w-100 q-mb-xl">Ajouter une auto école</h2>
        <q-form @submit.prevent="onSubmit" class="q-gutter-md">
          <q-input
            label="Nom de l'école de conduite"
            filled
            lazy-rules
            :rules="[(val) => val.length > 0 || 'Veuillez saisir un nom']"
            v-model="drive.name"
          />
          <q-input
            label="Adresse"
            filled
            lazy-rules
            :rules="[(val) => val.length > 0 || 'Veuillez saisir une adresse']"
            v-model="drive.address"
          />
          <q-input
            label="Code postal"
            filled
            lazy-rules
            :rules="[(val) => val.length > 0 || 'Veuillez saisir un code postal']"
            v-model="drive.zipcode"
          />
          <q-input
            label="Ville"
            filled
            lazy-rules
            :rules="[(val) => val.length > 0 || 'Veuillez saisir une ville']"
            v-model="drive.city"
          />
          <q-input
            label="Numéro de téléphone"
            filled
            lazy-rules
            :rules="[(val) => val.length > 0 || 'Veuillez saisir un numéro de téléphone']"
            v-model="drive.phoneNumber"
          />
          <q-input
            label="Numéro de SIRET"
            filled
            lazy-rules
            :rules="[(val) => val.length > 0 || 'Veuillez saisir un numéro de SIRET']"
            v-model="drive.siret"
          />
          <q-file
            @update:model-value="
              (val) => {
                file = val[0]
              }
            "
            filled
            type="file"
            v-model="drive.filePath"
          />

          <select v-model="directorSelected" placeholder="Choisir un directeur">
            <option value="" selected>Choisir un directeur</option>
            <option v-for="director in directors.value" :value="director.id">
              {{ director.userId.firstname }}
            </option>
          </select>
          <div>
            <q-btn label="Valider" type="submit" color="primary" />
          </div>
        </q-form>
        <q-circular-progress
          v-if="isLoading"
          indeterminate
          size="50px"
          :thickness="0.22"
          rounded
          color="lime"
          track-color="grey-3"
          class="q-ma-md"
        />
      </div>
    </div>
  </q-page>
</template>

<style></style>
<route lang="yaml">
meta:
  layout: admin
  requiresAuth: true
  roles: admin
</route>
