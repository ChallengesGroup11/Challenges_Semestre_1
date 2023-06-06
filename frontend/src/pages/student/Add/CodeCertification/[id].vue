<script setup lang="ts">
import { reactive } from "vue"
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

const user = reactive({
  filePathCode: "",
  filePathCni: "",
})

const isLoading = ref(false)

onMounted(async () => {
  if (localStorage.getItem("token") == null) {
    await router.push("/auth")
  } else {
  }
})
const { params } = useRoute()
const { id } = params

const onSubmit = async () => {
  const formData = new FormData()
  formData.append("fileCode", user.filePathCode)
  formData.append("fileCni", user.filePathCni)
  formData.append("userId", id)

  try {
    isLoading.value = true
    const validDocs = await fetch(`${import.meta.env.VITE_KYC_URL}/student`, {
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
      },
      method: "POST",
      body: formData,
    })
    if (validDocs.status == 200) {
      const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/student/pathCode`, {
        method: "POST",
        headers: {
          Authorization: "Bearer " + localStorage.getItem("token"),
        },
        body: formData,
      })
      if (response.ok) {
          viewNotif("thumb_up", "green", "Vos fichiers ont bien été ajoutées", "white", "top-right")
          await router.push("/student")
        } else {
          viewNotif("thumb_down", "red", "Vos fichiers n'ont pas été ajoutées", "white", "top-right")
        }
      } else {
        viewNotif("thumb_down", "red", "Vos documents ne sont pas valides, veuillez réessayer", "white", "top-right")
      }
    } catch (error) {
      viewNotif("thumb_down", "red", "Un problème st survenue", "white", "top-right")
    }
}
</script>

<template>
  <q-page class="bg-light-grey items-center">
    <div class="q-mt-sl row justify-center">
      <q-card class="q-ma-lg">
        <div class="q-pa-md">
          <h2 class="text-h5 q-mb-xl">Ajouter votre certification de code et votre carte d'identité</h2>
          <span>Ajouter le coté de la carte ou l'on appercoit votre visage pour la CNI</span>
          <q-img class="q-ma-md" style="height: 170px; max-width: 300px" src="/../public/images/cni_flat.png" />
          <q-form @submit="onSubmit(id)" class="q-gutter-md">
            <q-file
              @update:model-value="
                (val) => {
                  file = val[0]
                }
              "
              filled
              label="Ajouter votre code de la route"
              type="file"
              v-model="user.filePathCode"
            >
              <template v-slot:append>
                <q-icon name="attach_file" />
              </template>
            </q-file>
            <q-file
              @update:model-value="
                (val) => {
                  file = val[0]
                }
              "
              filled
              label="Ajouter votre carte d'identité"
              type="file"
              v-model="user.filePathCni"
            >
              <template v-slot:append>
                <q-icon name="attach_file" />
              </template>
            </q-file>
            <div>
              <q-btn label="Submit" type="submit" color="primary" />
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
      </q-card>
    </div>
  </q-page>
</template>

<route lang="yaml">
meta:
  layout: student
  requiresAuth: true
  roles: user
</route>
