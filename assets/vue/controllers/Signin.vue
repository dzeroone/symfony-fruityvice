<script setup>
import { reactive, ref } from 'vue';
import Container from '../components/Container.vue';

const formRef = ref(null);
const submitError = ref('');

const formData = reactive({
  email: '',
  password: ''
})

const rules = reactive({
  email: [
    {
      required: true,
      message: 'Email is required'
    },
    {
      required: true,
      type: 'email',
      message: 'Email is not in valid format'
    }
  ],
  password: [
    {
      required: true,
      message: 'Password is required'
    },
  ],
})

const onSubmit = async (formEl) => {
  if (!formEl) return
  await formEl.validate(async (valid, fields) => {
    if (valid) {
      const res = await fetch(`${baseUrl}/api-signin`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          email: formData.email,
          password: formData.password
        })
      })
      const data = await res.json();
      if(data.success) {
        location.href = baseUrl;
      }else{
        submitError.value = 'Signin failed!!'
      }
    } else {
      return false;
    }
  })
}

</script>

<template>
  <Container>
    <main class="signin-form-wrapper">
      <div class="brand-name">FrutyFun</div>
      <el-form
        ref="formRef"
        label-position="top"
        label-width="100px"
        :model="formData"
        :rules="rules"
        style="max-width: 460px"
      >
        <el-card>
          <el-form-item label="Email" prop="email">
            <el-input type="email" v-model="formData.email" />
          </el-form-item>
          <el-form-item label="Password" prop="password">
            <el-input type="password" v-model="formData.password" />
          </el-form-item>
          <el-form-item :error="submitError" :show-message="!!submitError"></el-form-item>
          <el-form-item>
            <div class="form-actions">
              <el-button type="primary" @click="() => onSubmit(formRef)">Sign in</el-button>
              <el-link type="success" href="/signup">New user?</el-link>
            </div>
          </el-form-item>
        </el-card>
      </el-form>
    </main>
  </Container>
</template>

<style scoped>
.brand-name{
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 2rem;
}
.signin-form-wrapper{
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
.form-actions{
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  width: 100%;
}
</style>