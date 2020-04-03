<template>
  <div class="container">
    <h1>Gerenciar Entidades</h1>

    <button v-if="!creatingEntity" class="btn btn-success m-1" @click="creatingEntity=true;"><span class="fa fa-plus-square"></span> Nova entidade</button>
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
            placeholder="Adicione uma descrição, descrevendo por exemplo o que a entidade faz, pelo que é responsável, etc."
            v-model="entityData.description"
          />
        </div>
        <div class="d-flex justify-content-end">
          <button class="btn btn-danger" @click="creatingEntity=false">Cancelar</button>
          <button class="btn btn-success ml-1">Criar</button>
        </div>
      </form>
    </div>
    <hr/>
    <div class="card mt-2" v-for="entity in entities">
      <div class="card-header">
        <h3>{{ entity.name }}&nbsp;<span style="font-size: 14px;" class="badge badge-pill badge-success" v-if="isActiveEntity(entity.id)">Ativo</span></h3>
      </div>
      <div class="card-body">
        <p><strong>CNPJ:</strong> {{ entity.cnpj }}</p>
        <p><strong>Razão Social:</strong> {{ entity.cnpj }}</p>
        <hr/>
        {{ entity.description }}
        <hr/>
        {{ entity.street_address }} - {{ entity.city}} - {{entity.state}}
      </div>
      <div class="card-footer">
        <button class="btn btn-success m-1" v-if="!isActiveEntity(entity.id)" @click="selectEntity(entity.id)"><span class="fa fa-check-double"></span> Ativar</button>
        <button class="btn btn-primary m-1" @click="selectEntity(entity.id, true)"><span class="fa fa-syringe"></span> Gerenciar Demandas</button>
        <button class="btn btn-primary m-1"><span class="fa fa-user-plus"></span> Convidar Usuário</button>
        <button class="btn btn-warning m-1"><span class="fa fa-edit"></span> Alterar</button>
        <button class="btn btn-danger m-1"><span class="fa fa-door-open"></span> Sair</button>
      </div>
    </div>
  </div>
</template>

<script>
import api from "../../api";
export default {
  name: 'SelectEntity',
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
    createEntity: function(data){
      
       /* data = {
        cnpj: "13590585000199", //14 digitos
        name: "Entidade nova", //minimo 4
        legal_name: "blablabla - Nome legal", //min 4
        description: "Uma descrição bacanuda", //min 10
        street_address: "Uma rua qlqr", //min 10,
        city: "Ponta Grossa",
        state: "PR" //sigla
      } */
      
      api.createEntity(data);
    },
    leaveEntity: function(entityId){
      api.leaveEntity(entityId)
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
  watch:{
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
