<template>
  <div class="container">
    <h1>Gerenciar Entidades</h1>

    <button v-if="!creatingEntity" class="btn btn-success m-1" @click="handleOpenCreateEntityPanel">
      <span class="fa fa-plus-square"></span> Nova entidade
    </button>

    <v-select
      v-if="creatingEntity"
      label="Tipo de Entidade"
      v-model="entityType"
      :items="entityTypes"
      outlined
    ></v-select>

    <hr />
    <div v-if="creatingEntity && entityType=='Unidade de Saúde'">
      <h3 class="py-2">Insira as informações referente à entidade</h3>
      <FormUS ref="formUS" :onSubmit="createEntity" :onCancel="handleCreateCancel" :loading="loading" />
    </div>
    <div v-if="creatingEntity && entityType=='Outras'">
      <FormOutros ref="formOutros" :onSubmit="createEntity" :onCancel="handleCreateCancel" :loading="loading" />
    </div>
    <hr />

    <entity-card
      v-for="entity in entities"
      v-bind:key="entity.id"
      :entity="entity"
      :onUpdateEntityCB="updateEntity"
      :onLeaveEntityCB="leaveEntity"
      :onSelectEntityCB="selectEntity"
      :isActiveEntityCB="isActiveEntity"
      :onInviteUserCB="inviteEntity"
    ></entity-card>
  </div>
</template>

<script>
import api from "../../api";
import Entity from "./Entity";
import FormOutros from "../forms/Outros";
import FormUS from "../forms/UnidadeSaude";
import validation from '../../util/validation'

export default {
  mixins: [validation],
  name: "SelectEntity",
  components: {
    "entity-card": Entity,
    FormOutros,
    FormUS
  },
  data: () => ({
    others: null,
    entityTypes: [],
    entityType: "",
    creatingEntity: false,
    loading: false
  }),
  methods: {
    selectEntity: function(entityId, redirect = false) {
      this.$store.commit("setEntity", entityId);
      if (redirect) {
        this.$router.push("/gerenciar-demandas");
      }
    },
    handleCreateCancel: function(ev) {
      ev.preventDefault();
      this.creatingEntity = false;
    },
    handleOpenCreateEntityPanel() {
      this.getEntityTypes();
      this.creatingEntity = true;
    },
    isActiveEntity: function(entityId) {
      return this.$store.getters.activeEntityId === entityId ? "active" : "";
    },
    getEntityTypes: function() {
      return api.getEntityTypes().then(res => {
        this.entityTypes = Object.values(res.data);
      });
    },
    createEntity: function(entity) {
      this.loading = true;
      api
        .createEntity(entity)
        .then(res => {
          this.$store.commit("showMessage", {
            content: `Entidade criada com sucesso!`,
            error: false
          });
          this.$store.dispatch("loadProfile");
          this.creatingEntity = false;
        })
        .catch(e => {
          this.$store.commit("showMessage", {
            content: "Não foi possivel criar a entidade.",
            error: true
          });
          if(this.entityType=='Outras')
            this.handleResponseError(e, this.$refs.formOutros.$refs)
          else
            this.handleResponseError(e, this.$refs.formUS.$refs)
        })
        .finally(() => {
          this.loading = false;
          this.$store.dispatch("loadProfile");
        });

    },
    updateEntity: function(entityId, data) {
      const current = this.entities.find(entity => entity.id === entityId);
      return api.updateEntity(entityId, data).then(response => {
        this.$store.dispatch("loadProfile");
      });
    },

    leaveEntity: function(entityId) {
      return api.leaveEntity(entityId).then(() => {
        this.$store.dispatch("loadProfile");
      });
    },
    inviteEntity: function(entityId, userId) {
      return api.inviteToEntity(entityId, userId);
    }
  },
  watch: {
    entities: function() {
      if (this.entities.length == 1) {
        this.selectEntity(this.entities[0].id, true);
      }
    }
  },
  computed: {
    entities: function() {
      return this.$store.getters.entities;
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.active {
  font-weight: bold;
}
</style>
