<script setup lang="ts">


import { assertExpressionStatement } from "@babel/types";
import { reactive, defineComponent } from "vue";


const router = useRouter()

const user = reactive({
  name: "",
  address: "",
  city: "",
  zipCode: "",
  phone_number: "",
  siret: "",
  filePathKbis: "",
});
const currentUser = reactive({ value: [] });
const isLoading = ref(false);
const { params } = useRoute();
const { id } = params;

const getUser = async () => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/me`, {
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
    }
  })
    .then((response) => response.json())
    .then((data) => {
      currentUser.value = data;
    })
    .catch((error) => {
      console.error("Error:", error);
    });
};

onMounted(async () => {
  if (localStorage.getItem('token') == null) {
    await router.push('/auth')
  } else {
    await getUser()
  }
});

const onSubmit = async () => {
  if(user.filePathKbis == "" || user.siret == ""){
    alert("Veuillez remplir tous les champs")
    return
  }
  const formData = new FormData();
  formData.append('file', user.filePathKbis);
  formData.append('siret', user.siret);
  formData.append('director', currentUser.value.director.id);
  formData.append('name', user.name);
  formData.append('address', user.address);
  formData.append('city', user.city);
  formData.append('zipCode', user.zipCode);
  formData.append('phone_number', user.phone_number);

  try {
    isLoading.value = true;
    const checked = await fetch('http://localhost:3200/api/director', {
      headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('token'),
        'Content-Type': 'multipart/form-data'
      },
      method: 'POST',
      body: formData,
    })
    const validDocs = await checked;
    if (validDocs.status == 200) {
      const response = await fetch('https://localhost/driving_school/create', {
        method: 'POST',
        headers: {
          'Authorization': 'Bearer ' + localStorage.getItem('token'),
        },
        body: formData,
      });

      isLoading.value = false;
      if (response.ok) {
        console.log('Success:', response.status);
        await router.push('/director')
      } else {
        alert("Vos documents n'ont pas été enregistrés, veuillez réessayer")
      }
    } else {
      alert("Vos documents ne sont pas valides, veuillez réessayer")
    }
  } catch (error) {
    console.error('Error:', error);
  }
}


</script>

<template>
  <q-page class="bg-light-grey  items-center">
    <div class=" q-mt-sl  row justify-center">
      <q-card class="q-ma-lg">
        <div class="q-pa-md">
          <h2 class="text-h5 q-mb-xl">Enregistrez votre auto-école</h2>
          <q-form @submit="onSubmit(id)" class="q-gutter-md">
            <q-input filled v-model="user.name" label="Nom de l'école" />
            <q-input filled v-model="user.address" label="Adresse" />
            <q-input filled v-model="user.city" label="Ville" />
            <q-input filled v-model="user.zipCode" label="Code postal" />
            <q-input filled v-model="user.phone_number" label="Numéro de téléphone" />
            <q-file @update:model-value="val => { file = val[0] }" filled label="Ajouter votre Kbis" type="file"
              v-model="user.filePathKbis">
              <template v-slot:append>
                <q-icon name="attach_file" />
              </template>
            </q-file>
            <q-input filled v-model="user.siret" label="Votre n° de Siret" />
            <div>
              <q-btn label="Submit" type="submit" color="primary" />
            </div>
          </q-form>
          <q-circular-progress v-if="isLoading" indeterminate size="50px" :thickness="0.22" rounded color="lime"
            track-color="grey-3" class="q-ma-md" />
        </div>
      </q-card>
    </div>
  </q-page>
</template>

<route lang="yaml">
meta:
  layout: director
  requiresAuth: true
  roles: director
</route>
