<template>
  <div class="hello">
    <h1>Gerenciar Demandas</h1>
    <p>
      Listar aqui as demandas da entidade
      <strong>---</strong>
    </p>
    <p>Lista paginada... Incluir opções de incluir, editar, excluir, resolver..., CRUD completo. Esqueleto abaixo, respostas no console.</p>
    <button v-if="!creatingDemand" class="btn btn-success" @click="creatingDemand=true">Nova demanda</button>
    <div v-if="creatingDemand">
      <form>
        <div class="form-group">
          <label for="demandTitle">Título da Demanda</label>
          <input
            type="text"
            v-model="demandData.title"
            class="form-control"
            placeholder="Digite o título da demanda"
          />
          <small
            class="form-text text-muted"
          >Informe o que é necessitado.</small>
        </div>
        <div class="form-group">
          <label for="Description">Descrição</label>
          <textarea
            type="text"
            class="form-control"
            placeholder="Adicione um descrição"
            v-model="demandData.text"
          />
        </div>
        <div class="d-flex justify-content-end">
          <button class="btn btn-danger" @click="creatingDemand=false">Cancelar</button>
          <button class="btn btn-success ml-1" @click="createDemand()">Criar</button>
        </div>
      </form>
    </div>
    <hr />

    <demand-card
      v-for="demand in demands"
      v-bind:key="demand.id"
      :demand="demand"
      :onViewDemandCB="viewDemand"
      :onUpdateDemandCB="updateDemand"
      :onDeleteDemandCB="deleteDemand"
    ></demand-card>
    <!-- <div class="card" v-for="(demand,i) in demands" v-bind:key="i">
      <div class="card-body">
        <h5 class="card-title">{{ demand.title }}</h5>
        <p class="card-text">{{ demand.text }}</p>
        <p class="card-text">Quantidade: {{ demand.quantity }}</p>
        <button class="btn btn-primary" @click="viewDemand(demand.id)">Info (console)</button>
        <button class="btn btn-warning" @click="updateDemand(demand.id)">Alterar (hard coded)</button>
        <button class="btn btn-danger" @click="deleteDemand(demand.id)">Excluir</button>
      </div>
    </div>-->
  </div>
</template>

<script>
import Demand from "./Demand";
import api from "../../api_local";
import randomstring from "randomstring";

export default {
  name: "ManageDemandsLocal",
  components: {
    "demand-card": Demand
  },
  data: () => ({
    demands: [],
    creatingDemand: false,
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
      /* const data = {
        title: "New demand " + randomstring.generate(5),
        text: "Test description " + randomstring.generate(5)
      };
       */
      this.creatingDemand=false;
      api.createDemand(this.demandData).then(() => {
        this.loadDemands();
      });
    },
    viewDemand: function(demandId) {
      api.getDemand(demandId).then(({ data }) => {
        console.log(data);
      });
    },
    updateDemand: function(demandId) {
      // estou apenas pegando o valor atual...
      const current = this.demands.find(demand => demand.id === demandId);

      // e adicionando um - ao final do título a cada update, e incrementando a quantidade
      const data = {
        title: current.title + "-",
        quantity: current.quantity === null ? 1 : ++current.quantity
      };
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
