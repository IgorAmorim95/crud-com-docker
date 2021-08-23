<template>
  <div class="p-5">
    <h1 class="title">Desenvolvedores</h1>

    <div class="box">
      <div class="flex space-between items-center mb-3">
        <div class="flex items-center gap-1 form-style">
          <div class="form-group">
            <label class="label-style">Nome</label>
            <input @input="handleFilter" v-model="filter.nome" type="text" class="input-style" placeholder="Filtrar por nome">
          </div>

          <div class="form-group ml-1">
            <label class="label-style">Idade</label>
            <input @input="handleFilter" v-model="filter.idade" type="text" class="input-style" placeholder="Filtrar por idade">
          </div>

          <div class="form-group ml-1">
            <label class="label-style">Sexo</label>
            <select @change="handleFilter" v-model="filter.sexo" class="input-style">
              <option value="">Filtrar por sexo</option>
              <option value="M">Masculino</option>
              <option value="F">Feminino</option>
              <option value="O">Outro</option>
            </select>
          </div>

          <div class="form-group ml-1">
            <label class="label-style">Data de nascimento</label>
            <input @input="handleFilter" v-model="filter.datanascimento" type="date" class="input-style" placeholder="Filtrar por data de nascimento">
          </div>

          <div class="form-group ml-1 mb-3">
            <label class="label-style">Hobby</label>
            <input @input="handleFilter" v-model="filter.hobby" type="text" class="input-style" placeholder="Filtrar por hobby">
          </div>

          <button v-if="selecteds.length" type="button" class="btn-small ml-1" @click="deleteMultipleDevelopers">
            <i class="fi fi-rr-trash"></i>
          </button>
        </div>

        <button type="button" @click="handleAdd()" class="btn btn-primary">
          <i class="fi fi-rr-add"></i>
          Adicionar
        </button>
      </div>
      <div class="list-skeleton-loading" v-if="!developers">
        <div class="item"></div>
        <div class="item"></div>
        <div class="item"></div>
        <div class="item"></div>
        <div class="item"></div>
        <div class="item"></div>
        <div class="item"></div>
        <div class="item"></div>
        <div class="item"></div>
      </div>
      <p v-if="developers && !developers.data.length">Desculpe, nenhum desenvolvedor foi encontraro</p>
      <table class="list-records" v-if="developers && developers.data.length">
        <thead>
        <th><input :checked="selecteds.length == developers.data.length" type="checkbox" ref="selectAll"
                   @change="handleSelectAll"></th>
        <th>ID</th>
        <th>Nome</th>
        <th>Idade</th>
        <th>Criado em</th>
        <th>Atualizado em</th>
        <th>Ações</th>
        </thead>
        <tbody>
        <tr v-for="developer in developers.data" :key="developer.id"
            :class="{selected: selecteds.includes(developer.id)}">
          <td><input type="checkbox" :checked="selecteds.includes(developer.id)" :value="developer.id"
                     @change="handleSelect"></td>
          <td>{{ developer.id }}</td>
          <td>{{ developer.nome }}</td>
          <td>{{ developer.idade }}</td>
          <td>{{ $dateISOFormat(developer.created_at) }}</td>
          <td>{{ $dateISOFormat(developer.updated_at) }}</td>
          <td>
            <div class="flex gap-1">
              <button type="button" class="btn-small" @click="showDeveloper(developer)">
                <i class="fi fi-rr-eye"></i>
              </button>
              <button type="button" class="btn-small" @click="handleEdit(developer)">
                <i class="fi fi-rr-pencil"></i>
              </button>
              <button type="button" class="btn-small" @click="handleDelete(developer)">
                <i class="fi fi-rr-trash"></i>
              </button>
            </div>
          </td>
        </tr>
        </tbody>
      </table>

      <div class="pagination" v-if="developers && developers.last_page > 1">
        <button type="button"
                class="btn-small"
                v-for="link in developers.links"
                :key="link.label"
                :class="{active: link.label == page}"
                @click="handlePaginate(link)">
          <i class="fi fi-rr-angle-small-left" v-if="link.label == 'pagination.previous'"></i>
          <i class="fi fi-rr-angle-small-right" v-if="link.label == 'pagination.next'"></i>
          <span v-if="!isNaN(link.label)">{{ link.label }}</span>
        </button>
      </div>
    </div>

    <ShowDeveloperModal ref="showDeveloperModal"></ShowDeveloperModal>
    <FormDeveloperModal ref="formDeveloperModal" @created="loadDevelopers"
                        @updated="loadDevelopers"></FormDeveloperModal>
  </div>
</template>

<script>
import axios from 'axios';
import ShowDeveloperModal from './ShowDeveloperModal';
import FormDeveloperModal from "./FormDeveloperModal";

const CancelToken = axios.CancelToken;
const source = CancelToken.source();

export default {
  data() {
    return {
      developers: undefined,
      filter: {
        nome: '',
        idade: '',
        sexo: '',
        datanascimento: '',
        hobby: '',
      },
      page: 1,
      timeout: undefined,
      selecteds: []
    }
  },
  mounted() {
    this.loadDevelopers()
  },
  watch: {
    filter() {
      if (this.timeout) {
        clearTimeout(this.timeout)
      }

      this.timeout = setTimeout(() => {
        this.loadDevelopers();
      }, 500)
    }
  },
  methods: {
    handleFilter() {
      if (this.timeout) {
        clearTimeout(this.timeout)
      }

      this.timeout = setTimeout(() => {
        this.page = 1;
        this.loadDevelopers();
      }, 500)
    },
    handleSelectAll($event) {
      if ($event.target.checked) {
        let allIDs = this.developers.data.map(dev => dev.id);
        this.selecteds = allIDs;
      } else {
        this.selecteds = [];
      }
    },
    handleSelect($event) {
      if ($event.target.checked) {
        this.selecteds.push(parseInt($event.target.value));
      } else {
        this.selecteds = this.selecteds.filter(selected => selected != $event.target.value);
      }
    },
    handleAdd() {
      this.$refs.formDeveloperModal.show()
    },
    handleEdit(developer) {
      this.$refs.formDeveloperModal.show(developer)
    },
    showDeveloper(developer) {
      this.$refs.showDeveloperModal.show(developer)
    },
    loadDevelopers() {
      axios.get('http://localhost/developers', {
        cancelToken: source.token,
        params: {
          nome: this.filter.nome,
          idade: this.filter.idade,
          sexo: this.filter.sexo,
          datanascimento: this.filter.datanascimento,
          hobby: this.filter.hobby,
          page: this.page
        }
      }).then(({data}) => {
        this.developers = data
        this.selecteds = []
      }).catch(err => {
        console.error(err)

        if(err.response.status == 404) {
          this.developers = err.response.data
          this.selecteds = []
        }
      })
    },
    handlePaginate(link) {
      if (link.label == 'pagination.previous' && this.page > 1) {
        this.page = this.page - 1
      } else if (link.label == 'pagination.next' && this.page < this.developers.last_page) {
        this.page = this.page + 1
      } else if (!isNaN(link.label)) {
        this.page = link.label
      }

      this.loadDevelopers();
    },
    handleDelete(developer) {
      axios.delete(`http://localhost/developers/${developer.id}`).then(() => {
        this.developers.data = this.developers.data.filter(dev => dev.id != developer.id)
        this.loadDevelopers()
      }).catch(err => {
        console.error(err)
        alert('Não foi possível remover o desenvolvedor, tente novamente!')
      })
    },
    deleteMultipleDevelopers() {
      let ids = this.selecteds;

      axios.delete(`http://localhost/developers`, {
        params: {
          ids: ids.join(',')
        }
      }).then(() => {
        this.developers.data = this.developers.data.filter(dev => !ids.includes(dev.id))
        this.loadDevelopers()
      }).catch(err => {
        console.error(err)
        alert('Não foi possível remover o desenvolvedor, tente novamente!')
      })
    }
  },
  components: {
    ShowDeveloperModal,
    FormDeveloperModal
  }
}
</script>

<style lang="scss">
@import '@/assets/base.scss';

.title {
  font-size: 22px;
  margin-bottom: 25px;
  padding-bottom: 5px;
  border-bottom: 2px solid #5d31e6;
  color: #5d31e6;
  //border-bottom: 2px solid #222;
  display: inline-block;
}

.box {
  background-color: #fff;
  border-radius: 5px;
  padding: 15px;
  box-shadow: 0px 2px 5px 2px rgba(32, 32, 32, .1), 0px 0px 5px 2px rgba(32, 32, 32, .1);
}

.input-search {
  padding: 10px 15px;
  border-radius: 5px;
  border: 1px solid #ddd;
  display: inline-block;
  width: 300px;
  position: relative;
  display: flex;
  align-items: center;
  line-height: 100%;

  input {
    border: 0;
    width: 100%;
    padding-left: 25px;
  }

  i.fi {
    position: absolute;
    color: #666;
  }
}

.pagination {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-top: 15px;
}
</style>