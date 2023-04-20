/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
import { registerVueControllerComponents } from '@symfony/ux-vue';
registerVueControllerComponents(require.context('./vue/controllers', true, /\.vue$/));

// start the Stimulus application
import './bootstrap';

import { createApp } from 'vue'
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
import './styles/app.css';
import { createRouter, createWebHashHistory } from 'vue-router';

const app = createApp();
app.use(ElementPlus)

export default app;

document.addEventListener('vue:before-mount', (event) => {
  const {
      componentName, // The Vue component's name
      component, // The resolved Vue component
      props, // The props that will be injected to the component
      app, // The Vue application instance
  } = event.detail;

  // Example with Vue Router
  // const router = createRouter({
  //   history: createWebHashHistory(),
  //   routes: []
  // });

  // app.use(router);
});