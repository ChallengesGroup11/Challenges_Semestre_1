<script setup lang="ts">
import * as R from "ramda"
import { useStoreUser } from "../../../../stores/user"
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
const emit = defineEmits<{
  (e: "on-save"): void
}>()

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

    if (state.monitor.id === "") {
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
      console.log("here")
      const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/director/create_monitor`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization: "Bearer " + localStorage.getItem("token"),
        },
        body: JSON.stringify(requestData),
      })
      if (response.status === 201) {
        emit("on-save")
        viewNotif("thumb_up", "green", "Le moniteur à bien été créé", "white", "top-right")
        state.isShownModal = false
      }
      if (response.status === 400) {
        viewNotif("thumb_down", "red", "Le moniteur ne peut pas être créer", "white", "top-right")
        return
      }
      if (response.status === 500) {
        viewNotif("thumb_down", "red", "Une erreur est survenue", "white", "top-right")
        return
      }
    } else {
      const requestData = {
        id: state.monitor.id,
        firstname: state.monitor.firstname,
        lastname: state.monitor.lastname,
        email: state.monitor.email,
        password: state.monitor.password,
        roles: ["ROLE_MONITOR"],
        status: false,
        createBy: "director",
        drivingSchoolId: drivingSchoolId,
      }
      const response = await fetch(`${import.meta.env.VITE_CHALLENGE_URL}/users/` + requestData.id, {
        method: "PATCH",
        headers: {
          "Content-Type": "application/merge-patch+json",
          Authorization: "Bearer " + localStorage.getItem("token"),
        },
        body: JSON.stringify(requestData),
      })
      if (response.status === 200) {
        emit("on-save")
        viewNotif("thumb_up", "green", "Le moniteur à bien été modifié", "white", "top-right")
        state.isShownModal = false
      }
      if (response.status === 400) {
        viewNotif("thumb_down", "red", "Le moniteur ne peut pas être créer", "white", "top-right")
        return
      }
      if (response.status === 500) {
        viewNotif("thumb_down", "red", "Une erreur est survenue", "white", "top-right")
        return
      }
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
