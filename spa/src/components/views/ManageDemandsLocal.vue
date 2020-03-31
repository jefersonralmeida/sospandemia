<template>
  <div class="hello">
    <h1>Gerenciar Demandas</h1>
    <p>
      Listar aqui as demandas da entidade
      <strong>---</strong>
    </p>
    <p>Lista paginada... Incluir opções de incluir, editar, excluir, resolver..., CRUD completo. Esqueleto abaixo, respostas no console.</p>
    <button class="btn btn-success" @click="creatingDemand = true">Nova demanda</button>
    <div v-if="creatingDemand">
      <form>
        <div class="form-group">
          <label for="exampleInputEmail1">Titulo da demanda</label>
          <input
            v-model="demandTemp.title"
            type="text"
            class="form-control"
            placeholder="Titulo da demanda"
          />
          <small id="emailHelp" class="form-text text-muted">{{demandTemp.title}}</small>
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">Password</label>
          <input
            type="password"
            class="form-control"
            id="exampleInputPassword1"
            placeholder="Password"
          />
        </div>
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="exampleCheck1" />
          <label class="form-check-label" for="exampleCheck1">Check me out</label>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>

    <hr />
    <demand-card
      v-for="(demand, i) in demands"
      :key="i"
      :demand="demand"
      :onViewDemandCB="viewDemand"
      :onUpdateDemandCB="updateDemand"
      :onDeleteDemandCB="deleteDemand"
      class="mt-2"
    ></demand-card>

    <!--
    <div class="card" v-for="(demand, i) in demands" v-bind:key="i">
      <div class="card-body">
        <h5 class="card-title">{{ demand.title }}</h5>
        <p class="card-text">{{ demand.text }}</p>
        <p class="card-text">Quantidade: {{ demand.quantity }}</p>
        <button class="btn btn-primary" @click="viewDemand(demand.id)">Info (console)</button>
        <button class="btn btn-warning" @click="updateDemand(demand.id)">Alterar (hard coded)</button>
        <button class="btn btn-danger" @click="deleteDemand(demand.id)">Excluir</button>
      </div>
    </div>
    -->
  </div>
</template>

<script>
import api from "../../api_local";
import randomstring from "randomstring";
import Demand from "./Demand";

export default {
  name: "ManageDemandsLocal",
  components: {
    "demand-card": Demand
  },
  data: () => ({
    demands: [],
    creatingDemand: false,
    demandTemp: {
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
      const data = {
        title: "New demand " + randomstring.generate(5),
        text: "Test description " + randomstring.generate(5)
      };
      api.createDemand(data).then(() => {
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
