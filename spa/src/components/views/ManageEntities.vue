<template>
  <div class="container">
    <h1>Gerenciar Entidades</h1>

    <button v-if="!creatingEntity" class="btn btn-success m-1" @click="handleOpenCreateEntityPanel">
      <span class="fa fa-plus-square"></span> Nova entidade
    </button>
    <button v-if="!creatingEntity" class="btn btn-primary m-1">
      <span class="fa fa-sync"></span>
    </button>
    <div v-if="creatingEntity">
      <hr />
      <v-form ref="form">
        <h3 class="py-2">Insira as informações referente à entidade</h3>
        <div class="row">
          <div class="form-group col-sm-8 col-md-10">
            <!--<label>Nome</label>
            <input
              type="text"
              v-model="entityData.name"
              class="form-control"
              placeholder="Nome da entidade"
            />-->
            <v-text-field
              ref="name"
              v-model="entityData.name"
              :counter="200"
              :rules="[rules.required]"
              label="Nome"
              placeholder="Nome da entidade"
              outlined
            ></v-text-field>
          </div>
          <div class="form-group col-sm-4 col-md-2">
            <!--<label>CNPJ</label>
            <input
              type="text"
              v-model="entityData.cnpj"
              class="form-control"
              placeholder="informe o CNPJ"
            />-->
            <v-text-field
              ref="cnpj"
              v-model="entityData.cnpj"
              :rules="[rules.numberRule,rules.required]"
              label="CNPJ"
              placeholder="informe o CNPJ"
              outlined
            ></v-text-field>
          </div>
        </div>
        <div class="form-group">
          <!--<label>Razão Social</label>
          <input
            type="text"
            class="form-control"
            placeholder="Insira a razão social da entidade"
            v-model="entityData.legal_name"
          />-->
          <v-text-field
            ref="legal_name"
            v-model="entityData.legal_name"
            :counter="300"
            :rules="[rules.required]"
            label="Razão Social"
            placeholder="Insira a razão social da entidade"
            outlined
          ></v-text-field>
        </div>
        <div class="form-group">
          <!--<label for="Description">Endereço</label>
          <input
            type="text"
            class="form-control"
            placeholder="Ex: Rua Exemplo, 1029"
            v-model="entityData.street_address"
          />-->
          <v-text-field
            ref="address"
            v-model="entityData.street_address"
            :counter="300"
            :rules="[rules.required]"
            label="Endereço"
            placeholder="Ex: Rua Exemplo, 1029"
            outlined
          ></v-text-field>
        </div>
        <div class="row">
          <div class="col-md-2 col-sm-3 col-3">
            <v-select
              label="Estado"
              :items="states"
              v-model="entityData.state"
              :loading="!statesFetched"
              :search-input.sync="search"
              outlined
              item-text="uf"
              item-value="id"
            ></v-select>
          </div>
          <div class="form-group col-md-10 col-sm-9 col-9">
            <v-autocomplete
              ref="city"
              v-model="entityData.city"
              :disabled="entityData.state == ''"
              :items="cities"
              item-text="name"
              label="Cidade"
              autocomplete="dskjalçkdwlçakdwlça"
              placeholder="Digite o nome da cidade para buscar"
              :search-input.sync="search"
              outlined
              hide-no-data
              hide-selected
              return-object
            ></v-autocomplete>
          </div>
        </div>
        <div class="form-group">
          <!--<label>Descrição</label>
          <textarea
            type="text"
            class="form-control"
            placeholder="(Mínimo de 10 caracteres) Adicione uma descrição, descrevendo por exemplo o que a entidade faz, pelo que é responsável, etc."
            v-model="entityData.description"
          />-->
          <v-textarea
            ref="description"
            rows="3"
            auto-grow
            :counter="500"
            label="Adicione um descrição"
            placeholder="(Mínimo de 10 caracteres) Adicione uma descrição, descrevendo por exemplo o que a entidade faz, pelo que é responsável, etc."
            v-model="entityData.description"
            :rules="[rules.required]"
            outlined
          ></v-textarea>
        </div>
        <div class="d-flex justify-content-end">
          <button class="btn btn-danger" @click="handleCreateCancel">Cancelar</button>
          <button class="btn btn-success ml-1" @click="createEntity">Criar</button>
        </div>
      </v-form>
    </div>
    <hr />
    <entity-card
      v-for="entity in entities"
      v-bind:key="entity.id"
      :entity="entity"
      :onUpdateEntityCB="updateEntity"
      :onLeaveEntityCB="leaveEntity"
      :onSelectEntityCB="selectEntity"
      :isActiveEntityCB="isActiveEntity"
      :onInviteUserCB="inviteEntity"
    ></entity-card>
  </div>
</template>

<script>
import api from "../../api";
import Entity from "./Entity";

export default {
  name: "SelectEntity",
  components: {
    "entity-card": Entity
  },
  data: () => ({
    creatingEntity: false,
    entityData: {
      cnpj: "",
      name: "",
      legal_name: "",
      description: "",
      street_address: "",
      city: "",
      state: ""
    },
    rules: {
      min: v => v.length >= 1 || "Minimo 15 caracteres",
      required: value => !!value || "Obrigatório.",
      numberRule: v => {
        if (parseInt(v) && v >= 1) return true;
        return "O campo deve conter apenas números.";
      }
    },
    states: [],
    cities: [],
    statesFetched: false,
    search: null,
    debounce: null
  }),
  methods: {
    selectEntity: function(entityId, redirect = false) {
      this.$store.commit("setEntity", entityId);
      if (redirect) {
        this.$router.push("/gerenciar-demandas");
      }
    },
    handleCreateCancel: function(ev) {
      ev.preventDefault();
      this.creatingEntity = false;
    },
    handleOpenCreateEntityPanel() {
      if (this.statesFetched == false) this.fetchStates();
      this.creatingEntity = true;
    },
    isActiveEntity: function(entityId) {
      return this.$store.getters.activeEntityId === entityId ? "active" : "";
    },
    validate() {
      console.log(this.$refs);
      return this.$refs.form.validate();
    },

    createEntity: function(ev) {
      ev.preventDefault();
      if (this.validate()) {
        api
          .createEntity({
            ...this.entityData,
            district_id: this.entityData.city.id
          })
          .then(res => {
            console.log(res);
            this.creatingEntity = false;
            //this.entities.push(this.entityData); //SOLUCAO PROVISORIA!
          })
          .catch(err => {
            console.log(err);
          })
          .finally(() => {
            this.creatingDemandLoading = false;
            this.demandData = {
              name: "",
              cnpj: "",
              legal_name: "",
              address: "",
              Estado: "",
              city: "",
              description: ""
            };
            this.$store.dispatch("loadProfile");
          });
      }  
    },
    updateEntity: function(entityId, data) {
      const current = this.entities.find(entity => entity.id === entityId);
      api.updateEntity(entityId, data).then(response => {
        console.log(response);
        this.$store.dispatch("loadProfile");
      });
    },

    leaveEntity: function(entityId) {
      api.leaveEntity(entityId)
      .then(()=>{
        this.$store.dispatch("loadProfile");
      })
      .catch(err => {
        console.log(err);
        window.alert(
          "Você é o último usuário permanecente nessa entidade, é necessário que pelo menos um usuário permaneça na entidade"
        );
      });
    },
    inviteEntity: function(entityId, userId) {
      api.inviteToEntity(entityId, userId);
    },
    fetchStates() {
      api.getStates().then(res => {
        console.log(res);
        this.states = res.data;
        this.states.sort((a,b)=>{
          const stateA = a.uf;
          const stateB = b.uf;
          let comparison = 0;
          if (stateA > stateB) {
            comparison = 1;
          } else if (stateA < stateB) {
            comparison = -1;
          }
          return comparison;
        });
        console.log(this.states)
        this.statesFetched = true;
      });
    },
    fetchCities(stateId, query) {
      return api.getDistricts(stateId, query);
    }
  },
  watch: {
    search(query) {
      if (query.length <= 3) return;
      clearTimeout(this.debounce);
      let that = this;
      this.debounce = setTimeout(function() {
        that.fetchCities(that.entityData.state, query).then(res => {
          that.cities = res.data;
        });
      }, 300);
    }
  },
  computed: {
    entities: function() {
      return this.$store.getters.entities;
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.active {
  font-weight: bold;
}
</style>
