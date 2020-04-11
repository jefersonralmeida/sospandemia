<template>
  <div>
    <h3 style="text-align: center;">
      <span class="error-code">{{ errorCode }}</span> Ops! {{ errorTitle }}
    </h3>
    <hr/>
    <p class="error-desc m-4">
      {{ errorMessage }}
    </p>
  </div>
</template>

<script>
export default {
  name: 'Generic Error',
  props: ['errorCode', 'errorTitle', 'errorMessage']
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.error-code {
  color: #DDD;
}
.error-desc {
  text-align: center;
  color: #999;
}
</style>
