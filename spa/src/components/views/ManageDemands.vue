<template>
  <div class="hello">
    <h1>Gerenciar Demandas</h1>
    <p>Listar aqui as demandas da entidade <strong>{{ activeEntity.name }}</strong></p>
    <p>Lista paginada... Incluir opções de incluir, editar, excluir, resolver..., CRUD completo.</p>
  </div>
</template>

<script>
export default {
  name: 'Home',
  computed: {
    activeEntity: function() {
      return this.$store.getters.activeEntity;
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
