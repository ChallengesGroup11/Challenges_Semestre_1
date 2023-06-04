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
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/packages`, {
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
    }
  })
    .then((response) => response.json())
    .then((data) => {
      packageItem.value = data["hydra:member"];
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
      <q-card v-for="item in packageItem.value" :key="item.id" class="my-card q-ma-lg col bg-secondary text-white">
        <q-card-section>
          <div class="text-h4"> {{ item.name }} </div>
          <hr/>
          <div class="text-h4 text-center mt-2">
            {{ item.nbCredit }} Crédits *
          </div>
          <div class="text-h10 text-center mt-8">
            Description : {{ item.description }}
          </div>
          <div class="text-price text-center mt-2">
            {{ item.price }} €
          </div>
          <q-btn label="Acheter" @click="payement(item)" class="btn-primary" ></q-btn>
        </q-card-section>

      </q-card>
    </div>
  </q-page>
</template>

<style scoped>
.row {
  margin-top: 20px;
  margin-bottom: 20px;
}

.my-card {
  max-width: 400px;
  margin: 0 auto;
}

.text-h4 {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 10px;
}

.text-h6 {
  font-size: 16px;
  margin-top: 10px;
}

.text-h10 {
  font-size: 14px;
}

.text-center {
  text-align: center;
}

.text-left {
  text-align: left;
}

.btn-primary {
  background-color: #1e88e5;
}

.text-price {
font-weight: 700;
font-size: 64px;
line-height: 150px;
color: #E76F51;
-webkit-text-stroke: 2px whitesmoke
}
</style>

<route lang="yaml">
meta:
  layout: student
  requiresAuth: true
  roles: user
</route>
