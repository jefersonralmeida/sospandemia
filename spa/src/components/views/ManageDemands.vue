<template>
  <div class="hello">
    <h1>Gerenciar Demandas</h1>
    <p>Listar aqui as demandas da entidade <strong>{{ activeEntity.name }}</strong></p>
    <p>Lista paginada... Incluir opções de incluir, editar, excluir, resolver..., CRUD completo. Esqueleto abaixo, respostas no console.</p>
    <a href="#" class="btn btn-success" @click="createDemand()">Nova demanda</a>
    <hr/>
    <div class="card" v-for="demand in demands">
      <div class="card-body">
        <h5 class="card-title">{{ demand.title }}</h5>
        <p class="card-text">{{ demand.text }}</p>
        <p class="card-text">Quantidade: {{ demand.quantity }}</p>
        <a href="#" class="btn btn-primary" @click="viewDemand(demand.id)">Info (console)</a>
        <a href="#" class="btn btn-warning" @click="updateDemand(demand.id)">Alterar (hard coded)</a>
        <a href="#" class="btn btn-danger" @click="deleteDemand(demand.id)">Excluir</a>
      </div>
    </div>
  </div>
</template>

<script>
import api from "../../api";
import randomstring from "randomstring";

export default {
  name: 'Home',
  data: () => ({
    demands: []
  }),
  methods: {
    loadDemands: function() {
      api.indexDemandsByEntity().then(({data}) => {
        this.demands = data.data;
      });
    },
    createDemand: function() {
      const data = {
        title: 'New demand ' + randomstring.generate(5),
        text: 'Test description ' + randomstring.generate(5),
      };
      api.createDemand(data).then(() => {
        this.loadDemands();
      });
    },
    viewDemand: function(demandId) {
      api.getDemand(demandId).then(({data}) => {
        console.log(data);
      });
    },
    updateDemand: function(demandId) {

      // estou apenas pegando o valor atual...
      const current = this.demands.find((demand) => demand.id === demandId);

      // e adicionando um - ao final do título a cada update, e incrementando a quantidade
      const data = {
        title: current.title + '-',
        quantity: current.quantity === null ? 1 : ++current.quantity,
      };
      api.updateDemand(demandId, data).then(() => {
        this.loadDemands();
      });

    },
    deleteDemand: function(demandId) {
      api.deleteDemand(demandId).then(() => {
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
  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
