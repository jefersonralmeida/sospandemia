<template>
  <div class="container">
    <h1>Gerenciar Demandas - {{ activeEntity.name }}</h1>

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
        </div>
        <div class="form-group">
          <label for="demandTitle">Quantidade</label>
          <input
            type="number"
            v-model="demandData.quantity"
            class="form-control"
            placeholder="Quantidade necessaria para suprir a demanda"
          />
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
    <loading-widget v-if="checked==false"></loading-widget>
    <p v-else-if="checked==true && demands.length === 0">Não há demandas, clique em "Nova demanda" para adicionar!</p>
    <demand-card
      v-for="demand in demands"
      v-bind:key="demand.id"
      :demand="demand"
      :onViewDemandCB="viewDemand"
      :onUpdateDemandCB="updateDemand"
      :onDeleteDemandCB="deleteDemand"
      class="mt-2"
    ></demand-card>
  </div>
</template>

<script>
import Demand from "./Demand";
import api from "../../api";
import randomstring from "randomstring";
import LoadingWidget from "../widgets/LoadingWidget";

export default {
  name: "ManageDemandsLocal",
  components: {
    "demand-card": Demand,
    "loading-widget": LoadingWidget
  },
  data: () => ({
    demands: [],
    creatingDemand: false,
    checked: false,
    demandData: {
      title: "",
      text: "",
      quantity: 1
    }
  }),
  methods: {
    loadDemands: function() {
      api.indexDemandsByEntity().then(response => {
        console.log(response);
        this.demands = response.data.data;
        this.checked = true;
      });
    },
    createDemand: function() {
      const data = {
        title: "New demand " + randomstring.generate(5),
        text: "Test description " + randomstring.generate(5)
      };

      this.creatingDemand = false;
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
