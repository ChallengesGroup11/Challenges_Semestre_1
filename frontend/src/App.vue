<script setup lang="ts">
useHead({
  title: 'Default Title',
  meta: [{ name: 'description', content: 'Default Content' }],
  link: [
    {
      rel: 'icon',
      type: 'image/svg+xml',
      href: computed(() => (preferredDark.value ? '/icons/favicon-dark.svg' : '/icons/favicon.svg')),
    },
  ],
})
</script>

<template>
  <RouterView />
</template>
