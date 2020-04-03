<template>
  <div class="container">
    <h1>Gerenciar Entidades</h1>

    <button class="btn btn-success m-1"><span class="fa fa-plus-square"></span> Nova entidade</button>
    <button class="btn btn-primary m-1"><span class="fa fa-sync"></span></button>
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
export default {
  name: 'SelectEntity',
  methods: {
    selectEntity: function(entityId, redirect = false) {
      this.$store.commit('setEntity', entityId);
      if (redirect) {
        this.$router.push('/gerenciar-demandas')
      }
    },
    isActiveEntity: function(entityId) {
      return this.$store.getters.activeEntityId === entityId ? "active" : '';
    }
  },
  computed: {
    entities: function() {
      return this.$store.getters.entities;
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.active {
  font-weight: bold;
}
</style>
