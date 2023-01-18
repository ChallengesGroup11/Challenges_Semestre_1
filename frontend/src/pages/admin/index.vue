<script setup lang="ts"></script>

<template>
  <div class="container">
    <h1>Admin</h1>
  </div>
</template>

<route lang="yaml">
meta:
  layout: admin
  requiresAuth: true
  roles: admin
</route>
