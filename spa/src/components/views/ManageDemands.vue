<template>
  <div class="container">
    <h1>Gerenciar Demandas - {{ activeEntity.name }}</h1>

    <button v-if="!creatingDemand" class="btn btn-success" @click="creatingDemand=true">Nova demanda</button>
    <div v-if="creatingDemand">
      <v-form ref="form">
        <div class="form-group">
          <v-text-field
            ref="title"
            v-model="demandData.title"
            :counter="200"
            :rules="[rules.required]"
            label="Demanda"
            outlined
          ></v-text-field>
        </div>
        <div class="form-group">
          <v-text-field
            ref="quantity"
            type="number"
            v-model="demandData.quantity"
            :rules="[rules.numberRule,rules.required]"
            label="Quantidade"
            outlined
          ></v-text-field>
        </div>
        <div class="form-group">
          <v-textarea
            ref="text"
            rows="3"
            auto-grow
            :counter="500"
            label="Adicione um descrição"
            v-model="demandData.text"
            :rules="[rules.required]"
            outlined
          ></v-textarea>
        </div>
        <div class="d-flex justify-content-end">
          <button class="btn btn-danger" @click="creatingDemand=false">Cancelar</button>
          <v-btn
            @click="createDemand"
            color="success"
            class="ml-1"
            :loading="creatingDemandLoading"
          >Criar</v-btn>
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
    
  <div class="text-center mt-2" v-if="checked && last_page>1">
    <v-pagination
      v-model="current_page"
      @input="loadDemands"
      :length="last_page"
      next-icon="mdi-skip-next"
    ></v-pagination>
  </div>

  </div>
</template>

<script>
import Demand from "./Demand";
import api from "../../api";
import randomstring from "randomstring";
import LoadingWidget from "../widgets/LoadingWidget";

import rules from "../../util/rules";
export default {
  mixins: [rules],
  name: "ManageDemandsLocal",
  components: {
    "demand-card": Demand,
    "loading-widget": LoadingWidget
  },
  data: () => ({
    demands: [],
    current_page:1,
    last_page:1,
    creatingDemand: false,
    checked: false,
    creatingDemandLoading: false,
    demandData: {
      title: "",
      text: "",
      quantity: 1
    },
  }),
  methods: {
    loadDemands: function() {
      api.indexDemandsByEntity(this.current_page).then(response => {
        console.log(response);
        //this.current_page = response.data.current_page;
        this.last_page = response.data.last_page;
        this.demands = response.data.data;
        this.checked = true;
      });
    },
    validate() {
      console.log(this.$refs);
      return this.$refs.form.validate();
    },
    createDemand: function() {
      if (this.validate()) {
        this.creatingDemandLoading = true;
        api
          .createDemand(this.demandData)
          .then(() => {
            this.creatingDemand = false;
            this.$store.commit("showMessage", {
              content: "Demanda adicionada!",
              error: false
            });
            this.loadDemands();
          })
          .catch(error => {
            this.$store.commit("showMessage", {
              content: "Erro ao adicionar a demanda.",
              error: true
            });
            for (let errPropriety in error.response.data.errors) {
              console.log(this.$refs[errPropriety]);
              this.$refs[errPropriety].errorMessages.push(
                error.response.data.errors[errPropriety][0]
              );
            }
          })
          .finally(() => {
            this.creatingDemandLoading = false;
            this.demandData = {
              title: "",
              text: "",
              quantity: 1
            };
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

      return api.updateDemand(demandId, data).then(response => {
        console.log(response);
        this.loadDemands();
      });
    },
    deleteDemand: function(demandId) {
      return api.deleteDemand(demandId).then(response => {
        console.log("teste",this.demands.length);
        if(this.demands.length == 1 && this.current_page>1){
          this.current_page--;
        }
        this.loadDemands()
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
