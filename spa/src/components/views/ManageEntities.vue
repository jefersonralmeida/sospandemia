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
      <form>
        <h3 class="py-2">Insira as informações referente à entidade</h3>
        <div class="row">
          <div class="form-group col-sm-8 col-md-10">
            <label>Nome</label>
            <input
              type="text"
              v-model="entityData.name"
              class="form-control"
              placeholder="Nome da entidade"
            />
          </div>
          <div class="form-group col-sm-4 col-md-2">
            <label>CNPJ</label>
            <input
              type="text"
              v-model="entityData.cnpj"
              class="form-control"
              placeholder="informe o CNPJ"
            />
          </div>
        </div>
        <div class="form-group">
          <label>Razão Social</label>
          <input
            type="text"
            class="form-control"
            placeholder="Insira a razão social da entidade"
            v-model="entityData.legal_name"
          />
        </div>
        <div class="form-group">
          <label for="Description">Endereço</label>
          <input
            type="text"
            class="form-control"
            placeholder="Ex: Rua Exemplo, 1029"
            v-model="entityData.street_address"
          />
        </div>
        <div class="row">
          <div class="col-md-1 col-sm-2 col-3">
            <v-select
              :items="states"
              v-model="entityData.state"
              :loading="!statesFetched"
              :search-input.sync="search"
              label="Estado"
              outlined
              item-text="uf"
              item-value="id"
            ></v-select>
          </div>
          <div class="form-group col-md-11 col-sm-10 col-9">
            <v-autocomplete
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
          <label>Descrição</label>
          <textarea
            type="text"
            class="form-control"
            placeholder="(Mínimo de 10 caracteres) Adicione uma descrição, descrevendo por exemplo o que a entidade faz, pelo que é responsável, etc."
            v-model="entityData.description"
          />
        </div>
        <div class="d-flex justify-content-end">
          <button class="btn btn-danger" @click="handleCreateCancel">Cancelar</button>
          <button class="btn btn-success ml-1" @click="createEntity">Criar</button>
        </div>
      </form>
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

    createEntity: function(ev) {
      ev.preventDefault();
      api
        .createEntity({...this.entityData, district_id: this.entityData.city.id})
        .then(res => {
          console.log(res);
          this.creatingEntity = false;
          this.entities.push(this.entityData); //SOLUCAO PROVISORIA!
        })
        .catch(err => {
          console.log(err);
        });
    },
    updateEntity: function(entityId, data) {
      const current = this.entities.find(entity => entity.id === entityId);
      api.updateEntity(entityId, data).then(response => {
        console.log(response);
      });
    },

    leaveEntity: function(entityId) {
      api.leaveEntity(entityId).catch(err => {
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
        this.statesFetched = true;
      });
    },
    fetchCities(stateId, query) {
      return api.getDistricts(stateId, query);
    }
  },
  watch: {
    search(query) {
      if(query.length <= 3) return
      clearTimeout(this.debounce);
      let that = this;
      this.debounce = setTimeout(function() {
        that
          .fetchCities(that.entityData.state, query)
          .then(res => {
            that.cities = res.data;
          })
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
