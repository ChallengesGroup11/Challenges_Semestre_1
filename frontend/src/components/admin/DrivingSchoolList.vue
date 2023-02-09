<script setup lang="ts">
import { onMounted, reactive } from 'vue';
const drivingSchool = reactive({ value: [] });

onMounted(async () => {
  await fetchDrivingSchool();
});

const fetchDrivingSchool = async () => {
  return fetch('https://localhost/driving_schools')
    .then((response) => response.json())
    .then((data) => {
      drivingSchool.value = data["hydra:member"];
      console.log(drivingSchool.value);
    });
};

console.log(drivingSchool);
</script>

<template>
<!--    Tableau qui liste les auto école -->
  <div class=" relative-position">
  <q-markup-table>
    <thead>
    <tr>
      <th colspan="11">


          <div class="text-h4 q-ml-md">Liste des auto-écoles</div>

      </th>
    </tr>
    <tr>
      <th>Id</th>
      <th>Nom</th>
      <th>Adresse</th>
      <th>Code Postal</th>
      <th>Ville</th>
      <th>Numéro de téléphone</th>
      <th>Numéro de SIRET</th>
      <th> Kbis </th>
      <th> Directeur </th>
      <th> Editer</th>
      <th> Supprimer</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="(driving, index) in drivingSchool.value" :key="index">
      <td>{{ driving.id }}</td>
      <td>{{ driving.name }}</td>
      <td>{{ driving.address }}</td>
      <td>{{ driving.zipcode }}</td>
      <td>{{ driving.city }}</td>
      <td>{{ driving.phoneNumber }}</td>
      <td>{{ driving.siret }}</td>
      <td>{{ driving.kbis }}</td>
      <td v-if="driving.director">{{ driving.director.userId.firstname }}</td>
      <td v-else>Non renseigné</td>
      <td>
        <q-btn
          color="primary"
          text-color="white"
          label="Editer"
          icon="edit"
          @click="editDrivingSchool(driving.id)"
        />
      </td>
      <td>
        <q-btn
          color="primary"
          text-color="white"
          label="Supprimer"
          icon="delete"
          @click="deleteDrivingSchool(driving.id)"
        />
      </td>
    </tr>
    </tbody>
  </q-markup-table>

  </div>


</template>


<style>




</style>
