<template>
  <div class="home">
    <div class="form-group">
      <input type="email" class="form-control w-50 m-auto" aria-describedby="emailHelp" placeholder="Busca..." v-model="query">
      <small id="emailHelp" class="form-text text-muted">Começe a digitar para filtar os resultados.</small>
    </div>
    <loading-widget v-if="demands.length === 0"></loading-widget>
    <div class="results" v-else>
      <div class="card m-3" v-for="demand in demands">
        <div class="card-header">
          <h4>{{ demand.title}}</h4>
        </div>
        <div class="card-body">
          {{ demand.text}}
          <hr v-if="demand.quantity"/>
          <p v-if="demand.quantity">Quantidade: {{ demand.quantity }}</p>
        </div>
        <div class="card-footer">{{ demand.entity.name }} - {{ demand.entity.city }} - {{ demand.entity.state}}</div>
      </div>
    </div>
  </div>
</template>

<script>
import api from "../../api";
import _ from 'lodash';
import LoadingWidget from "../widgets/LoadingWidget";

export default {
  name: 'Home',
  components: {LoadingWidget},
  data() {
    return {
      query: '',
      demands: []
    }
  },
  methods: {
    search: function(query) {
      api.searchDemands(this.query).then(({data}) => {
        this.demands = data.data;
      });
    },
  },
  watch: {
    query: _.debounce(function() {
      this.search()
    }, 300),
  },
  mounted: function () {
    this.search();
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.home {
  text-align: center;
}
.results {
  text-align: left;
}
h3 {
  margin: 40px 0 0;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
</style>
