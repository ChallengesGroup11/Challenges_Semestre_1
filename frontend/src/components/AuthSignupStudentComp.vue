<script setup lang="ts">

import {reactive} from 'vue';

const user = reactive({
  firstname: "",
  lastname: "",
  email: "",
  password: "",
  passwordSecond: "",
  identity: "",
});

const onClickSignup = async (e: { preventDefault: () => void; }) => {
  e.preventDefault();
  const requestData = {
    firstname: user.firstname,
    lastname: user.lastname,
    email: user.email,
    password: user.password,
    roles: ["ROLE_USER"],
    status: false
  };
  console.log(requestData)
  const response = await fetch('https://localhost:81/signup/student', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(requestData),
  });
  // const data = await response.json();
  if (response.status === 201) {
    console.log("created")
  }
  if (response.status === 422) {
    const data = await response.json();
    console.log(data)
  }
}
</script>

<template>
  <div>
    <q-card-section>
      <q-form class="q-gutter-md">
        <q-input v-model="user.firstname" square filled clearable type="text" label="Prénom" />
        <q-input v-model="user.lastname" square filled clearable type="text" label="Nom" />

        <q-input v-model="user.email" square filled clearable type="email" label="email" />
        <q-input v-model="user.password" square filled clearable type="password" label="password" />
        <q-input
          v-model="user.passwordSecond"
          square
          filled
          clearable
          type="password"
          label="Confirmer le mot de
      passe"
        />
      </q-form>
    </q-card-section>
    <q-card-actions class="q-px-md">
      <q-btn
        @click="onClickSignup"
        unelevated
        color="light-green-7"
        size="lg"
        class="full-width"
        label="Créer un compte étudiant"
      />
    </q-card-actions>
  </div>
</template>
