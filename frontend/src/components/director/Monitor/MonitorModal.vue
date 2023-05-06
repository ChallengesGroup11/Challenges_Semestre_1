<script setup lang="ts">
const state = reactive({
  isShownModal: false,
})

const fn = {
  onClickSaveMonitor: async () => {},
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
          <div class="q-pa-md" style="max-width: 300px"></div>

          <div class="q-pa-md" style="max-width: 300px"></div>
        </q-form>
      </q-card-section>

      <q-card-actions align="right">
        <q-btn flat label="Enregistrer" color="primary" @click="fn.onClickSaveMonitor" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>
