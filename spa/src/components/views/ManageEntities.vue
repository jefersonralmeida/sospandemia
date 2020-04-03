<template>
  <div class="container">
    <h1>Gerenciar Entidades</h1>

    <button v-if="!creatingEntity" class="btn btn-success m-1" @click="creatingEntity=true"><span class="fa fa-plus-square"></span> Nova entidade</button>
    <button v-if="!creatingEntity" class="btn btn-primary m-1"><span class="fa fa-sync"></span></button>
    <div v-if="creatingEntity">
      <hr/>
      <form>
        <h3 class="py-2">Insira as informações referente à entidade</h3>
        <div class="row">
          <div class="form-group col-8">
            <label>Nome</label>
            <input
              type="text"
              v-model="entityData.name"
              class="form-control"
              placeholder="Nome da entidade"
            />
          </div>
          <div class="form-group col">
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
        <div class="row">
          <div class="form-group col-8">
            <label for="Description">Endereço</label>
            <input
              type="text"
              class="form-control"
              placeholder="Ex: Rua Exemplo, 1029"
              v-model="entityData.street_address"
            />
          </div>
          <div class="form-group col-3">
            <label>Cidade</label>
            <input
              type="text"
              class="form-control"
              v-model="entityData.city"
            />
          </div>
          <div class="form-group col">
            <label>Estado</label>
            <input
              type="text"
              class="form-control"
              placeholder="XX"
              v-model="entityData.state"
            />
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
      </form>
        <div class="d-flex justify-content-end">
          <button class="btn btn-danger" @click="creatingEntity=false">Cancelar</button>
          <button class="btn btn-success ml-1" @click="createEntity()">Criar</button>
        </div>
    </div>
    <hr/>
    <entity-card 
      v-for="entity in entities" 
      v-bind:key="entity.id"
      :entity="entity"
      :onUpdateEntityCB="updateEntity"
      :onLeaveEntityCB="leaveEntity"
      :onSelectEntityCB="selectEntity"
      :isActiveEntityCB="isActiveEntity"
      >

    </entity-card>
  </div>
</template>

<script>
import api from "../../api";
import Entity from "./Entity";

export default {
  name: 'SelectEntity',
  components:{
    "entity-card":Entity
  },
  data: () =>({
    creatingEntity: false,
    entityData:{
      cnpj: "", 
      name: "", 
      legal_name: "", 
      description: "", 
      street_address: "",
      city: "",
      state: ""
    }
  }),
  methods: {
    selectEntity: function(entityId, redirect = false) {
      this.$store.commit('setEntity', entityId);
      if (redirect) {
        this.$router.push('/gerenciar-demandas')
      }
    },

    isActiveEntity: function(entityId) {
      return this.$store.getters.activeEntityId === entityId ? "active" : '';
    },

    createEntity: function(){
      api.createEntity(this.entityData).then(res => {
        console.log(res);
        this.creatingEntity = false;
        this.entities.push(this.entityData); //SOLUCAO PROVISORIA!
      }).catch(err=>{console.log(err);});
    },

    updateEntity: function(entityId, data) {
      const current = this.entities.find(entity => entity.id === entityId);
      api.updateEntity(entityId, data).then(response => {
        console.log(response);
      });
    },

    leaveEntity: function(entityId){
      api.leaveEntity(entityId).catch(err=>{
        window.alert("Você é o último usuário permanecente nessa entidade, é necessário que pelo menos um usuário permaneça na entidade");
      })
    },
    inviteEntity: function(entityId, userId){
      api.inviteToEntity(entityId, userId)
    },
  },
  computed: {
    entities: function() {
      return this.$store.getters.entities;
    }
  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.active {
  font-weight: bold;
}
</style>
