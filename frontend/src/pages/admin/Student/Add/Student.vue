<script setup lang='ts'>
const router = useRouter()

const user = reactive({
  firstname: "",
  lastname: "",
  email: "",
  password:"",
});


const initProvisoryPassword = () => {
  for (let i = 0; i < 16; i++) {
    user.password += String.fromCharCode(Math.floor(Math.random() * 26) + 97)
  }
}

const onSubmit = async () => {
  initProvisoryPassword()

  const requestData = {
    firstname: user.firstname,
    lastname: user.lastname,
    email: user.email,
    password: user.password,
    roles: ["ROLE_USER"],
    status: false,
    createBy: 'admin',
    };

  const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/signup/student`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(requestData),
  });

    if (response.status === 201) {
        await router.push("/admin/Student")
    }

}

</script>

<template>
  <q-page class="bg-light-grey  items-center">
    <div class=" q-mt-sl  row justify-center">
      <div class="q-pa-md">
        <h2 class="text-h5 w-100 q-mb-xl">Ajouter un nouvel étudiant </h2>
        <q-form @submit.prevent="onSubmit" class="q-gutter-md">
          <q-input label="Prénom" filled lazy-rules :rules="[val => val.length > 0 || 'Veuillez saisir votre prénom']"
            v-model="user.firstname" />
          <q-input label="Nom" filled lazy-rules :rules="[val => val.length > 0 || 'Veuillez saisir votre nom']"
            v-model="user.lastname" />
          <q-input label="Email" filled lazy-rules :rules="[val => val.length > 0 || 'Veuillez saisir votre email']"
            v-model="user.email" />
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
