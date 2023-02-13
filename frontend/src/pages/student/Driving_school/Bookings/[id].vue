<script setup lang="ts">

import {reactive} from "vue";
const router = useRouter();

const driving_school = reactive({value: []});

onMounted(
  async () => {
    if (localStorage.getItem('token') == null) {
      await router.push('/auth')
    } else {
        const {params} = useRoute();
        const {id} = params;
      await getDrivingSchool(id)
    }
  }
)

const getDrivingSchool = async (id: string | string[]) => {
    return fetch("https://localhost/driving_schools/"+id,{
    headers:{
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
    }
  })
    .then((response) => response.json())
    .then((data) => {
        console.log(data)
        driving_school.value = data["hydra:member"];
    })
    .catch((error) => {
      console.error('Error:', error);
    })    
}

</script>

<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center">Disponibilités</h1>
            </div>
        </div>
    </div>
</template>

<route lang="yaml">
meta:
    layout: student
    requiresAuth: true
    roles: user
</route>    