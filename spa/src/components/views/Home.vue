<template>
  <div class="home">
    <div class="form-group">
      <input
        type="email"
        class="form-control w-50 m-auto"
        aria-describedby="emailHelp"
        placeholder="Busca..."
        v-model="query"
      />
      <small id="emailHelp" class="form-text text-muted">Começe a digitar para filtar os resultados.</small>
    </div>
    <v-btn text @click="handleAdvancedFilterState">
      <span>Filtro avançado</span>
    </v-btn>
    <div v-if="showFilter === true">
      <v-row>
        <v-col cols="2">
          <v-select
            label="Estado"
            :items="states"
            v-model="filterOptions.state"
            :loading="!statesFetched"
            outlined
            item-text="uf"
            item-value="id"
          ></v-select>
        </v-col>
        <v-col cols="10">
          <v-autocomplete
            ref="city"
            v-model="filterOptions.city"
            :disabled="filterOptions.state == ''"
            :items="cities"
            item-text="name"
            label="Cidade"
            autocomplete="dskjalçkdwlçakdwlça"
            placeholder="Digite o nome da cidade para buscar"
            :search-input.sync="searchCity"
            outlined
            hide-no-data
            hide-selected
            return-object
          ></v-autocomplete>
        </v-col>
      </v-row>
      <small id="emailHelp" class="form-text text-muted">
        Buscando resultados em
        <span v-if="filterOptions.state === null">Brasil.</span>
        <span
          v-else-if="this.filterOptions.city != null &&
          typeof this.filterOptions.city == 'object'"
        >{{this.filterOptions.city.name}}.</span>
        <span v-else>{{stateName}}</span>
      </small>
    </div>
    <hr />
    <loading-widget v-if="widgetLoading==true"></loading-widget>
    <p
      v-else-if="widgetLoading==false && demands.length === 0"
    >Não há demandas correspondente à busca!</p>
    <div class="results" v-else>
      <div class="card m-3" v-for="demand in demands">
        <div class="card-header">
          <h4>{{ demand.title}}</h4>
        </div>
        <div class="card-body">
          {{ demand.text}}
          <hr v-if="demand.quantity" />
          <p v-if="demand.quantity">Quantidade: {{ demand.quantity }}</p>
        </div>
        <div
          class="card-footer"
        >{{ demand.entity.name }} - {{ demand.entity.city }} - {{ demand.entity.state}}</div>
      </div>
    </div>
  </div>
</template>

<script>
import api from "../../api";
import _ from "lodash";
import LoadingWidget from "../widgets/LoadingWidget";

export default {
  name: "Home",
  components: { LoadingWidget },
  data() {
    return {
      query: "",
      widgetLoading: false,
      demands: [],
      showFilter: false,
      statesFetched: false,
      states: [],
      cities: [],
      searchCity: null,
      debounce: null,
      filterOptions: {
        state: null,
        city: null
      }
    };
  },
  computed: {
    stateName: function() {
      return this.states.find(el => {
        return this.filterOptions.state == el.id
      }).name
    }
  },
  methods: {
    search: function(query) { 
      this.widgetLoading = true;
      if (this.showFilter && this.filterOptions.state != null) {
        let filterType;
        let filterParam;
        if (
          this.filterOptions.city != null &&
          typeof this.filterOptions.city == "object"
        ) {
          filterType = "district_id";
          filterParam = this.filterOptions.city.id;
        } else {
          filterType = "state_id";
          filterParam = this.filterOptions.state;
        }
        api
          .searchDemands(this.query, filterType, filterParam)
          .then(({ data }) => {
            console.log(data)
            this.demands = data.data;
            this.widgetLoading = false;
          });
      } else {
        api.searchDemands(this.query).then(({ data }) => {
            console.log(data)
          this.demands = data.data;
          this.widgetLoading = false;
        });
      }
    },
    handleAdvancedFilterState() {
      this.showFilter = !this.showFilter;
      if (this.showFilter === false) return;

      if (this.statesFetched === false) this.fetchStates();
    },
    fetchStates() {
      api.getStates().then(res => {
        this.states = res.data;
        this.states.sort((a, b) => {
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
        console.log(this.states);
        this.statesFetched = true;
      });
    },
    fetchCities(stateId, query) {
      return api.getDistricts(stateId, query);
    }
  },
  watch: {
    query: _.debounce(function() {
      this.search();
    }, 300),
    searchCity(query) {
      console.log(query)
      if (query.length <= 3) return;
      clearTimeout(this.debounce);
      let that = this;
      this.debounce = setTimeout(function() {
        that.fetchCities(that.filterOptions.state, query).then(res => {
          that.cities = res.data;
        });
      }, 300);
    }
  },
  mounted: function() {
    this.search();
  }
};
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
