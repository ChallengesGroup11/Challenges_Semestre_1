<script setup lang="ts">
import { reactive, defineComponent } from "vue";
import moment from 'moment';
import { log } from "console";

const router = useRouter()

const currentUser = reactive({ value: [] });

onMounted(async () => {
  if (localStorage.getItem('token') == null) {
    await router.push('/auth')
  } else {
    await getUser()
  }
});

const getUser = async () => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/me`, {
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
    }
  })
    .then((response) => response.json())
    .then((data) => {
      console.log(data);
      currentUser.value = data;
    })
    .catch((error) => {
      console.error("Error:", error);
    });
};


const editer = (id: string) => {
  router.push('/director/edit/' + id)
}

const addKbisAndSiret = (id: string) => {
  router.push('/director/add/createdrivingschool/' + id)
}

</script>


<template>
  <div>
    <h1>Directeur</h1>
    <div class="q-pa-md  ">
      <div class=" row ">
        <q-card class="my-card q-ma-lg col">
          <q-card-section>
            <div class="text-h4"> {{ currentUser.value?.firstname }} {{ currentUser.value?.lastname }} <q-btn size="sm"
                round color="warning" @click="editer(currentUser.value?.id)" icon="edit"></q-btn></div>
            <div class="text-left">
              <div class="text-h6">Mon email : {{ currentUser.value?.email }}</div>
              <div class="text-center q-mt-lg" v-if="!currentUser.value?.director?.drivingSchoolId">
                <q-btn label="Ajouter votre auto-ecole" push size="md" @click='addKbisAndSiret(currentUser.value.id)'
                  color="positive" icon="add" />
              </div>

              <div v-if="currentUser.value.director?.drivingSchoolId" class="text-h6">
                Nom de l'auto-ecole :
                <span v-if="currentUser.value.director?.drivingSchoolId?.name">
                  <p>{{ currentUser.value.director.drivingSchoolId.name }}</p>
                </span>
              </div>

              <div v-if="currentUser.value.director?.drivingSchoolId" class="text-h6">
                Adresse de l'auto-ecole :
                <span v-if="currentUser.value.director?.drivingSchoolId?.address">
                  <p>{{ currentUser.value.director.drivingSchoolId.address }}</p>
                </span>
              </div>

              <div v-if="currentUser.value.director?.drivingSchoolId" class="text-h6">
                Ville de l'auto-ecole :
                <span v-if="currentUser.value.director?.drivingSchoolId?.city">
                  <p>{{ currentUser.value.director.drivingSchoolId.city }}</p>
                </span>
              </div>

              <div v-if="currentUser.value.director?.drivingSchoolId" class="text-h6">
                Téléphone de l'auto-ecole :
                <span v-if="currentUser.value.director?.drivingSchoolId?.phoneNumber">
                  <p>{{ currentUser.value.director.drivingSchoolId.phoneNumber }}</p>
                </span>
              </div>

              <div v-if="currentUser.value.director?.drivingSchoolId" class="text-h6">
                N° SIRET
                <span v-if="currentUser.value.director?.drivingSchoolId?.siret">
                  <q-icon style="vertical-align: text-top" size="sm" color="positive" name="check" />
                </span>
              </div>
              <div v-if="currentUser.value.director?.drivingSchoolId" class="text-h6">
                N° Kbis
                <span v-if="currentUser.value.director?.drivingSchoolId?.contentUrl">
                  <q-icon style="vertical-align: text-top" size="sm" color="positive" name="check" />
                </span>
              </div>
            </div>
          </q-card-section>
        </q-card>

        <!-- <q-card class="my-card q-ma-lg  col ">
          <q-card-section class="vertical-middle">
            <div class="text-h4">Nombre d'heure effectué(e)</div>
            <div v-if="currentUser.value.director" class="vertical text-h1">
              <span v-if="currentUser.value.director.nbHourDone !== null">
                {{ currentUser.value.director.nbHourDone }}
              </span>
              <span v-else class="vertical text-h1"> 0</span>
            </div>

          </q-card-section>
        </q-card> -->
      </div>
    </div>
  </div>
</template>


<route lang="yaml">
meta:
  layout: director
  requiresAuth: true
  roles: director
</route>
