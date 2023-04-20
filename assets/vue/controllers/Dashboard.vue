<script setup>
import Layout from '../components/Layout.vue';
import CardFruitDashboard from '../components/CardFruitDashboard.vue';

const props = defineProps({
  user: Object,
  fruits: Array,
  totalNutrition: Number
})
</script>

<template>
  <Layout :user="user">
    <h2>Dashboard</h2>
    <el-card>
      Total Nutrition of all fruits is {{ totalNutrition.toFixed(2) }}
    </el-card>
    <h3>Liked fruits - </h3>
    <el-row :gutter="20">
      <el-col
        v-for="(fruit, index) in fruits" :key="fruit.id"
        :span="8"
        :xs="12"
        class="fruit-card-wrapper"
      >
        <CardFruitDashboard :fruit="fruit" />
      </el-col>
    </el-row>
  </Layout>
</template>