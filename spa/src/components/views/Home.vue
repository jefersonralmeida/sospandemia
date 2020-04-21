<template>
  <div class="home">
    <p>
      Primeira vez utilizando o sistema? <a @click="help=true">Clique aqui!</a>
    </p>
    <v-dialog v-model="help" max-width="600px">
      <v-card class="">
        <v-card-title class="headline">
          Bem vindo ao SOS Pandemia!
        </v-card-title>
        <v-card-text>
          Gostaria de cadastrar minha entidade para receber ajuda,  <router-link to="primeiros-passos">como proceder?</router-link> <a></a> <!-- adicionar link do video aqui @Hernani -->
        </v-card-text>
        <v-divider></v-divider>
        <v-card-text>
          Gostaria de ajudar entidades necessitadas, <a @click="donation = true">como proceder?</a>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-text>
          email: contato@sospandemia.org
        </v-card-text>
        <v-divider></v-divider>
        <v-card-text>
          <ul class="list-inline text-small">
            <li>Apoio</li>
              <li>
                <a class="text-muted" href="http://www.utfpr.edu.br/" target="_blank">
                  <img class="mb-2" src="../../assets/logo_utfpr.png" height="15" />
                </a>
              </li>
              <li>
                <a class="text-muted" href="http://www.uepg.br/" target="_blank">
                  <img class="mb-2" src="../../assets/logo_uepg.png" height="15" />
                </a>
              </li>
              <li>
                <a
                  class="text-muted"
                  href="https://www.facebook.com/escritoriodecriatividade/"
                  target="_blank"
                >
                  <img class="mb-2" src="../../assets/logo_criatividade.png" height="15" />
                </a>
              </li>
              <li>
                <a
                  class="text-muted"
                  href=" jeferson@webage.solutions" target="_blank"> <img class="mb-2" src="../../assets/logo_webAge.png" height="15" />
                </a>
              </li>
              <li><router-link to="sobre-nos">Equipe</router-link></li>
            </ul>

        </v-card-text>

        <v-card-text v-if="donation">
          Na página inicial, são encontradas as necessidades entidades que necessitam de doações, nela é possível buscar por uma necessidade específica, 
          filtrar as necessidades por estado e cidade, além de poder procurar por uma entidade específica.
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
              color="red darken-1"
              text
              @click="help = false"
            >
              Fechar
            </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <div class="form-group">
      <v-text-field
        type="email"
        class="w-50 m-auto"
        aria-describedby="emailHelp"
        placeholder="Busca..."
        outlined
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
            @change="search(query)"
            outlined
            item-text="uf"
            item-value="id"
          ></v-select>
        </v-col>
        <v-col cols="10">
          <DistrictSelector :stateId="filterOptions.state" :disabled="filterOptions.state == 0"  :onChangeCB="onCityChange"/>
        </v-col>
      </v-row>
      <small id="emailHelp" class="form-text text-muted">
        Buscando resultados em
        <span v-if="filterOptions.state === 0">Brasil.</span>
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
          <p v-if="demand.quantity">Quantidade: {{ demand.quantity }} {{ demand.unit }}</p>
          <hr v-if="demand.contact_info || demand.entity.contact_info" />
          <h5 v-if="demand.contact_info || demand.entity.contact_info">Informações de Contato:</h5>
          <hr v-if="demand.contact_info || demand.entity.contact_info" style="width: 300px"/>
          <p v-if="demand.entity.contact_info"><strong>Entidade:</strong> {{ demand.entity.contact_info }}</p>
          <p v-if="demand.contact_info"><strong>Específico da Demanda:</strong> {{ demand.contact_info }}</p>

        </div>
        <div
          class="card-footer"
        >{{ demand.entity.name }} - {{ demand.entity.city }}</div>
      </div>
    </div>
    <div class="text-center mt-2" v-if="widgetLoading==false && last_page>1">
    <v-pagination
      v-model="current_page"
      @input="search"
      :length="last_page"
    ></v-pagination>
  </div>
  </div>
</template>

<script>
import api from "../../api";
import _ from "lodash";
import LoadingWidget from "../widgets/LoadingWidget";
import DistrictSelector from "../widgets/DistrictSelector";

export default {
  name: "Home",
  components: { LoadingWidget, DistrictSelector },
  data() {
    return {
      help:false,
      donation:false,
      current_page:1,
      last_page:1,
      query: "",
      widgetLoading: false,
      demands: [],
      showFilter: false,
      statesFetched: false,
      states: [],
      filterOptions: {
        state: 0,
        city: null,
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
      if (this.showFilter && this.filterOptions.state != 0) {
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
          .searchDemands(this.query, 1, filterType, filterParam)
          .then(({ data }) => {
            this.demands = data.data;
            this.last_page = data.last_page;
            this.widgetLoading = false;
          });
      } else {
        api.searchDemands(this.query,this.current_page).then(({ data }) => {
          this.last_page = data.last_page;  
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
        this.statesFetched = true;
      });
    },
    onCityChange(city){
      this.filterOptions.city = city
    }
  },
  watch: {
    help: function(val){
      if(val == false){
        this.donation = false;
      }
    },
    query: _.debounce(function() {
      this.current_page = 1;
      this.search();
    }, 300),
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
