<script setup>
  const props = defineProps({
    fruit: Object
  })
</script>

<template>
  <el-card :body-style="{ padding: '0px' }">
    <template #header>
      <div class="card-header">
        <div>
          <h3 class="card-header-title">{{ fruit.name }}</h3>
        </div>
      </div>
    </template>
    <div style="padding: var(--el-card-padding)">
      <div>Order: <span class="scientific-name">{{ fruit.taxo_order }}</span></div>
      <div>Family: <span class="scientific-name">{{ fruit.family }}</span></div>
      <div>Genus: <span class="scientific-name">{{ fruit.genus }}</span></div>
      <br />
      <div>Nutritions -</div>
      <ul>
        <li>carbohydrates: {{ fruit.nutrition.carbohydrates }}</li>
        <li>protein: {{ fruit.nutrition.protein }}</li>
        <li>fat: {{ fruit.nutrition.fat }}</li>
        <li>calories: {{ fruit.nutrition.calories }}</li>
        <li>sugar: {{ fruit.nutrition.sugar }}</li>
      </ul>
    </div>
  </el-card>
</template>

<style scoped>
.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.card-header-title{
  font-size: 1.2rem;
  padding: 0;
  margin: 0;
}
.scientific-name {
  font-style: italic;
  opacity: 0.8;
}
.btn-like{
  color: #cc4444;
}
.btn-like.liked,
.btn-like:hover{
  background-color: #cc4444;
  color: #fff;
  border-color: #ac4444;
}
.btn-like:active{
  background-color: #943030;
}
.btn-like.liked:hover{
  background-color: transparent;
  color: #cc4444;
  border-color: var(--el-button-border-color);
}
.btn-like.liked:active{
  background-color: transparent;
}
</style>