<script setup lang="ts">
const router = useRouter()
import { onMounted, reactive } from 'vue';
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

const Alldirectors = reactive({ value: [] })
const selectedDirector = ref('')

const drivingSchool = reactive({
  id: "",
  name: "",
  address: "",
  zipcode: "",
  city: "",
  phoneNumber: "",
  siret: "",
  kbis: "",
  director: ""
});

onMounted(async () => {
  const { params } = useRoute();
  const { id } = params;
  await fetchOneDrivingSchool(id);
  await fetchDirector()
});

const fetchDirector = async () => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/directors`, {
    headers: {
      accept: 'application/ld+json',
      Authorization: 'Bearer ' + localStorage.getItem('token'),
    },
  })
    .then((response) => response.json())
    .then((data) => {
      // directors.value = data['hydra:member'].filter((item) => item.drivingSchoolId === null);
      //filter directors who have not a driving school
      Alldirectors.value = data['hydra:member']


      for (let i = Alldirectors.value.length - 1; i >= 0; i--) {
        const element = Alldirectors.value[i];
        if (element.drivingSchoolId) {
          Alldirectors.value.splice(i, 1);
        }
      }
    })
}

const fetchOneDrivingSchool = async (id: string | string[]) => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/driving_schools/` + id,
    {
      headers: {
        'Authorization': 'Bearer ' + localStorage.getItem('token'),
      }
    }
  )
    .then((response) => response.json())
    .then((data) => {
      drivingSchool.id = data.id;
      drivingSchool.name = data.name;
      drivingSchool.address = data.address;
      drivingSchool.zipcode = data.zipcode;
      drivingSchool.city = data.city;
      drivingSchool.phoneNumber = data.phoneNumber;
      drivingSchool.siret = data.siret;
      drivingSchool.kbis = data.kbis;
      if (data.director) {
        drivingSchool.director = data.director
        selectedDirector.value = data.director
      }
    })
    .catch((error) => {
      viewNotif("thumb_down", "red", "Une erreur est survenue", "white", "top-right")
    })
};

const onSubmit = async (id: string) => {
  console.log(drivingSchool)
  if (drivingSchool.director) {
    drivingSchool.director = "/directors/" + drivingSchool.director.id
  }else{
    delete drivingSchool.director;
  }
  const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/driving_schools/` + id, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
    },
    body: JSON.stringify(drivingSchool),
  });
  if (response.status === 400 || response.status === 500) {
    viewNotif("thumb_down", "red", "Une erreur est survenue", "white", "top-right")
  }
  if (response.status === 200) {
    viewNotif("thumb_up", "green", "L'auto école à bien été modifié", "white", "top-right")
    await router.push('/admin/drivingSchool')
  }
};

function isset(drivingSchoolId: any) {
  throw new Error('Function not implemented.');
}
</script>


<template>
  <q-page class="bg-light-grey  items-center">
    <div class=" q-mt-sl  row justify-center">
      <div class="q-pa-md">
        <h2 class="text-h5 w-100 q-mb-xl">Editer une auto-école </h2>
        <q-form @submit="onSubmit(drivingSchool.id)" class="q-gutter-md">
          <q-input v-model="drivingSchool.name" label="Nom de l'école de conduite" filled lazy-rules
            :rules="[val => val && val.length > 0 || 'Veuillez écrire quelque chose']" />
          <q-input filled v-model="drivingSchool.address" label="Adresse *" hint="Address" lazy-rules
            :rules="[val => val && val.length > 0 || 'Veuillez écrire quelque chose']" />
          <q-input filled v-model="drivingSchool.zipcode" label="Code postal " hint="Zipcode" lazy-rules
            :rules="[val => val && val.length > 0 || 'Veuillez écrire quelque chose']" />
          <q-input filled v-model="drivingSchool.city" label="Ville" hint="City" lazy-rules
            :rules="[val => val && val.length > 0 || 'Veuillez écrire quelque chose']" />
          <q-input filled v-model="drivingSchool.phoneNumber" label="Numéro de téléphone" hint="Phone number" lazy-rules
            :rules="[val => val && val.length > 0 || 'Veuillez écrire quelque chose']" />
          <q-input filled v-model="drivingSchool.siret" label="Numéro de siret" hint="Siret" lazy-rules
            :rules="[val => val && val.length > 0 || 'Veuillez écrire quelque chose']" />
          <div v-if="selectedDirector">
            <select disabled v-model="selectedDirector" placeholder="Choisir un directeur">
              <option selected v-if="selectedDirector != ''" :value="selectedDirector">{{
                selectedDirector.userId?.firstname }}</option>
            </select>
          </div>
          <div v-else>
            <select v-model="drivingSchool.director" placeholder="Choisir un directeur">
              <option value="">Choisir un directeur</option>
              <option v-for="director in Alldirectors.value" :value="director">
                {{ director.userId.firstname }}
              </option>
            </select>
          </div>
          <div>
            <q-btn label="Valider" type="submit" color="primary" />
          </div>
        </q-form>
      </div>
    </div>
  </q-page>
</template>


<style></style>


<route lang="yaml">
meta:
  layout: admin
  requiresAuth: true
  roles: admin
</route>

