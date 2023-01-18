<script setup lang="ts">
const emit = defineEmits<{
  (e: 'signin', val: FormSignin): void
}>()

class FormSignin {
  email = ''
  password = ''
}
const state = reactive({
  form: new FormSignin(),
})

const fn = {
  onClickSignin() {
    console.log('signin')
  },
}
</script>

<template>
  <div>
    <q-card-section>
      <q-form class="q-gutter-md">
        <q-input v-model="state.form.email" square filled clearable type="email" label="email" />
        <q-input v-model="state.form.password" square filled clearable type="password" label="password" />
        <q-input
          v-model="state.form.password"
          square
          filled
          clearable
          type="password"
          label="Confirmer le mot de passe"
        />
      </q-form>
    </q-card-section>
    <q-card-actions class="q-px-md">
      <q-btn @click="fn.onClickSignin" color="light-green-7" size="lg" class="full-width" label="Se connecter" />
    </q-card-actions>
  </div>
</template>
