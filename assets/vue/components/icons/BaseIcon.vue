<script setup>
const props = defineProps({
  width: {
    type: [Number, String],
    default: 18
  },
  height: {
    type: [Number, String],
    default: 18
  },
  viewBox: {
    type: String,
    default: "0 0 18 18"
  },
  iconColor: {
    type: String,
    default: 'currentColor'
  }
})
const { width, height, iconColor, iconName } = props;
</script>

<template>
  <svg xmlns="http://www.w3.org/2000/svg"
    :width="width"
    :height="height"
    :viewBox="viewBox"
    :aria-labelledby="iconName"
    role="presentation"
  >
    <g :fill="iconColor">
      <slot />
    </g>
  </svg>
</template>