<template>
  <div class="container">
    <h1>Nome da Entidade</h1>
    
    <form>
      <div class="form-group col-7">
        <label>Endereço</label>
        <input
          type="email"
          class="form-control form-control-sm"
          placeholder="lidar com API do maps aqui"
        />
      </div>
      <div class="form-group col-4">
        <label>CNPJ</label>
        <input type="text" value="adicionar CNPJ da entidade" class="form-control form-control-sm" disabled />
      </div>
    </form>
    
    <div class="table-responsive">
      <table class="table table-hover table-sm">
        <caption>Lista de usuários ligados à essa entidade.</caption>
        <thead class="thead-dark">
          <tr>
            <th scope="col" style="width:30%;">Nome</th>
            <th scope="col" style="width:40%;">Email</th>
            <th scope="col" style="width:20%;">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="demand in demands" v-bind:key="demand.id">
            <td>{{demand.title}}</td>
            <td>{{demand.text}}</td>
            <td>
              <button class="btn btn-warning btn-sm">Editar</button>
              <button class="btn btn-danger btn-sm ml-2">Remover</button>
            </td>
          </tr>
        </tbody>
        <button
          v-if="!includingUser"
          class="btn btn-success btn-sm"
          @click="includingUser=true"
        >Adicionar novo usuário</button>
      </table>
    </div>
    <div v-if="includingUser">
        <p>
            adicionar forumlario
        </p>
        <button class="btn btn-danger" @click="includingUser=false">Cancelar</button>
        <button class="btn btn-success ml-1">Criar</button>
    </div>
  </div>
</template>

<script>
//testes feito com dados da demanda local, somente para exibirr alguma coisa na tela
import api from "../../api_local";

export default {
  name: "ManageEntitiesLocal",
  data: () => ({
    demands: [],
    includingUser: false,
    demandData: {
      title: "",
      text: ""
    }
  }),
  methods: {
    loadDemands: function() {
      api.indexDemandsByEntity().then(response => {
        console.log(response);
        this.demands = response.data.data;
      });
    },
    createDemand: function() {
      this.includingUser = false;
      api.createDemand(this.demandData).then(() => {
        this.loadDemands();
      });
    },
    viewDemand: function(demandId) {
      api.getDemand(demandId).then(({ data }) => {
        console.log(data);
      });
    },
    updateDemand: function(demandId, data) {
      // estou apenas pegando o valor atual...
      const current = this.demands.find(demand => demand.id === demandId);

      api.updateDemand(demandId, data).then(response => {
        console.log(response);
        this.loadDemands();
      });
    },
    deleteDemand: function(demandId) {
      api.deleteDemand(demandId).then(response => {
        console.log(response);
        this.loadDemands();
      });
    }
  },
  computed: {
    activeEntity: function() {
      return this.$store.getters.activeEntity;
    }
  },
  watch: {
    activeEntity: function() {
      this.loadDemands();
    }
  },
  mounted() {
    this.loadDemands();
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
