<script setup lang="ts">

import {reactive} from "vue";
const router = useRouter();

const packageItem = reactive({value: []});


onMounted(
  async () => {
    if (localStorage.getItem('token') == null) {
      await router.push('/auth')
    } else {
      await getPackage()
    }
  }
)

const getPackage = async () => {
  return fetch("https://localhost/packages", {
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
    }
  })
    .then((response) => response.json())
    .then((data) => {
      packageItem.value = data["hydra:member"];
      console.log(packageItem.value);
    })
    .catch((error) => {
      console.error("Error:", error);
    });
};

const payement = (item: any) => {
  router.push('/student/package/payement/' + item.id)
}


</script>


<template>
  <q-page>
    <div class="row">
      <q-card v-for="item in packageItem.value" :key="item.id" class="my-card q-ma-lg col">
        <q-img :src="'../public/images/package_'+item.nbCredit+'.png'"></q-img>
        <q-card-section>
          <div class="text-h4"> {{ item.name }} </div>
          <div class="text-left">
            <div class="text-h10">Description : {{ item.description }} </div>
            <div class="text-h6">Prix : {{ item.price }} €</div>
            <div class="text-h6">Crédits : {{ item.nbCredit }} </div>
          </div>
          <div class="text-center">
            <q-btn label="Acheter" @click="payement(item)" color="positive" icon="monetization_ons"></q-btn>
          </div>
        </q-card-section>

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
