<script setup lang="ts">
const router = useRouter()
const enum SignupOrLogin {
  SIGNUP = 'signup',
  LOGIN = 'login',
}
const state = reactive({
  form: {
    email: '',
    password: '',
    passwordSecond: '',
  },
  signupOrLogin: SignupOrLogin.LOGIN as SignupOrLogin,
})

const fn = {
  onClickSignup() {
    state.signupOrLogin = state.signupOrLogin === SignupOrLogin.SIGNUP ? SignupOrLogin.LOGIN : SignupOrLogin.SIGNUP
  },
  createAccount() {
    console.log('createAccount')
  },

  login() {
    console.log('login')
    router.push('/dashboard')
  },
}
</script>

<template>
  <div>
    <q-page class="bg-light-grey window-height window-width row justify-center items-center">
      <div class="column">
        <div class="row">
          <h2 class="text-h5 text-white q-my-md">DRIVING SHCOOL</h2>
        </div>
        <div class="row">
          <q-tabs v-model="tab" inline-label class="bg-purple text-white shadow-2">
            <q-tab name="mails" icon="mail" label="Mails" />
            <q-tab name="alarms" icon="alarm" label="Alarms" />
            <q-tab name="movies" icon="movie" label="Movies" />
          </q-tabs>
          <q-card square bordered class="q-pa-lg shadow-1">
            <h2 v-if="state.signupOrLogin === SignupOrLogin.LOGIN">Connection</h2>
            <h2 v-if="state.signupOrLogin === SignupOrLogin.SIGNUP">Création de compte</h2>
            <q-card-section>
              <q-form class="q-gutter-md">
                <q-input v-model="state.form.email" square filled clearable type="email" label="email" />
                <q-input v-model="state.form.password" square filled clearable type="password" label="password" />
                <q-input
                  v-if="state.signupOrLogin === SignupOrLogin.SIGNUP"
                  v-model="state.form.passwordSecond"
                  square
                  filled
                  clearable
                  type="password"
                  label="Confirmer le mot de passe"
                />
              </q-form>
            </q-card-section>
            <q-card-actions class="q-px-md">
              <q-btn
                v-if="state.signupOrLogin === SignupOrLogin.LOGIN"
                unelevated
                color="light-green-7"
                size="lg"
                class="full-width"
                label="Se connecter"
              />
              <q-btn
                v-if="state.signupOrLogin === SignupOrLogin.SIGNUP"
                unelevated
                color="light-green-7"
                size="lg"
                class="full-width"
                label="Créer un compte"
              />
            </q-card-actions>
            <q-card-section class="text-center q-pa-none">
              <p class="text-grey-6 hover:bg-black" @click="fn.onClickSignup">Pas de compte ? Créez en un.</p>
            </q-card-section>
          </q-card>
        </div>
      </div>
    </q-page>
  </div>
</template>

<style>
.q-card {
  width: 360px;
}
</style>

<route lang="yaml">
meta:
  layout: auth
  requiresNotAuth: true
</route>
