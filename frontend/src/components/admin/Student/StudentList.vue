<script setup lang="ts">
const router = useRouter()
import { onMounted, reactive } from 'vue'
import { useQuasar } from "quasar"

const $q = useQuasar()
const viewNotif = (icon: any, color: string, message: string, textColor: string, position: any) => {
  $q.notify({
    icon,
    color,
    message,
    textColor,
    position,
  })
}
const students = reactive({ value: [] })

onMounted(async () => {
  await fetchStudent()
})

const fetchStudent = async () => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/users`, {
    headers: {
      accept: 'application/ld+json',
      Authorization: 'Bearer ' + localStorage.getItem('token'),
    },
  })
    .then((response) => response.json())
    .then((data) => {
      students.value = data['hydra:member'].filter((student: any) => student.roles[0] !== 'ROLE_DIRECTOR' && student.roles[0] !== 'ROLE_ADMIN' && student.roles[0] !== 'ROLE_MONITOR')
      console.log(students.value)
    })
}

const editStudent = (id: string) => {
  router.push('/admin/Student/Edit/' + id)
}

const addStudent = () => {
  router.push('/admin/Student/Add/Student')
}

const deleteStudent = async (id: string) => {
  const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/users/` + id, {
    method: 'DELETE',
    headers: {
      accept: 'application/ld+json',
      Authorization: 'Bearer ' + localStorage.getItem('token'),
    },
  })
  if (response.status === 400) {
    viewNotif("thumb_down", "red", "L'étudiant ne peut pas être supprimé", "white", "top-right")
  }
  if (response.status === 500) {
    viewNotif("thumb_down", "red", "Une erreur est survenue", "white", "top-right")
  }
  if (response.status === 204) {
    viewNotif("thumb_up", "green", "L'étudiant à bien été supprimé", "white", "top-right")
  }
  await fetchStudent()
}

const changeStatus = async (id: string) => {
  console.log(id)
  const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/users/` + id + '/edit_status', {
    method: 'PATCH',
    headers: {
      accept: 'application/ld+json',
      'Content-type': 'application/merge-patch+json',
      Authorization: 'Bearer ' + localStorage.getItem('token'),
    },
    body: '{}',
  })
  if (response.status === 400) {
    viewNotif("thumb_down", "red", "L'étudiant  ne peut pas être modifié", "white", "top-right")
  }
  if (response.status === 500) {
    viewNotif("thumb_down", "red", "Une erreur est survenue", "white", "top-right")
  }
  if (response.status === 201) {
    viewNotif("thumb_up", "green", "L'étudiant  à bien été modifié", "white", "top-right")
  }
  await fetchStudent()
}
</script>

<template>
  <!--    Tableau qui liste les auto école -->
  <div class="q-pt-lg window-height window-width row justify-center">
    <q-markup-table>
      <thead>
        <tr>
          <th colspan="13">
            <div class="text-h4 q-ml-md" color="primary">
              Liste des students
              <q-btn class="float-right" color="positive" text-color="white" icon="add" @click="addStudent()" />
            </div>
          </th>
        </tr>
        <tr>
          <th>Id</th>
          <th>Prénom</th>
          <th>Nom</th>
					<th>Email</th>
          <th>Status</th>
          <th>Editer</th>
          <th>Supprimer</th>
          <th>Valider le status</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(student, index) in students.value" :key="index">
          <td>{{ student.id }}</td>
          <td>{{ student.firstname }}</td>
          <td>{{ student.lastname }}</td>
          <td>{{ student.email }}</td>
          <td v-if="student.status === true">
            <span> Valider </span>
          </td>
          <td v-else>
            <span> En attente </span>
          </td>
          <td>
            <q-btn color="warning" text-color="white" icon="edit" @click="editStudent(student.id)" />
          </td>
          <td>
            <q-btn color="negative" text-color="white" icon="delete" @click="deleteStudent(student.id)" />
          </td>
          <td v-if="student.status === true">
            <q-btn color="secondary" text-color="white" icon="sync" disabled @click="changeStatus(student.id)" />
          </td>
          <td v-else>
            <q-btn color="warning" text-color="white" icon="sync" @click="changeStatus(student.id)" />
          </td>
        </tr>
      </tbody>
    </q-markup-table>
  </div>
</template>

<style>

.text-h4 {
  color: primary;
}

</style>
