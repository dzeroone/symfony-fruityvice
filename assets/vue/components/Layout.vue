<script setup>
import Container from './Container.vue';
const props = defineProps({
  user: {
    type: Object
  }
})
console.log('uu', props.user)
</script>

<template>
  <div>
    <Container>
      <div class="navbar"> 
        <img src="/images/fruits-pear-svgrepo-com.svg" alt="Logo" class="brand-logo"/><div class="brand">FruityFun</div>
        <div class="navbar-right-content">
          <el-row :gutter="10" v-if="props.user">
            <el-col span="auto">{{ props.user.email }}</el-col>
            <el-col span="auto">
              <el-link href="/dashboard" type="success">Dashboard</el-link>
            </el-col>
            <el-col span="auto"><el-link href="/signout" type="success">Signout</el-link></el-col>
          </el-row>
          <div v-else>
            <el-link href="/signin" type="success">Signin</el-link>
          </div>
        </div>
      </div>
    </Container>
    <Container>
      <main class="main-content">
        <slot />
      </main>
    </Container>
  </div>
</template>
<style scoped>
.navbar {
  height: 4rem;
  display: flex;
  align-items: center;
  flex-direction: row;
  border-bottom: 1px solid #e0e0e0;
}
.navbar-right-content{
  margin-left: auto;
}
.brand-logo{
  width: 3rem;
}
.brand{
  font-size: 1.5rem;
  font-weight: 600;
  margin-left: 1rem;
}
.main-content {
  padding: 1rem 0;
}
</style>