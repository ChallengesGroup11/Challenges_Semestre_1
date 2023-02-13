<script setup lang="ts">

import {reactive, onMounted} from 'vue'
import {useRouter} from 'vue-router'
import { useQuasar } from 'quasar'

const cardNumber = ref('');
const cardDate = ref('');
const cardCvv = ref('');
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

const loading = ref(false)

const router = useRouter()

const packageItem = reactive({
  id: '',
  name: '',
  price: '',
  description: '',
  nbCredit: '',
})

onMounted(async () => {
  if (localStorage.getItem('token') == null) {
    await router.push('/auth')
  } else {
    const {params} = useRoute();
    const {id} = params;
    await getOnePackage(id)
  }
})

const getOnePackage = async (id: string | string[]) => {


  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/packages/` + id, {
    headers: {
      'accept': 'application/ld+json',
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
    },
  })
    .then((response) => response.json())
    .then((data) => {
      packageItem.id = data.id
      packageItem.name = data.name
      packageItem.price = data.price
      packageItem.description = data.description
      packageItem.nbCredit = data.nbCredit

    })
    .catch((error) => {
      console.error('Error:', error)
    })
}

const OnSubmit = async (id: string | string[]) => {
  //expolode cardDate en cardMonth et cardYear
  loading.value = true
  const cardMonth = cardDate.value.split('/')[0]
  const cardYear = cardDate.value.split('/')[1]

  const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/payments`, {
    method: 'POST',
    headers:{
      'Content-Type': 'application/json',
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
    },
    body: JSON.stringify({
        cardNumber: cardNumber.value,
        cardMonth: cardMonth,
        cardYear: cardYear,
        cardCvv: cardCvv.value,
        package: packageItem
    }),
    }
  )
  const data = await response.json();
  if(data.type !== 'success'){
    loading.value = false
    viewNotif('thumb_down','red-4', data.message, 'white', 'top-right')
    return
  }else {
    loading.value = false
    await router.push('/student/profil')
    viewNotif('thumb_up', 'green-4', data.message, 'white', 'top-right')
  }
}

</script>


<template>
  <q-page>
    <div class=" q-mt-sl  row justify-center">
      <div class="q-pa-md">
        <h2 class="text-h5  q-mb-xl">Achetez des crédits </h2>
        <q-markup-table class="q-mb-lg">
          <thead>
          <tr>
            <th colspan="2" class="text-center">
              <span class="text-h5">Récapitulatif</span>
            </th>
          </tr>
          <tr>
            <th>Crédits</th>
            <th>Prix</th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td>{{ packageItem.nbCredit }}</td>
            <td>{{ packageItem.price }} €</td>
          </tr>
          </tbody>
        </q-markup-table>

        <q-form
          @submit="onSubmit(packageItem.id)"
          class="q-gutter-md q-mt-xl"
        >
          <q-input
            class="q-mb-lg"
            filled
            v-model="cardNumber"
            label="Numéro de carte"
            mask="#### - #### - #### - ####"
            fill-mask="#"
            unmasked-value
            hint="Mask: #### - #### - #### - ####"
          />
          <q-input
            class="q-mb-lg"
            filled
            v-model="cardDate"
            label="Date d'expiration"
            mask="##/##"
            fill-mask
            hint="Mask: ##/##"
          />
          <q-input
            class="q-mb-lg"
            filled
            v-model="cardCvv"
            label="CVV"
            mask="###"
            fill-mask
            hint="Mask: ###">
          </q-input>
          <q-btn
            class="q-mb-lg"
            label="Acheter"
            :loading="loading"
            @click="OnSubmit(packageItem.id)"
            color="positive"
          />
        </q-form>
        <span class="text-h10 italic"> Ce paiement est sécurisé et utilise le système de paiement de <a
          href="https://stripe.com/fr/privacy>">Stripe</a> </span>
      </div>
    </div>


  </q-page>


</template>

<route lang="yaml">
meta:
  layout: student
  requiresAuth: true
  roles: user
</route>
