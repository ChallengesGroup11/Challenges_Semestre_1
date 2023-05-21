<script setup lang="ts">
import { useQuasar } from 'quasar'
const router = useRouter()
const $q = useQuasar()
const viewNotif = (icon:any ,color: string, message: string, textColor: string, position:any) => {
  $q.notify({
    icon,
    color,
    message,
    textColor,
    position,
  })
}

onMounted(async () => {
  const {params} = useRoute()
  const { id } = params
  const token = new URLSearchParams(window.location.search).get("token");
  await validateAccount(id, token)
});


const validateAccount = (id: any, token: any) => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/CheckAccount`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      userId: id,
      token: token
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.type=="success") {
        viewNotif('thumb_up','green-4', data.message, 'white', 'top-right')
        router.push('/auth')
      }else{
        viewNotif('thumb_down','red-4', data.message, 'white', 'top-right')
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

</script>


<template>

    <div>
      <q-page class="bg-light-grey window-height window-width row justify-center items-center">
        <div class="column">
          <div class="row">
            <h2 class="text-h5 text-black q-my-md">DRIVING SHCOOL</h2>
          </div>
          <div >

            <div class="text-h4 q-ma-xl"> Bienvenue sur le site Drive Queen !</div>
           <div class="text-h5 q-ma-xl"> Vous allez être redirigé vers la connexion </div>
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
