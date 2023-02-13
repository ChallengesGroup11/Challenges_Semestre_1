<script setup lang="ts">
import {onMounted, reactive} from 'vue';
const router = useRouter()
const student = reactive({value: []});
const studentInfo = reactive({value: []});
onMounted(async () => {
  await fetchStudent();
  await fetchStudentInfo();
});
const fetchStudent = async () => {
  return fetch('https://localhost:81/users')
    .then((response) => response.json())
    .then((data) => {
      student.value = data["hydra:member"];
      student.value = student.value.filter((user) => {
        return typeof user.student !== 'undefined'
      })
      console.log(student.value);
    });
};
const fetchStudentInfo = async () => {
  return fetch('https://localhost:81/students')
    .then((response) => response.json())
    .then((data) => {
      studentInfo.value = data["hydra:member"];
      console.log(studentInfo.value);
    });
};
const editStudent = (id: string) => {
  router.push('/admin/student/Edit/' + id)
}
const addStudent = () => {
  router.push('/admin/student/Add/Student')
}
const deleteStudent = async (id: string) => {
  const response = await fetch('https://localhost:81/students/' + id, {
    method: 'DELETE',
    headers: {
      'accept': 'application/ld+json',
    },
  });
  await fetchStudent();
};
const changeStatus = async (id: string) => {
  const response = await fetch('https://localhost:81/students/' + id + '/edit_status', {
    method: 'PATCH',
    headers: {
      'accept': 'application/ld+json',
      'Content-type': 'application/merge-patch+json',
    },
    body: '{}'
  });
  const data = await response.json()
  console.log('Success:', data)
  await fetchStudent();
};
</script>
<template>
  <!--    Tableau qui liste les auto école -->
  <div class=" q-pt-lg window-height window-width row justify-center ">
    <q-markup-table>
      <thead>
      <tr>
        <th colspan="13">
          <div class="text-h4 q-ml-md">Liste des étudiants
            <q-btn
            class="float-right"
            color="positive"
            text-color="white"
            icon="add"
            @click="addStudent()"
          />
          </div>
        </th>
      </tr>
      <tr>
        <th>Id</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Email</th>
        <th>Statut</th>
        <th>Nombre d'heures effectuées</th>
        <th>Url certification code</th>
        <th>Url CNI</th>
        <th>Editer</th>
        <th>Supprimer</th>
        <th>Valider le status</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(stud, index) in student.value" :key="index">
        <td>{{ stud.id }}</td>
        <td>{{ stud.lastname }}</td>
        <td>{{ stud.firstname }}</td>
        <td>{{ stud.email }}</td>
        <td v-if="stud.status === true">
          <span> Validé </span>
        </td>
        <td v-else>
          <span> En attente </span>
        </td>
        <td>{{ stud.nb_hour_done }}</td>
        <td v-if="stud.url_code_certification">
          <span>  <q-icon color="positive" name="thumb_up"/> </span>
        </td>
        <td v-else>
          <span> <q-icon color="negative" name="thumb_down"/> </span>
                   <q-img
                     :src="'../' + stud.url_code_certification"
                     :alt="stud.url_code_certification"
                     spinner-color="#8785A2"
                     style="height: 140px; max-width: 150px"
                   />
        </td>
        <td v-if="stud.url_cni">
          <span>  <q-icon color="positive" name="thumb_up"/> </span>
        </td>
        <td v-else>
          <span> <q-icon color="negative" name="thumb_down"/> </span>
                   <q-img
                     :src="'../' + stud.url_cni"
                     :alt="stud.url_cni"
                     spinner-color="#8785A2"
                     style="height: 140px; max-width: 150px"
                   />
        </td>
        <td>
          <q-btn
            color="warning"
            text-color="white"
            icon="edit"
            @click="editStudent(stud.id)"
          />
        </td>
        <td>
          <q-btn
            color="negative"
            text-color="white"
            icon="delete"
            @click="deleteStudent(stud.id)"
          />
        </td>
        <td v-if="stud.status === true">
          <q-btn
            color="secondary"
            text-color="white"
            icon="sync"
            disabled
            @click="changeStatus(stud.id)"
          />
        </td>
        <td v-else>
          <q-btn
            color="warning"
            text-color="white"
            icon="sync"
            @click="changeStatus(stud.id)"
          />
        </td>
      </tr>
      </tbody>
    </q-markup-table>
  </div>
</template>
<style>
</style>
