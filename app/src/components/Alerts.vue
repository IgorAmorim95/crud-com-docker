<template>
  <div class="alert-wrapper">
    <div class="alert">
      <p v-text="this.$store.alert.state.message">Essa é uma mensagem de alerta de teste</p>
      <i class="close fi fi-rr-cross-small" @click="hide"></i>
    </div>
  </div>
</template>

<script>
export default {
  methods: {
    hide() {
      console.log('teste')
    }
  }
}
</script>

<style lang="scss">
@import '@/assets/base.scss';

.alert-wrapper {
  position: fixed;
  bottom: 15px;
  left: 15px;
  max-width: 250px;
}

.alert {
  position: relative;
  width: 100%;
  border-radius: 5px;
  background: #ccc;
  color: #222;
  font-size: 14px;
  padding: 10px 15px;
  line-height: 140%;
  box-shadow: 0px 2px 5px 0px rgba(32, 32, 32, .1), 0px 0px 5px 0px rgba(32, 32, 32, .1);

  &:hover .close {
    opacity: 1;
    visibility: visible;
  }

  .close {
    transition: .2s;
    opacity: 0;
    visibility: hidden;
    position: absolute;
    top: 0;
    right: 0;
    padding: 5px;
    cursor: pointer;
  }
}
</style>