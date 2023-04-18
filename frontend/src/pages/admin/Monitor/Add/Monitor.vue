<script setup lang='ts'>

import { ref } from 'vue'

const router = useRouter()

// const firstname = ref('')
// const lastname = ref('')
// const email = ref('')

const user = reactive({
  firstname: "",
  lastname: "",
  email: "",
  password:"",
});
const drivingSchoolSelected = ref('')
var drivingSchoolSelectedSend = ""
const drivingSchools = reactive({value:[]})

const initProvisoryPassword = () => {
  for (let i = 0; i < 16; i++) {
    user.password += String.fromCharCode(Math.floor(Math.random() * 26) + 97)
  }
}

const fetchDrivingSchools = async () => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/driving_schools`, {
    headers: {
      accept: 'application/ld+json',
      Authorization: 'Bearer ' + localStorage.getItem('token'),
    },
  })
    .then((response) => response.json())
    .then((data) => {
      drivingSchools.value = data['hydra:member']
    })
}


onMounted(async () => {
  await fetchDrivingSchools()
})

const onSubmit = async () => {
  console.log(drivingSchoolSelected.value)
  initProvisoryPassword()
  console.log(drivingSchoolSelected.value.id)

  const requestData = {
    firstname: user.firstname,
    lastname: user.lastname,
    email: user.email,
    password: user.password,
    roles: ["ROLE_MONITOR"],
    status: false,
    createBy: 'admin',
    drivingSchoolId: drivingSchoolSelected.value.id
    };
  const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/director/create_monitor`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(requestData),
  });

    if (response.status === 201) {
        await router.push("/admin/Monitor")
    }

}

</script>

<template>
  <q-page class="bg-light-grey  items-center">
    <div class=" q-mt-sl  row justify-center">
      <div class="q-pa-md">
        <h2 class="text-h5 w-100 q-mb-xl">Ajouter un moiteur d'auto-école</h2>
        <q-form @submit.prevent="onSubmit" class="q-gutter-md">
          <q-input label="Prénom" filled lazy-rules :rules="[val => val.length > 0 || 'Veuillez saisir votre prénom']"
            v-model="user.firstname" />
          <q-input label="Nom" filled lazy-rules :rules="[val => val.length > 0 || 'Veuillez saisir votre nom']"
            v-model="user.lastname" />
          <q-input label="Email" filled lazy-rules :rules="[val => val.length > 0 || 'Veuillez saisir votre email']"
            v-model="user.email" />

						<select v-model="drivingSchoolSelected" placeholder="Choisir un directeur">
              <option value="" selected>Choisir une auto-école</option>
              <option v-for="drivingSchool in drivingSchools.value" :value="drivingSchool">
                {{ drivingSchool.name }}
              </option>
            </select>
          <div>
            <q-btn label="Valider" type="submit" color="primary" />
          </div>
        </q-form>
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
