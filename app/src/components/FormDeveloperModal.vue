<template>
  <Modal ref="modal" @hidded="reset">
    <template v-slot:header>
      Adicionar Desenvolvedor
    </template>
    <template v-slot:body>
      <div class="list-skeleton-loading" v-if="loading">
        <div class="item"></div>
        <div class="item"></div>
        <div class="item"></div>
        <div class="item"></div>
      </div>
      <form class="form-style" v-if="!loading">
        <div class="form-group" :class="{'has-error': errors.hasOwnProperty('nome')}">
          <label class="label-style">Nome</label>
          <input type="text" class="input-style" v-model="form.nome" placeholder="Nome do desenvolvedor">
          <p class="error-message" v-if="errors.hasOwnProperty('nome')" v-text="errors.nome[0]"></p>
        </div>
        <div class="form-group-multiple">
          <div class="form-group" :class="{'has-error': errors.hasOwnProperty('datanascimento')}">
            <label class="label-style">Data de nascimento</label>
            <input type="date" class="input-style" v-model="form.datanascimento" placeholder="Data de nascimento">
            <p class="error-message" v-if="errors.hasOwnProperty('datanascimento')"
               v-text="errors.datanascimento[0]"></p>
          </div>
          <div class="form-group" :class="{'has-error': errors.hasOwnProperty('sexo')}">
            <label class="label-style">Sexo</label>
            <select class="input-style" v-model="form.sexo">
              <option value="">Selecione uma opção</option>
              <option value="M">Masculino</option>
              <option value="F">Feminino</option>
              <option value="O">Outro</option>
            </select>
            <p class="error-message" v-if="errors.hasOwnProperty('sexo')" v-text="errors.sexo[0]"></p>
          </div>
        </div>
        <div class="form-group" :class="{'has-error': errors.hasOwnProperty('hobby')}">
          <label class="label-style">Hobby</label>
          <input type="text" class="input-style" v-model="form.hobby" placeholder="Ex.: Sair com os amigos">
          <p class="error-message" v-if="errors.hasOwnProperty('hobby')" v-text="errors.hobby[0]"></p>
        </div>
      </form>
    </template>
    <template v-slot:footer>
      <div class="flex flex-end">
        <button type="button" class="btn" @click="hide">Cancelar</button>
        <button type="button"
                class="btn btn-primary ml-3"
                @click="submit"
                :disabled="submitting"
                v-text="submitting ? 'Aguarde...' : 'Confirmar'">
        </button>
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
      submitting: false,
      loading: false,
      developer: null,
      form: {
        nome: '',
        datanascimento: '',
        sexo: '',
        hobby: ''
      },
      errors: {}
    }
  },
  methods: {
    hide() {
      this.$refs.modal.hide()
    },
    show(developer = null) {
      if (developer != null) {
        this.loading = true
      }

      if (developer != null) {
        axios.get(`http://localhost/developers/${developer.id}`).then(({data}) => {
          this.developer = data.data

          this.form.nome = data.data.nome
          this.form.datanascimento = this.$dateToFormat(data.data.datanascimento, 'y-MM-dd', 'dd/MM/y')
          this.form.sexo = data.data.sexo
          this.form.hobby = data.data.hobby
        }).catch(err => {
          console.error(err)
          alert("Algo deu errado, tente novamente")
          this.reset()
          this.$refs.modal.hide()
        }).finally(() => {
          this.loading = false
        })
      }

      this.$refs.modal.open()
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
    },
    submit() {
      this.errors = {}
      this.submitting = true

      let method = 'post'
      let url = `http://localhost/developers`

      if (this.developer != null) {
        method = 'put'
        url = `http://localhost/developers/${this.developer.id}`
      }

      axios[method](url, this.form).then(() => {
        if (this.developer != null) {
          this.$emit('updated')
        } else {
          this.$emit('created')
        }

        this.reset()
        this.$refs.modal.hide()
      }).catch(err => {
        console.error(err);
        if (err.response.status == 400) {
          this.errors = err.response.data.errors
        }
        alert('Algo deu errado ao cadastrar o desenvolvedor, tente novamente!')
      }).finally(() => {
        this.submitting = false;
      })
    },
    reset() {
      this.developer = null
      this.loading = false
      this.form = {
        nome: '',
        datanascimento: '',
        sexo: '',
        hobby: ''
      }
    }
  },
  components: {
    Modal
  }
}
</script>