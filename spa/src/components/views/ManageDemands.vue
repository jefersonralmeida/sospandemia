<template>
  <div class="container">
    <h1>Gerenciar Demandas - {{ activeEntity.name }}</h1>

    <button v-if="!creatingDemand" class="btn btn-success" @click="creatingDemand=true">Nova demanda</button>
    <div v-if="creatingDemand">
      <v-form ref="form">
        <div class="form-group">
          <v-text-field
            v-model="demandData.title"
            :counter="200"
            :rules="[rules.required]"
            label="Demanda"
          ></v-text-field>
        </div>
        <div class="form-group">
          <v-text-field
            type="number"
            v-model="demandData.quantity"
            :rules="[rules.numberRule,rules.required]"
            label="Quantidade"
          ></v-text-field>
        </div>
        <div class="form-group">
          <v-textarea
            rows="3"
            auto-grow
            :counter="500"
            label="Adicione um descrição"
            v-model="demandData.text"
            :rules="[rules.required]"
          ></v-textarea>
        </div>
        <div class="d-flex justify-content-end">
          <button class="btn btn-danger" @click="creatingDemand=false">Cancelar</button>
          <button class="btn btn-success ml-1" @click="createDemand">Criar</button>
        </div>
      </v-form>
    </div>
    <hr />
    <loading-widget v-if="checked==false"></loading-widget>
    <p
      v-else-if="checked==true && demands.length === 0"
    >Não há demandas, clique em "Nova demanda" para adicionar!</p>
    <demand-card
      v-else
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
    },
    rules: {
      min: v => v.length >= 1 || "Min 15 caracteres",
      required: value => !!value || "Obrigatório.",
      numberRule: v => {
        if (parseInt(v) && v >= 1) return true;
        return "O campo deve conter apenas múmero. Favor verificar!";
      }
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
    validate() {
      console.log(this.$refs);
      return this.$refs.form.validate();
    },
    createDemand: function(ev) {
      ev.preventDefault();
      if (this.validate()) {
        this.creatingDemand = false;
        api.createDemand(this.demandData).then(() => {
          (this.demandData = {
            title: "",
            text: "",
            quantity: 1
          }),
            this.loadDemands();
        });
      }
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
      this.creatingDemand = false;
      this.checked = false;
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
