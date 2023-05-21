<script setup lang="ts">
import AuthSigninComp from "~/components/AuthSigninComp.vue"
import AuthSignupStudentComp from "~/components/AuthSignupStudentComp.vue"
import { useQuasar } from 'quasar'


const $q = useQuasar()
const viewNotif = (icon:any ,color: string, message: string, textColor: string, position:any) => {
  $q.notify({
    icon,
    color,
    message,
    textColor,
    position,
  })
}
const router = useRouter()

const ee = "d"
enum EnumTab {
  SCHOOL_DRIVING = "school-driving",
  STUDENT = "student",
}

const enum EnumSignupOrLogin {
  SIGNUP = "signup",
  LOGIN = "login",
}

const state = reactive({
  signupOrLogin: EnumSignupOrLogin.LOGIN as EnumSignupOrLogin,
  currentTab: EnumTab.STUDENT as EnumTab,
  isShownModal: false,
  email: "",

})

const fn = {
  changeToSignupOrLogin() {
    state.signupOrLogin =
      state.signupOrLogin === EnumSignupOrLogin.LOGIN ? EnumSignupOrLogin.SIGNUP : EnumSignupOrLogin.LOGIN
  },
  redirectToSignin() {
    state.signupOrLogin = EnumSignupOrLogin.LOGIN
  },
  forgetPassword() {
    state.isShownModal = true
    // router.push("/forget-password")
  },
  async sendEmailPassword() {
    const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/users/send_email/password`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({email : state.email}),
    })
    if(response.status === 200) {
      viewNotif('thumb_up','green-4', "Un email vous a été envoyé", 'white', 'top-right')
      state.isShownModal = false
    }
    if(response.status === 404){
      viewNotif('thumb_down','red-4', "Une erreur c'est produite", 'white', 'top-right')
    }
    if(response.status === 510){
      viewNotif('thumb_down','red-4', "L'email n'exite pas", 'white', 'top-right')
    }
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
            <template v-if="state.signupOrLogin === EnumSignupOrLogin.LOGIN">
              <h2>Se connecter</h2>
              <AuthSigninComp />
            </template>
            <template v-if="state.signupOrLogin === EnumSignupOrLogin.SIGNUP">
              <h2>Vous êtes ?</h2>
              <q-tabs v-model="state.currentTab" inline-label class="bg-blue text-white shadow-2">
                <q-tab :name="EnumTab.STUDENT" label="Eleve" />
                <q-tab :name="EnumTab.SCHOOL_DRIVING" label="Gérant d'auto-école" />
              </q-tabs>

              <AuthSignupDirectorComp v-if="state.currentTab === EnumTab.SCHOOL_DRIVING"
                @redirectToSignin="fn.redirectToSignin" />
              <AuthSignupStudentComp v-if="state.currentTab === EnumTab.STUDENT"
                @redirectToSignin="fn.redirectToSignin" />
            </template>
            <q-card-section class="text-center q-pa-none">
              <p v-if="state.signupOrLogin === EnumSignupOrLogin.LOGIN" @click="fn.changeToSignupOrLogin"
                class="text-grey-6 cursor-pointer">
                Pas encore de compte ? En créer un.
              </p>
              <p v-if="state.signupOrLogin === EnumSignupOrLogin.LOGIN" @click="fn.forgetPassword"
                class="text-grey-6 cursor-pointer">
                Mot de passe oublié ?
              </p>
              <p v-else-if="state.signupOrLogin === EnumSignupOrLogin.SIGNUP" @click="fn.changeToSignupOrLogin"
                class="text-grey-6 cursor-pointer">
                Se connecter à son compte.
              </p>
            </q-card-section>
          </q-card>
        </div>
      </div>
      <q-dialog v-model="state.isShownModal">
        <q-card style="width: 600px">
          <q-card-section>
            <div class="text-h6">Modifier mon mot de passe</div>
          </q-card-section>
          <q-card-section>
            <q-form class="q-gutter-md">
              <q-input square filled clearable v-model="state.email" type="email" label="email" />
            </q-form>
          </q-card-section>
          <q-card-actions align="right">
            <q-btn flat label="Envoyer" color="secondary" @click="fn.sendEmailPassword" />
          </q-card-actions>
        </q-card>
      </q-dialog>

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
