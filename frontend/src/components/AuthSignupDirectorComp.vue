import { handleFileUpload } from '../utils/domUtil';
<script setup lang="ts">
// import { domUtil } from '~/utils/domUtil'
import {ref} from 'vue'

// const emit = defineEmits<{
//   (e: 'signup', val: FormSignupDirector): void
// }>()

// class FormSignupDirector {
//   name = 'bonjour'
//   lastname = 'cava'
//   email = 'florianguillot94@gmail.com'
//   password = 'test'
//   passwordSecond = 'test'
//   numSiret = '123456'
//   kbisFile = {} as File
//   identity= 'student'
// }

const firstname = ref('')
const lastname = ref('')
const email = ref('')
const password = ref('')
const passwordSecond = ref('')
const numSiret = ref('')
const kbisFile = ref()
const identity = ref('student')


// const state = reactive({
//   form: new FormSignupDirector(),
// })

const onClickSignup = async () => {
  const formData = new FormData();
  formData.append('firstname', firstname.value);
  formData.append('lastname', lastname.value);
  formData.append('email', email.value);
  formData.append('password', password.value);
  formData.append('identity', identity.value);
  formData.append('siret', numSiret.value);
  formData.append('kbis', kbisFile.value[0]);
  const response = await fetch('https://localhost/SignUp', {
    method: 'POST',
    body: formData
  });
  const data = await response.json();
  console.log(data)
}

</script>

<template>
  <div>
    <q-card-section>
      <q-form class="q-gutter-md">
        <q-input v-model="firstname" square filled clearable type="text" label="Prénom" />
        <q-input v-model="lastname" square filled clearable type="text" label="Nom" />

        <q-input v-model="email" square filled clearable type="email" label="email" />
        <q-input v-model="password" square filled clearable type="password" label="password" />
        <q-input v-model="passwordSecond" square filled clearable type="password" label="Confirmer le mot de
      passe" />
        <q-input v-model="numSiret" square filled clearable type="number" label="Numéro de Siret" />
        <q-file v-model="kbisFile"  @update:model-value="val => { file = val[0] }">
          <template #prepend>
            <q-icon name="attach_file" />
          </template>
        </q-file>
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
