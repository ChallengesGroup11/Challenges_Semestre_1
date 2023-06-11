<script setup lang="ts">
const router = useRouter()

const currentUser = reactive({ value: [] })

const state = reactive({
  monitor: null as any,
  students: [] as any,
})

const getUser = async () => {
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/me`, {
    headers: {
      Authorization: "Bearer " + localStorage.getItem("token"),
    },
  })
    .then((response) => response.json())
    .then((data) => {
      currentUser.value = data
    })
    .catch((error) => {
      console.error("Error:", error)
    })
}

const loadData = async () => {
  if (localStorage.getItem("token") == null) {
    await router.push("/auth")
  } else {
    await getUser()
  }

  // LE QUERY BUILDER EST DANS MONITOR REPOSITORy et le controller c'est GetStudentByMonitorController
  return fetch(`${import.meta.env.VITE_CHALLENGE_URL}/monitors/` + +currentUser.value?.monitor.id + `/student`, {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
      Authorization: "Bearer " + localStorage.getItem("token"),
    },
  })
    .then((response) => response.json())
    .then((data) => {
      // group by email

      const slots = data

      const slotsGroupByEmail = slots.reduce((acc, slot) => {
        const email = slot.email
        const existingSlot = acc[email]

        if (existingSlot) {
          existingSlot.push(slot)
        } else {
          acc[email] = [slot]
        }

        return acc
      }, {})

      // sort each group by date
      const slotsGroupByEmailSorted = Object.keys(slotsGroupByEmail).reduce((acc, email) => {
        const slots = slotsGroupByEmail[email]
        const sortedSlots = slots.sort((a, b) => {
          return new Date(a.date).getTime() - new Date(b.date).getTime()
        })

        acc[email] = sortedSlots

        return acc
      }, {})

      state.students = slotsGroupByEmailSorted
      console.log(state.students)
    })
    .catch((error) => {
      console.error("Error:", error)
    })
}

loadData()
</script>
<template>
  <div>
    <q-card v-for="email in Object.keys(state.students)" key="student" class="mb-3 mt-3">
      <q-card-section>
        <q-item-label header
          >{{ state.students[email][0].firstname }} {{ state.students[email][0].lastname }} ({{ email }})</q-item-label
        >
      </q-card-section>

      <q-separator />

      <q-card-section>
        <q-item-label header>Slots</q-item-label>
        <q-item v-for="(slot, index) in state.students[email]" :key="slot.id">
          <q-item-section>
            <span> {{ index + 1 }} :</span>
            <q-item-label>{{ slot.date }}</q-item-label>
            <q-item-label>{{ slot.comment }}</q-item-label>
          </q-item-section>
        </q-item>
      </q-card-section>
    </q-card>
  </div>
</template>

<route lang="yaml">
meta:
  layout: monitor
  requiresAuth: true
  roles: user
</route>
