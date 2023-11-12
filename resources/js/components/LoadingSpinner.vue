<template>
  <div class="text-center p-5">
    <div>
      <div
        class="v-pulse v-pulse1"
        :style="[spinnerStyle,spinnerDelay1]"
      />
      <div
        class="v-pulse v-pulse2"
        :style="[spinnerStyle,spinnerDelay2]"
      />
      <div
        class="v-pulse v-pulse3"
        :style="[spinnerStyle,spinnerDelay3]"
      />
    </div>
  </div>
</template>
<script setup>
const spinnerStyle = {
  backgroundColor: '#4368fa',
  width: '15px',
  height: '15px',
  margin: '2px',
  borderRadius: '100%',
  display: 'inline-block',
  animationName: 'v-pulseStretchDelay',
  animationDuration: '0.75s',
  animationIterationCount: 'infinite',
  animationTimingFunction: 'cubic-bezier(.2,.68,.18,1.08)',
  animationFillMode: 'both'
};
const spinnerDelay1 = {
  animationDelay: '0.12s'
};
const spinnerDelay2 = {
  animationDelay: '0.24s'
};
const spinnerDelay3 = {
  animationDelay: '0.36s'
};
</script>
<style>
@-webkit-keyframes v-pulseStretchDelay
{
    0%,
    80%
    {
        -webkit-transform: scale(1);
                transform: scale(1);
        -webkit-opacity: 1;
                opacity: 1;
    }
    45%
    {
        -webkit-transform: scale(0.1);
                transform: scale(0.1);
        -webkit-opacity: 0.7;
                opacity: 0.7;
    }
}
@keyframes v-pulseStretchDelay
{
    0%,
    80%
    {
        -webkit-transform: scale(1);
                transform: scale(1);
        -webkit-opacity: 1;
                opacity: 1;
    }
    45%
    {
        -webkit-transform: scale(0.1);
                transform: scale(0.1);
        -webkit-opacity: 0.7;
                opacity: 0.7;
    }
}
</style>
