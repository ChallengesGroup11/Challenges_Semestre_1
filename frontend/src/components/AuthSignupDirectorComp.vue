import { handleFileUpload } from '../utils/domUtil';
<script setup lang="ts">
// import { domUtil } from '~/utils/domUtil'
import {reactive} from 'vue'

const router = useRouter();

const user = reactive({
  firstname: "",
  lastname: "",
  email: "",
  password: "",
  passwordSecond: "",
  identity: "",
});

const error = reactive({
  type: "",
  message: "",
})

const onClickSignup = async (e: { preventDefault: () => void; }) => {
  e.preventDefault();
  if(user.password !== user.passwordSecond) {
    error.type = "password"
    error.message = "Les mots de passe ne correspondent pas"
    return error
  }else {
    error.type = ""
    error.message = ""
    const requestData = {
      firstname: user.firstname,
      lastname: user.lastname,
      email: user.email,
      password: user.password,
      roles: ["ROLE_DIRECTOR"],
      status: false
    };
    console.log(requestData)
    const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/signup/director`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(requestData),
    });
    // const data = await response.json();
    if (response.status === 201) {
      console.log("created")
      await router.push("/auth")
    }
    if (response.status === 422) {
      const data = await response.json();
      if(data.hydra.title === "An error occurred") {
        error.type = "email"
        error.message = "Cet email est déjà utilisé"
        return error
      }
      console.log(data)
    }
  }
}

</script>

<template>
  <div>
    <q-card-section>
      {{error.message}}
      <q-form class="q-gutter-md">
        <q-input v-model="user.firstname" square filled clearable type="text" label="Prénom" />
        <q-input v-model="user.lastname" square filled clearable type="text" label="Nom" />

        <q-input v-model="user.email" square filled clearable type="email" label="email" />
        <q-input v-model="user.password" square filled clearable type="password" label="password" />
        <q-input v-model="user.passwordSecond" square filled clearable type="password" label="Confirmer le mot de
      passe" />
      </q-form>
    </q-card-section>
    <q-card-actions class="q-px-md">
      <q-btn
        @click="onClickSignup"
        unelevated
        color="light-green-7"
        size="lg"
        class="full-width"
        label="Créer un dirigeant d'une Auto-école"
      />
    </q-card-actions>
  </div>
</template>
