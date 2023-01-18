<script setup lang="ts"></script>

<template>
  <div class="container">
    <h1>Student</h1>
  </div>
</template>

<route lang="yaml">
meta:
  layout: student
  requiresAuth: true
  roles: student
</route>
