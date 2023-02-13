<script setup lang="ts">
import {onMounted, reactive} from 'vue';
const router = useRouter()
const student = reactive({
  id: "",
  nb_hour_done: "",
  url_code_certification: "",
  url_cni: "",
});
onMounted(async () => {
  const {params} = useRoute();
  const {id} = params;
  await fetchOneStudent(id);
});
const fetchOneStudent = async (id: string | string[]) => {
  return fetch('https://localhost:81/students/'+id, {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
    }
    })
    .then((response) => response.json())
    .then((data) => {
      student.id = data.id;
      student.nb_hour_done = data.nb_hour_done;
      student.url_code_certification = data.url_code_certification;
      student.url_cni = data.url_cni;
    })
    .catch((error) => {
      console.error('Error:', error);
    })
};
const onSubmit = async (id: string) => {
  console.log(id)
  const response = await fetch('https://localhost:81/students/'+id, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(student),
  });
  const data = await response.json();
  await router.push('/admin/student')
  console.log('Success:', data);
};
// console.log(student);
</script>


<template>
  <q-page class="bg-light-grey  items-center">
    <div class=" q-mt-sl  row justify-center">
      <div class="q-pa-md">
        <h2 class="text-h5 w-100 q-mb-xl">Editer un élève </h2>
        <q-form
          @submit="onSubmit(student.id)"
          class="q-gutter-md"
        >
          <q-input
            filled
            v-model="student.nb_hour_done"
            label="Nombre d'heure effectuées"
            lazy-rules
            :rules="[ val => val && val.length > 0 || 'Please type something']"
          />
          <q-input
            @update:model-value="val => { file = val[0] }"
            filled
            type="file"
            v-model="student.url_code_certification"
          />
          <q-input
            @update:model-value="val => { file = val[0] }"
            filled
            type="file"
            v-model="student.url_cni"
          />
          <div>
            <q-btn label="Submit" type="submit" color="primary"/>
          </div>
        </q-form>
      </div>
    </div>
  </q-page>
</template>


<style>
</style>


<route lang="yaml">
meta:
  layout: admin
  requiresAuth: true
  roles: admin
</route>