<script setup lang="ts">
import {onMounted, reactive} from 'vue';
const router = useRouter()

const packageDS = reactive({
  id: "",
  name: "",
  description: "",
  nbCredit: "",
  price: "",
});

onMounted(async () => {
  const {params} = useRoute();
  const {id} = params;
  await fetchOnePackage(id);
});

const fetchOnePackage = async (id: string | string[]) => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/packages/`+id,
    {
      headers:{
        'Authorization': 'Bearer ' + localStorage.getItem('token'),
      }
    }
  )
    .then((response) => response.json())
    .then((data) => {
      packageDS.id = data.id;
      packageDS.name = data.name;
      packageDS.description = data.description;
      packageDS.nbCredit = data.nbCredit;
      packageDS.price = data.price;
    })
    .catch((error) => {
      console.error('Error:', error);
    })
};

const onSubmit = async (id: string) => {
  const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/packages/`+id, {
    method: 'PATCH',
    headers: {
      'Content-Type': 'application/merge-patch+json',
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
    },
    body: JSON.stringify(packageDS),
  });
  const data = await response.json();
  await router.push('/admin/Package')
};
</script>

<template>
  <q-page class="bg-light-grey  items-center">
    <div class=" q-mt-sl  row justify-center">
      <div class="q-pa-md">
        <h2 class="text-h5 w-100 q-mb-xl">Editer un package</h2>
        <q-form
          @submit="onSubmit(packageDS.id)"
          class="q-gutter-md"
        >
          <q-input
            v-model="packageDS.name"
            label="Name"
            filled
            lazy-rules
            :rules="[ val => val && val.length > 0 || 'Please type something']"
          />
          <q-input
            filled
            v-model="packageDS.description"
            label="Description"
            lazy-rules
            :rules="[ val => val && val.length > 0 || 'Please type something']"
          />
          <q-input
            filled
            v-model.number="packageDS.nbCredit"
            label="Number of credit"
            lazy-rules
            :rules="[ val => val && val > 0 || 'Please type something']"
          />
            <q-input
                filled
                v-model.number="packageDS.price"
                label="Price"
                lazy-rules
                :rules="[ val => val && val > 0 || 'Please type something']"
            />
          <div>
            <q-btn label="Submit" type="submit" color="primary"/>
          </div>
        </q-form>
      </div>
    </div>
  </q-page>
</template>

<style>


</style>

<route lang="yaml">
meta:
  layout: admin
  requiresAuth: true
  roles: admin
</route>

