<script setup lang="ts">
import AuthSigninComp from '~/components/AuthSigninComp.vue'
import AuthSignupStudentComp from '~/components/AuthSignupStudentComp.vue'

const router = useRouter()

enum EnumTab {
  SCHOOL_DRIVING = 'school-driving',
  STUDENT = 'student',
}

const enum EnumSignupOrLogin {
  SIGNUP = 'signup',
  LOGIN = 'login',
}

const state = reactive({
  signupOrLogin: EnumSignupOrLogin.LOGIN as EnumSignupOrLogin,
  currentTab: EnumTab.STUDENT as EnumTab,
})

const fn = {
  changeToSignupOrLogin() {
    state.signupOrLogin =
      state.signupOrLogin === EnumSignupOrLogin.LOGIN ? EnumSignupOrLogin.SIGNUP : EnumSignupOrLogin.LOGIN
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
          <q-card square bordered class="q-pa-lg shadow-1">
            <h2>Vous êtes ?</h2>
            <q-tabs v-model="state.currentTab" inline-label class="bg-purple text-white shadow-2">
              <q-tab :name="EnumTab.STUDENT" label="Eleve" />
              <q-tab :name="EnumTab.SCHOOL_DRIVING" label="Gérant d'auto-école" />
            </q-tabs>
            <template v-if="state.signupOrLogin === EnumSignupOrLogin.LOGIN">
              <h2>Se connecter en tant que {{ state.currentTab === EnumTab.SCHOOL_DRIVING ? 'Gérant' : 'Eleve' }}</h2>
              <AuthSigninComp />
            </template>
            <template v-if="state.signupOrLogin === EnumSignupOrLogin.SIGNUP">
              <h2>
                Créer un compte en tant que
                {{ state.currentTab === EnumTab.SCHOOL_DRIVING ? 'Gérant' : 'Eleve' }}
              </h2>
              <AuthSignupDirectorComp v-if="state.currentTab === EnumTab.SCHOOL_DRIVING" />
              <AuthSignupStudentComp v-if="state.currentTab === EnumTab.STUDENT" />
            </template>
            <q-card-section class="text-center q-pa-none">
              <p
                v-if="state.signupOrLogin === EnumSignupOrLogin.LOGIN"
                @click="fn.changeToSignupOrLogin"
                class="text-grey-6"
              >
                Pas encore de compte ? En créer un.
              </p>
              <p
                v-else-if="state.signupOrLogin === EnumSignupOrLogin.SIGNUP"
                @click="fn.changeToSignupOrLogin"
                class="text-grey-6"
              >
                Se connecter à son compte.
              </p>
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
