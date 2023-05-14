<script setup lang="ts">
import * as R from "ramda"
import { useStoreUser } from "../../../../stores/user"

const props = defineProps({
  monitor: {
    type: Object as () => any,
    required: false,
  },
})

watch(
  () => props.monitor,
  (newValue) => {
    state.monitor = R.clone(newValue)
    state.isShownModal = newValue !== null
  },
)

const state = reactive({
  isShownModal: props.monitor !== null,
  monitor: R.clone(props.monitor),
})

const fn = {
  onClickSaveMonitor: async () => {
    const drivingSchoolId = useStoreUser().drivingSchool.id
    debugger
    const requestData = {
      firstname: state.monitor.firstname,
      lastname: state.monitor.lastname,
      email: state.monitor.email,
      password: state.monitor.password,
      roles: ["ROLE_MONITOR"],
      status: false,
      createBy: "director",
      drivingSchoolId: drivingSchoolId,
    }
    const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/director/create_monitor`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(requestData),
    })
    debugger
    if (response.status === 201) {
      state.isShownModal = false
    }
  },
}
</script>

<template>
  <q-dialog v-model="state.isShownModal">
    <q-card style="width: 600px">
      <q-card-section>
        <div class="text-h6">Moniteur</div>
      </q-card-section>

      <q-card-section class="q-pt-none">
        <q-form>
          <div class="q-pa-md" style="max-width: 300px">
            <q-input filled v-model="state.monitor.firstname" label="Prénom" />
            <q-input filled v-model="state.monitor.lastname" label="Nom" />
            <q-input filled v-model="state.monitor.email" label="Email" />
          </div>
        </q-form>
      </q-card-section>

      <q-card-actions align="right">
        <q-btn flat label="Enregistrer" color="primary" @click="fn.onClickSaveMonitor" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>
