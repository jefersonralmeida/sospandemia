<template>
  <v-form ref="form">
    <h3 class="py-2">Insira as informações referente à entidade</h3>
    <div class="row">
      <div class="form-group col-sm-8 col-md-8 col-12">
        <v-text-field
          ref="name"
          v-model="entity.name"
          :rules="[rules.required, (value) => rules.min(value, 8), (value) => rules.max(value, 100)]"
          :counter="100"
          label="Nome"
          placeholder="Nome da entidade"
          outlined
        ></v-text-field>
      </div>
      <div class="form-group col-sm-4 col-md-4 col-12">
        <v-text-field
          ref="cnpj"
          v-model="entity.cnpj"
          :rules="[rules.required, (value) => rules.min(value, 14), (value) => rules.max(value, 14)]"
          label="CNPJ"
          placeholder="informe o CNPJ"
          outlined
        ></v-text-field>
      </div>
    </div>
    <div class="form-group">
      <v-text-field
        ref="legal_name"
        v-model="entity.legal_name"
        :counter="300"
        :rules="[rules.required, (value) => rules.min(value, 15), (value) => rules.max(value, 300)]"
        label="Razão Social"
        placeholder="Insira a razão social da entidade"
        outlined
      ></v-text-field>
    </div>
    <div class="form-group">
      <v-text-field
        ref="address"
        v-model="entity.street_address"
        :counter="300"
        :rules="[rules.required, (value) => rules.min(value, 15), (value) => rules.max(value, 300)]"
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
          v-model="entity.state"
          :loading="!statesFetched"
          outlined
          item-text="uf"
          item-value="id"
        ></v-select>
      </div>
      <div class="form-group col-md-10 col-sm-9 col-12">
        <v-autocomplete
          ref="city"
          v-model="entity.city"
          :disabled="entity.state == ''"
          :items="cities"
          item-text="name"
          label="Cidade"
          autocomplete="null"
          placeholder="Digite o nome da cidade para buscar"
          :search-input.sync="search"
          outlined
          :no-data-text="noDataText"
          hide-selected
          return-object
        ></v-autocomplete>
      </div>
    </div>
    <div class="form-group">
      <v-textarea
        ref="description"
        rows="3"
        auto-grow
        :counter="500"
        label="Adicione um descrição"
        placeholder="(Mínimo de 10 caracteres) Adicione uma descrição, descrevendo por exemplo o que a entidade faz, pelo que é responsável, etc."
        v-model="entity.description"
        :rules="[rules.required]"
        outlined
      ></v-textarea>
    </div>
    <div class="d-flex justify-content-end">
      <v-btn color="red" dark @click="onCancel">Cancelar</v-btn>
      <v-btn color="success" :loading="loading" class="ml-1" @click="handleSubmit">Criar</v-btn>
    </div>
  </v-form>
</template>

<script>
import api from "../../api";
import rules from "../../util/rules";

export default {
  props: {
    onSubmit: {
      type: Function,
      required: false,
      default: () => {}
    },
    onCancel: {
      type: Function,
      required: false,
      default: () => {}
    },
    loading: {
        type: Boolean,
        default: false
    }
  },
  data() {
    return {
      entity: {
        cnpj: "",
        name: "",
        legal_name: "",
        description: "",
        street_address: "",
        city: "",
        state: ""
      },
      statesFetched: false,
      states: [],
      cities: [],
      search: null,
      debounce: null,
      noDataText: "Continue digitando para encontrar uma cidade."
    };
  },
  computed: {
    entityPayload() {
      const entity = this.entity;
      return {
        entity_type_id: 0,
        cnpj: entity.cnpj,
        name: entity.name,
        legal_name: entity.legal_name,
        description: entity.description,
        street_address: entity.street_address,
        district_id: entity.city.id
      };
    }
  },
  mixins: [rules],
  methods: {
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
    fetchCities(stateId, query) {
      return api.getDistricts(stateId, query);
    },
    handleSubmit() {
      if (!this.isValidForm()) return;

      this.onSubmit(this.entityPayload);
    },
    isValidForm() {
      return this.$refs.form.validate();
    }
  },
  watch: {
    search(query) {
      if (query === null) return;
      if (query.length < 3) {
        this.noDataText = "Continue digitando para encontrar uma cidade.";
        return;
      }
      clearTimeout(this.debounce);
      let that = this;
      this.debounce = setTimeout(function() {
        that.fetchCities(that.entity.state, query).then(res => {
          that.cities = res.data;
          that.noDataText =
            "Nenhuma cidade encontrada. Verifique a busca ou seja mais específico";
        });
      }, 300);
    }
  },
  created() {
    this.fetchStates();
  }
};
</script>

<style>
</style>