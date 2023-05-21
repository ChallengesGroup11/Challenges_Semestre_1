<script setup lang="ts">
// change name, surname; numero de tel

const router = useRouter()

const user = {
  firstname: "",
  lastname: "",
  email: "",
  password: "",
}

const requestData = {
  firstname: user.firstname,
  lastname: user.lastname,
  email: user.email,
  password: user.password,
  roles: ["ROLE_MONITOR"],
  status: false,
  createBy: "admin",
  drivingSchoolId: drivingSchoolSelected.value.id,
}

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
    roles: ["ROLE_MONITOR"],
    status: false,
    createBy: "admin",
    drivingSchoolId: drivingSchoolSelected.value.id,
  }
  const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/director/create_monitor`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(requestData),
  })
  if (response.status === 201) {
    await router.push("/admin/Monitor")
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
      </q-form>
    </q-card-section>
    <q-card-actions class="q-px-md">
      <q-btn
        @click="onClickSignup"
        unelevated
        color="positive"
        size="lg"
        class="full-width"
        label="Créer un compte étudiant"
      />
    </q-card-actions>
  </div>
</template>
