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
        <div class="user-nav">
          <div class="user-nav-item">
            <el-link href="/" type="success">Home</el-link>
          </div>
          <div class="user-nav-item" v-if="props.user">
            <el-link href="/dashboard" type="success">Dashboard</el-link>
          </div>
        </div>
        <div class="navbar-right-content">
          <div class="user-nav" v-if="props.user">
            <div class="user-nav-item">{{ props.user.email }}</div>
            <div class="user-nav-item"><el-link href="/signout" type="success">Signout</el-link></div>
          </div>
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
.user-nav {
  display: flex;
  flex-direction: row;
}
.user-nav-item{
  margin-left: 0.5rem;
}
</style>