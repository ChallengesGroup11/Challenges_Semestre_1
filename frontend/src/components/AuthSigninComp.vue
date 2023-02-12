<script setup lang="ts">

const router = useRouter()

const errorMessage = ref(null)

const user = reactive({
  email: '',
  password: '',
})

function parseJwt(token: string | null) {
  if (!token) {
    return;
  }
  const base64Url = token.split(".")[1];
  const base64 = base64Url.replace("-", "+").replace("_", "/");
  return JSON.parse(window.atob(base64));
}


const onClickSignin = async (e: { preventDefault: () => void; }) => {
  e.preventDefault();
  console.log("bla")
  const response = await fetch('https://localhost/authentication_token', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(user),
  })
  const data = await response.json()
  if (data.token) {
    errorMessage.value = null;
    localStorage.setItem("token", data.token);
    const token = localStorage.getItem("token");
    const user = parseJwt(token);
    if(user.roles.includes("ROLE_ADMIN")){
      await router.push("/admin");
    } else{
      await router.push("/student");
    }
  } else if (data.message) {
    errorMessage.value = data.message
  } else if (data.error) {
    errorMessage.value = data.error
  }
}
</script>

<template>
  <div>
    <q-card-section>
      <span>{{ errorMessage }}</span>
      <q-form class="q-gutter-md">
        <q-input v-model="user.email" square filled clearable type="email" label="email" />
        <q-input v-model="user.password" square filled clearable type="password" label="password" />
      </q-form>
    </q-card-section>
    <q-card-actions class="q-px-md">
      <q-btn @click="onClickSignin" color="light-green-7" size="lg" class="full-width" label="Se connecter" />
    </q-card-actions>
  </div>
</template>
