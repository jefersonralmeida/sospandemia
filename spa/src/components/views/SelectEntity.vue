<template>
  <div class="hello">
    <h1>Selecionar Entidade</h1>
    <ul>
      <li v-for="entity in entities">

        <a href="#" @click="selectEntity(entity.id)" :class="activeEntityClass(entity.id)"> {{ entity.name }}</a>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'SelectEntity',
  methods: {
    selectEntity: function(entityId) {
      this.$store.commit('setEntity', entityId);
      this.$router.push('/gerenciar-demandas')
    },
    activeEntityClass: function(entityId) {
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
