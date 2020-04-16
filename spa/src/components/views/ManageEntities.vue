<template>
  <div class="container">
    <h1>Gerenciar Entidades</h1>

    <button v-if="!creatingEntity" class="btn btn-success m-1" @click="handleOpenCreateEntityPanel">
      <span class="fa fa-plus-square"></span> Nova entidade
    </button>
    
    <v-select
      v-if="creatingEntity"
      label="Tipo de Entidade"
      v-model="entityType"
      :items="entityTypes"
      outlined
      @change="resetForm"
    ></v-select>

    <div v-if="creatingEntity && entityType=='Unidade de Saúde'">
      <hr />
      <h3 class="py-2">Insira as informações referente à entidade</h3>
      <v-form ref="formUS">
        <div class="row">
          <div class="form-group col-6">
            <v-text-field
              v-model="cnes"
              label="CNES"
              placeholder="informe o CNES"
              :rules="[rules.numberRule,rules.required]"
              outlined
              @change="getEntityByCNES"
            ></v-text-field>
          </div>
          <div class="form-group col-6">
            <v-text-field
              v-model="entityData.cnpj"
              label="CNPJ"
              placeholder="informe o CNPJ"
              :rules="[rules.numberRule,rules.required]"
              outlined
            ></v-text-field>
          </div>
        </div>
        <div class="form-group">
          <v-text-field
            v-model="entityData.name"
            outlined
            :rules="[rules.required,rules.min]"
            disabled
            label="Nome"
          ></v-text-field>
        </div>
        <div class="form-group">
          <v-text-field
            v-model="entityData.legal_name"
            outlined
            :rules="[rules.required,rules.min]"
            disabled
            label="Razão Social"
          ></v-text-field>
        </div>  
        <div class="row">
          <div class="form-group col-8 col-md-10">
            <v-text-field
              v-model="cnesResponse.district_name"
              :rules="[rules.required]"
              outlined
              disabled
              label="Cidade"
            ></v-text-field>
          </div>
          <div class="form-group col-4 col-md-2">
            <v-text-field
              v-model="cnesResponse.uf"
              :rules="[rules.required]"
              outlined
              disabled
              label="Estado"
            ></v-text-field>
          </div>
        </div>
        <div class="form-group">
          <v-text-field
            ref="address"
            v-model="entityData.street_address"
            :rules="[rules.required, rules.min]"
            label="Endereço"
            placeholder="Ex: Rua Exemplo, 1029"
            outlined
          ></v-text-field>
        </div>
        <div class="form-group">
          <v-select
              v-model="entityData.city"
              :disabled="entityData.state == ''"
              :rules="[rules.required]"
              :items="cities"
              item-text="name"
              item-value="id"
              label="Cidade"
              outlined
            ></v-select>
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
            rows="3"
            auto-grow
            :rules="[rules.required, rules.min]"
            label="Adicione um descrição"
            placeholder="(Mínimo de 10 caracteres) Adicione uma descrição, descrevendo por exemplo o que a entidade faz, pelo que é responsável, etc."
            v-model="entityData.description"
            outlined
          ></v-textarea>
        </div>
        <div class="d-flex justify-content-end">
          <v-btn color="red" dark @click="handleCreateCancel">Cancelar</v-btn>
          <v-btn color="success" :loading="loading" class="ml-1" @click="event => createEntity(1, event)">Criar</v-btn>
        </div>
      </v-form>
    </div>
    <div v-if="creatingEntity && entityType=='Outras'">
      <hr />
      <v-form ref="formOutros">
        <h3 class="py-2">Insira as informações referente à entidade</h3>
        <div class="row">
          <div class="form-group col-sm-8 col-md-10 col-12">
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
          <div class="form-group col-sm-4 col-md-2 col-12">
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
          <v-text-field
            ref="address"
            v-model="entityData.street_address"
            :counter="300"
            :rules="[rules.required, rules.min]"
            label="Endereço"
            placeholder="Ex: Rua Exemplo, 1029"
            outlined
          ></v-text-field>
        </div>
        <div class="row">
          <div class="col-md-2 col-sm-3 col-12">
            <v-select
              label="Estado"
              :items="states"
              v-model="entityData.state"
              :loading="!statesFetched"
              outlined
              item-text="uf"
              item-value="id"
            ></v-select>
          </div>
          <div class="form-group col-md-10 col-sm-9 col-12">
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
              :no-data-text="entityData.noDataText"
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
          <v-btn color="red" dark @click="handleCreateCancel">Cancelar</v-btn>
          <v-btn color="success" :loading="loading" class="ml-1" @click="event => createEntity(0, event)">Criar</v-btn>
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
    others: null,
    entityTypes:[],
    entityType:"",
    cnes:"",
    cnesResponse:{},
    creatingEntity: false,
    entityData: {
      cnpj: "",
      name: "",
      legal_name: "",
      description: "",
      street_address: "",
      city: "",
      state: "",
      noDataText: "Continue digitando para encontrar uma cidade.",
    },
    rules: {
      min: v => v != null && v.length >= 1 || "Minimo 15 caracteres",
      required: value => !!value || "Obrigatório.",
      numberRule: v => (parseInt(v) && v >= 1) || "O campo deve conter apenas números."
    },
    states: [],
    cities: [],
    loading: false,
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
      if (this.statesFetched == false) this.fetchStates()
      this.getEntityTypes();
      this.creatingEntity = true;
    },
    isActiveEntity: function(entityId) {
      return this.$store.getters.activeEntityId === entityId ? "active" : "";
    },
    validate() {
      if(this.entityType=='Unidade de Saúde')
        return this.$refs.formUS.validate();
      else
        return this.$refs.formOutros.validate();
    },
    getEntityTypes: function(){
      return api.getEntityTypes().then(res=>{
        this.entityTypes = Object.values(res.data);
      })
    },
    createEntity: function(type,ev) {
      ev.preventDefault();
      if (type==1){
        if (this.validate()) {
          this.loading=true;
          api.createEntity({
            entity_type_id:type,
            entity_type_document:this.cnes,
            ...this.entityData,
            district_id: this.entityData.city
          }).then(res => {
              this.creatingEntity = false;
              this.entityData = {
                name: "",
                cnpj: "",
                legal_name: "",
                address: "",
                state: "",
                city: "",
                description: ""
              };
              this.$store.commit('showMessage', { content:`Entidade criada com sucesso!`, error:false })
              //this.entities.push(this.entityData); //SOLUCAO PROVISORIA!
            })
            .catch(err => {
              this.$store.commit('showMessage', { content:err, error:true })
            })
            .finally(() => {
              this.loading = false;
              this.$store.dispatch("loadProfile");
            });
        }
      }else{
        if (this.validate()) {
          this.loading=true;
          api
            .createEntity({
              ...this.entityData,
              entity_type_id: type,
              district_id: this.entityData.city.id
            })
            .then(res => {
              this.creatingEntity = false;
              this.entityData = {
                name: "",
                cnpj: "",
                legal_name: "",
                address: "",
                state: "",
                city: "",
                description: ""
              };
              this.$store.commit('showMessage', { content:`Entidade criada com sucesso!`, error:false })
              //this.entities.push(this.entityData); //SOLUCAO PROVISORIA!
            })
            .catch(err => {
              this.$store.commit('showMessage', { content:err, error:true })
            })
            .finally(() => {
              this.loading = false;
              this.$store.dispatch("loadProfile");
            });
        }  
      }
    },
    updateEntity: function(entityId, data) {
      const current = this.entities.find(entity => entity.id === entityId);
      return api.updateEntity(entityId, data).then(response => {
        this.$store.dispatch("loadProfile");
      });
    },

    leaveEntity: function(entityId) {
      return api.leaveEntity(entityId)
      .then(()=>{
        this.$store.dispatch("loadProfile");
      });
    },
    inviteEntity: function(entityId, userId) {
      return api.inviteToEntity(entityId, userId);
    },
    getEntityByCNES: function(){
      try{
        if (this.cnes.length!=7){
          this.cnesResponse = {};
          return;
        }
      }catch(e){}

      return api.getEntityByCNES(this.cnes).then(res=>{
        this.cnesResponse = res.data;
        this.entityData.name = res.data.name;
        this.entityData.legal_name = res.data.legal_name;
        this.entityData.state = this.states.find(element => element.uf == this.cnesResponse.uf);
        this.fetchCities(this.entityData.state.id,this.cnesResponse.district_name).then(
          res=>{
            this.cities = res.data;
          }
        )
      }).catch(e => {
        this.$store.commit('showMessage', { content:`Problemas ao identificar o CNES!`, error:true })
      })
    },
    fetchStates() {
      api.getStates().then(res => {
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
        this.statesFetched = true;
      });
    },
    fetchCities(stateId, query) {
      return api.getDistricts(stateId, query);
    },
    resetForm: function(){
      try{
        this.$refs.formUS.reset()
        this.$refs.formOutros.reset();
      }catch(e){

      }
      /*
      this.entityData={
        cnpj: "",
        name: "",
        legal_name: "",
        description: "",
        street_address: "",
        city: "",
        state: "",
        noDataText: "Continue digitando para encontrar uma cidade.",
      }*/
    }
  },
  watch: {
    search(query) {
      if(query === null) return
      if (query.length <= 3) {
        this.entityData.noDataText = "Continue digitando para encontrar uma cidade."
        return;
      }
      clearTimeout(this.debounce);
      let that = this;
      this.debounce = setTimeout(function() {
        that.fetchCities(that.entityData.state, query).then(res => {
          that.cities = res.data;
          that.entityData.noDataText = "Nenhuma cidade encontrada. Verifique a busca ou seja mais específico"
        });
      }, 300);
    },
    entities: function(){
      if(this.entities.length==1){
        this.selectEntity(this.entities[0].id,true);
      }
    },
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
