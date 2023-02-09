import { handleFileUpload } from '../utils/domUtil';
<script setup lang="ts">
import { domUtil } from '~/utils/domUtil'

const emit = defineEmits<{
  (e: 'signup', val: FormSignupDirector): void
}>()

class FormSignupDirector {
  name = ''
  firstname = ''
  email = ''
  password = ''
  passwordSecond = ''
  numSiret = ''
  kbisFile = {} as File
}

const state = reactive({
  form: new FormSignupDirector(),
})

const fn = {
  onClickSignup() {
    console.log('signup')
  },
}
</script>

<template>
  <div>
    <q-card-section>
      <q-form class="q-gutter-md">
        <q-input v-model="state.form.name" square filled clearable type="text" label="Prénom" />
        <q-input v-model="state.form.firstname" square filled clearable type="text" label="Nom" />

        <q-input v-model="state.form.email" square filled clearable type="email" label="email" />
        <q-input v-model="state.form.password" square filled clearable type="password" label="password" />
        <q-input
          v-model="state.form.passwordSecond"
          square
          filled
          clearable
          type="password"
          label="Confirmer le mot de
      passe"
        />
        <q-input v-model="state.form.numSiret" square filled clearable type="number" label="Numéro de Siret" />
        <q-file v-model="state.form.kbisFile">
          <template #prepend>
            <q-icon name="attach_file" />
          </template>
        </q-file>
      </q-form>
    </q-card-section>
    <q-card-actions class="q-px-md">
      <q-btn
        @click="fn.onClickSignup"
        unelevated
        color="light-green-7"
        size="lg"
        class="full-width"
        label="Créer un dirigeant d'une Auto-école"
      />
    </q-card-actions>
  </div>
</template>
