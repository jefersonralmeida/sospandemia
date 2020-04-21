<template>
  <v-form ref="form">
    <div class="row">
      <div class="form-group col-6">
        <v-text-field
          v-model="entity.cnes"
          label="CNES"
          placeholder="informe o CNES"
          type="number"
          :rules="[rules.required, (value) => rules.min(value, 7), (value) => rules.max(value, 7)]"
          outlined
        ></v-text-field>
      </div>
      <div class="form-group col-6">
        <v-text-field
          v-model="entity.cnpj"
          type="number"
          label="CNPJ"
          placeholder="informe o CNPJ"
          :rules="[rules.required, (value) => rules.min(value, 14), (value) => rules.max(value, 14)]"
          outlined
        ></v-text-field>
      </div>
    </div>
    <div class="form-group">
      <v-text-field v-model="entity.name" outlined readonly label="Nome"></v-text-field>
    </div>
    <div class="form-group">
      <v-text-field v-model="entity.legal_name" outlined readonly label="Razão Social"></v-text-field>
    </div>
    <div class="row">
      <div class="form-group col-4 col-md-4">
        <v-text-field
          v-model="entity.state"
          :rules="[rules.required]"
          outlined
          readonly
          label="Estado"
        ></v-text-field>
      </div>
      <div class="form-group col-8 col-md-8">
        <v-text-field
          v-model="entity.city"
          :rules="[rules.required]"
          outlined
          readonly
          label="Cidade"
        ></v-text-field>
      </div>
    </div>
    <div class="form-group">
      <v-select
        v-model="entity.district"
        :rules="[rules.required]"
        :items="cities"
        item-text="name"
        item-value="id"
        label="Distrito"
        outlined
      ></v-select>
    </div>
    <div class="form-group">
      <v-text-field
        ref="address"
        v-model="entity.street_address"
        :rules="[rules.required, rules.min]"
        label="Endereço"
        placeholder="Ex: Rua Exemplo, 1029"
        outlined
      ></v-text-field>
    </div>
    <div class="form-group">
      <v-text-field
        ref="contact_info"
        v-model="entity.contact_info"
        label="Contato"
        placeholder="Ex: tel: (00) 0000-0000, email: example@example.ex"
        outlined
      ></v-text-field>
    </div>
    <div class="form-group">
      <v-textarea
        rows="3"
        auto-grow
        :rules="[rules.required, rules.min]"
        label="Adicione um descrição"
        placeholder="(Mínimo de 10 caracteres) Adicione uma descrição, descrevendo por exemplo o que a entidade faz, pelo que é responsável, etc."
        v-model="entity.description"
        outlined
      ></v-textarea>
    </div>
    <div class="d-flex justify-content-end">
      <v-btn color="red" dark @click="onCancel">Cancelar</v-btn>
      <v-btn
        color="success"
        :loading="loading"
        class="ml-1"
        @click="handleSubmit"
      >Criar</v-btn>
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
    }
  },
  data() {
    return {
      entity: {
        cnpj: "",
        name: "",
        legal_name: "",
        contact_info: "",
        description: "",
        street_address: "",
        city: "",
        state: "",
        district: null
      },
      statesFetched: false,
      states: [],
      cities: [],
      search: null,
      loading: false,
      debounce: null,
      noDataText: "Continue digitando para encontrar uma cidade."
    };
  },
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
    handleSubmit(){
        if(!this.isValidForm()) return

        this.onSubmit(this.entityPayload)
    },
    isValidForm(){
        return this.$refs.form.validate()
    }
  },
  mixins: [rules],
  computed: {
    entityPayload() {
      const entity = this.entity;
      return {
        entity_type_id: 1,
        entity_type_document: entity.cnes,
        cnpj: entity.cnpj,
        name: entity.name,
        contact_info: entity.contact_info,
        legal_name: entity.legal_name,
        description: entity.description,
        street_address: entity.street_address,
        district_id: entity.district
      };
    }
  },
  watch: {
    "entity.cnes"(cnes) {
      if (cnes.length != 7) return;

      return api
        .getEntityByCNES(cnes)
        .then(res => {
          var cnesResponse = res.data;
          this.entity.name = cnesResponse.name;
          this.entity.legal_name = cnesResponse.legal_name;
          var state = this.states.find(
            element => element.uf == cnesResponse.uf
          );
          this.entity.state = state.name;
          this.entity.city = cnesResponse.district_name;

          const that = this;
          this.fetchCities(state.id, cnesResponse.district_name).then(res => {
            that.cities = res.data;
          });
        })
        .catch(e => {
          this.$store.commit("showMessage", {
            content: `Problemas ao identificar o CNES!`,
            error: true
          });
        });
    }
  },
  created() {
    this.fetchStates();
  }
};
</script>

<style>
</style>