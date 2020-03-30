<template>
  <div class="hello">
    <h1>Gerenciar Demandas</h1>
    <p>Listar aqui as demandas da entidade <strong>{{ activeEntity.name }}</strong></p>
    <p>Lista paginada... Incluir opções de incluir, editar, excluir, resolver..., CRUD completo.</p>
    <ul>
      <li v-for="demand in demands">
        <h4>{{ demand.title }}</h4>
        <p>{{ demand.text }}</p>
      </li>
    </ul>
  </div>
</template>

<script>
import api from "../../api";

export default {
  name: 'Home',
  data: () => ({
    demands: []
  }),
  methods: {
    loadDemands: function() {
      api.demandsIndex().then(({data}) => {
        this.demands = data.data;
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
      this.loadDemands();
    }
  },
  mounted() {
    this.loadDemands();
  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
