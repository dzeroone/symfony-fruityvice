<script setup>
import { reactive, ref } from 'vue';
import Container from '../components/Container.vue';

const formData = reactive({
  email: '',
  password: '',
  c_password: ''
})

const formRef = ref(null);
const submitError = ref('');

const rules = reactive({
  email: [
    {
      required: true,
      type: 'email'
    },
    {
      trigger: 'blur',
      validator: async (rule, value, callback) => {
        if(!value) {
          callback(new Error('Email is required'))
          return;
        }
        const res = await fetch(`${baseUrl}/api-check-email`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            email: value
          })
        })
        let data = await res.json();
        if(data.exists) {
          callback(new Error('Email already exists'))
        }else{
          callback()
        }
      }
    }
  ],
  password: [
    {
      trigger: 'blur',
      validator: async (rule, value, callback) => {
        if(!value) {
          callback(new Error('Password is required'))
        }
      }
    }
  ],
  c_password: [
    {
      trigger: 'blur',
      validator: (rule, value, callback) => {
        if(!value) {
          callback(new Error('Confirm password is required'))
          return;
        }
        if(value != formData.password) {
          callback(new Error('Confirm password is not matched!'))
          return;
        }
        callback();
      }
    }
  ]
})

const onSubmit = async (formEl) => {
  if (!formEl) return
  await formEl.validate(async (valid, fields) => {
    if (valid) {
      const res = await fetch(`${baseUrl}/api-signup`, {
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
        submitError.value = 'Signup failed!!'
      }
    } else {
      return false;
    }
  })
}

</script>

<template>
  <Container>
    <main class="form-wrapper">
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
          <el-form-item label="Confirm Password" prop="c_password">
            <el-input type="password" v-model="formData.c_password" />
          </el-form-item>
          <el-form-item :error="submitError" :show-message="!!submitError"></el-form-item>
          <el-form-item>
            <div class="form-actions">
              <el-button type="primary" @click="onSubmit(formRef)">Sign up</el-button>
              <el-link type="success" href="/signin">Existing user?</el-link>
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
.form-wrapper{
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