<template>
  <div class="modal-wrapper"
       @click="handleClickOutside"
       v-show="show">
    <div class="modal-box">
      <i class="close fi fi-rr-cross-small" @click="hide"></i>
      <div class="modal-header">
        <slot name="header"></slot>
      </div>

      <div class="modal-body">
        <slot name="body"></slot>
      </div>

      <div class="modal-footer">
        <slot name="footer"></slot>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      show: false
    }
  },
  mounted() {
    document.addEventListener('keyup', e => {
      if (e.key == "Escape") {
        this.hide()
      }
    })
  },
  methods: {
    handleClickOutside(e) {
      if (!e.target.closest('.modal-box')) {
        this.hide()
      }
    },
    hide() {
      this.show = false
      this.$emit('hidded')
    },
    open() {
      this.show = true
    }
  }
}
</script>

<style lang="scss">
.modal-wrapper {
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  background-color: rgba(32, 32, 32, .8);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-box {
  width: 500px;
  min-height: 300px;
  background-color: #fff;
  border-radius: 5px;
  max-height: 80vh;
  box-shadow: 0px 2px 10px 0px rgba(32, 32, 32, .8);
  display: flex;
  flex-direction: column;
  position: relative;

  .close {
    position: absolute;
    padding: 10px;
    top: 0;
    right: 0;
    line-height: 100%;
    cursor: pointer;
  }
}

.modal-header, .modal-body, .modal-footer {
  padding: 15px;
}

.modal-header {
  font-weight: 600;
  font-size: 20px;
}

.modal-body {
  flex: 1;
  font-size: 16px;
}
</style>