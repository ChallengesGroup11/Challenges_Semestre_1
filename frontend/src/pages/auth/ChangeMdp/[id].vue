<script setup lang="ts">

import { reactive } from "vue"
const { params } = useRoute()
const router = useRouter()

import { useQuasar } from 'quasar'
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
const user = reactive({
  password: "",
  passwordSecond: "",

})


const changeMdp = async () => {
  if (user.password !== user.passwordSecond) {
    viewNotif('thumb_down', 'red-4', "Les mots de passe ne sont pas identiques", 'white', 'top-right')
    return
  }
  const { id } = params
  const token = new URLSearchParams(window.location.search).get("token")
  const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/reset_password`, {
    method: 'POST',
    headers: {
      'Content-Type': "application/json",
    },
    body: JSON.stringify({
      userId: id,
      token: token,
      password: user.password,
    }),
  })
  if (response.status === 200) {
    viewNotif('thumb_up', 'green-4', "Votre mot de passe a été changé avec succès", 'white', 'top-right')
    router.push('/auth')
  }
  if (response.status === 510) {
    viewNotif('thumb_down', 'red-4', "Le token est invalide", 'white', 'top-right')
  }
}

</script>



<template>
  <div>
    <q-page class="bg-light-grey window-height window-width row justify-center items-center">
      <div class="column">
        <div class="row">
          <q-card square bordered class="q-pa-lg shadow-1">
            <h2>Changement de mot de passe</h2>
            <div>
              <q-card-section>
                <q-form class="q-gutter-md">
                  <q-input v-model="user.password" square filled clearable type="password" label="Mot de passe " />
                  <q-input v-model="user.passwordSecond" square filled clearable type="password" label="Confirmer le mot de
      passe" />
                </q-form>
              </q-card-section>
              <q-card-actions class="q-px-md">
                <q-btn @click="changeMdp" color="secondary" size="lg"  class="full-width" label="Enregistrer" />
              </q-card-actions>
            </div>
          </q-card>
        </div>
      </div>
    </q-page>
  </div>
</template>



<route lang="yaml">
  meta:
    layout: auth
    requiresNotAuth: true
</route>
