<template>
  <Modal ref="modal">
    <template v-slot:header>
      Desenvolvedor
    </template>
    <template v-slot:body>
      <div class="list-skeleton-loading" v-if="!developer">
        <div class="item"></div>
        <div class="item"></div>
        <div class="item"></div>
        <div class="item"></div>
      </div>
      <div v-if="developer">
        <p class="mb-1"><b>Nome:</b> {{ developer.nome }}</p>
        <p class="mb-1"><b>Idade:</b> {{ developer.idade }}</p>
        <p class="mb-1"><b>Data de nascimento:</b> {{ developer.datanascimento }}</p>
        <p class="mb-1"><b>Sexo:</b> {{ getSexo(developer.sexo) }}</p>
        <p><b>Hobby:</b> {{ developer.hobby }}</p>
      </div>
    </template>
    <template v-slot:footer>
      <div class="flex flex-end">
        <button type="button" class="btn" @click="hide">Fechar</button>
      </div>
    </template>
  </Modal>
</template>

<script>
import Modal from './Modal';
import axios from "axios";

export default {
  data() {
    return {
      developer: undefined
    }
  },
  methods: {
    hide() {
      this.developer = undefined;
      this.$refs.modal.hide()
    },
    show(developer) {
      this.$refs.modal.open()

      axios.get(`http://localhost/developers/${developer.id}`).then(({data}) => {
        this.developer = data.data
      }).catch(err => {
        console.error(err)
      })
    },
    getSexo(sexo) {
      let sexoString

      switch (sexo) {
        case 'M':
          sexoString = 'Masculino'
          break
        case 'F':
          sexoString = 'Feminino'
          break
        case 'O':
          sexoString = 'Outro'
          break
      }

      return sexoString
    }
  },
  components: {
    Modal
  }
}
</script>