<template>
  <div>
    <v-row justify="center">
      <!-- update -->
      <v-dialog v-model="update" max-width="600px">
        <v-card>
          <v-card-title>
            <span class="headline">Alterar Entidade</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-form ref="form">
                <v-row>
                  <v-col cols="12" sm="8" md="8">
                    <v-text-field
                      ref="name"
                      v-model="tempEntity.name"
                      :rules="[rules.required]"
                      label="Nome"
                      placeholder="Nome da entidade"
                      outlined
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="4" md="4">
                    <v-text-field outlined v-model="tempEntity.cnpj" disabled label="CNPJ"></v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-text-field
                      ref="legal_name"
                      v-model="tempEntity.legal_name"
                      :rules="[rules.required]"
                      label="Razão Social"
                      placeholder="Insira a razão social da entidade"
                      outlined
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-text-field
                      ref="address"
                      v-model="tempEntity.street_address"
                      :rules="[rules.required]"
                      label="Endereço"
                      placeholder="Ex: Rua Exemplo, 1029"
                      outlined
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <v-select
                      label="Estado"
                      :items="states"
                      v-model="tempEntity.state_id"
                      :loading="!statesFetched"
                      outlined
                      item-text="uf"
                      item-value="id"
                    ></v-select>
                  </v-col>
                  <v-col cols="12">
                    <DistrictSelector
                      :stateId="tempEntity.state_id"
                      :disabled="tempEntity.state_id == 0"
                      :onChangeCB="onCityChange"
                      :defaultValue="{name:tempEntity.city, id:tempEntity.district_id}"
                    />
                  </v-col>
                  <v-col cols="12">
                    <v-textarea
                      ref="description"
                      rows="3"
                      auto-grow
                      label="Adicione uma descrição"
                      placeholder="(Mínimo de 10 caracteres) Adicione uma descrição sobre a entidade"
                      v-model="tempEntity.description"
                      :rules="[rules.required]"
                      outlined
                    ></v-textarea>
                  </v-col>
                </v-row>
              </v-form>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="red" dark @click="update = false">Cancelar</v-btn>
            <v-btn :loading="loading" color="warning" @click="handleUpdateEntity">
              <span class="fa fa-edit"></span>Alterar
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>

    <v-row justify="center">
      <!-- Sair -->
      <v-dialog v-model="del" max-width="300px">
        <v-card>
          <v-card-title>
            <span class="headline">Sair da entidade</span>
          </v-card-title>
          <v-card-text>Deseja realmente sair da entidade {{ entity.name }}?</v-card-text>
          <v-card-actions>
            <v-btn @click="del=false" class="btn btn-danger float-right">Cancelar</v-btn>
            <v-spacer></v-spacer>
            <v-btn :loading="loading" @click="handleExit" color="red" dark>
              <span class="fa fa-door-open"></span>Sair
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>

    <v-row justify="center">
      <!-- Invitar -->
      <v-dialog v-model="invite" max-width="500px">
        <v-card>
          <v-card-title>
            <span class="headline">Convidar Usuário</span>
          </v-card-title>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field v-model="email" outlined label="Email"></v-text-field>
                <span class="text-muted small">
                  Nota: o email inserido deve estar registrado! Caso não
                  esteja, favor entrar em contato com o dono do email, e solicitar ao mesmo para se registrar no sistema.
                </span>
              </v-col>
            </v-row>
          </v-container>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn @click="invite=false" color="red" dark>Cancelar</v-btn>
            <v-btn :loading="loading" @click="handleInvite" color="primary" dark>
              <span class="fa fa-user-plus"></span>Convidar
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-row>

    <div class="card mt-2">
      <div class="card-header">
        <h3>
          {{ entity.name }}&nbsp;
          <v-chip class="mx-2" small>{{entity.entity_type}}</v-chip>
          <v-chip small v-if="isActiveEntityCB(entity.id)" color="success">Ativo</v-chip>
        </h3>
      </div>
      <div class="card-body">
        <p>
          <strong>CNPJ:</strong>
          {{ entity.cnpj }}
        </p>
        <p>
          <strong>Razão Social:</strong>
          {{ entity.legal_name }}
        </p>
        <p v-if="entity.entity_type_document !== null">
          <strong>{{entity.entity_type_document_label}}:</strong>
          {{ entity.entity_type_document }}
        </p>
        <hr />
        {{ entity.description }}
        <hr />
        {{ entity.street_address }} - {{ entity.city}}
      </div>
      <div class="card-footer">
        <div class="row">
          <div class="col-md col-sm-12 col-12 p-1">
            <v-btn
              class="btn btn-success w-100"
              v-if="!isActiveEntityCB(entity.id)"
              @click="onSelectEntityCB(entity.id)"
            >
              <span class="fa fa-check-double"></span> Ativar
            </v-btn>
            <v-btn
              class="btn btn-success w-100"
              v-if="isActiveEntityCB(entity.id)"
              @click="onSelectEntityCB(entity.id)"
              disabled
            >Ativo</v-btn>
          </div>
          <div class="col-md col-sm-6 col-6 p-1">
            <v-btn class="btn btn-primary w-100" @click="onSelectEntityCB(entity.id, true)">
              <span class="fa fa-syringe"></span> Gerenciar Demandas
            </v-btn>
          </div>
          <div class="col-md col-sm-6 col-6 p-1">
            <v-btn class="btn btn-primary w-100" @click="invite=true">
              <span class="fa fa-user-plus"></span> Convidar Usuário
            </v-btn>
          </div>
          <div class="col-md col-sm-6 col-6 p-1">
            <v-btn class="btn btn-warning w-100" @click="openUpdateDialog">
              <span class="fa fa-edit"></span> Alterar
            </v-btn>
          </div>
          <div class="col-md col-sm-6 col-6 p-1">
            <v-btn @click="del=true" class="btn btn-danger w-100">
              <span class="fa fa-door-open"></span> Sair
            </v-btn>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from "./../../api";
import rules from "../../util/rules";
import DistrictSelector from "../widgets/DistrictSelector";

export default {
  mixins: [rules],
  components: { DistrictSelector },
  props: {
    entity: {
      type: Object,
      required: true
    },
    onUpdateEntityCB: {
      type: Function,
      required: true
    },
    onLeaveEntityCB: {
      type: Function,
      required: true
    },
    onSelectEntityCB: {
      type: Function,
      required: true
    },
    isActiveEntityCB: {
      type: Function,
      required: true
    },
    onInviteUserCB: {
      type: Function,
      required: true
    }
  },
  data() {
    return {
      tempEntity: {},
      states: [],
      cities: [],
      loading: false,
      update: false,
      del: false,
      invite: false,
      statesFetched: false,
      email: ""
    };
  },
  computed: {
    entityPayload() {
      let entity = this.tempEntity;
      let payload = {
        entity_type_id: entity.entity_type_id,
        cnpj: entity.cnpj,
        name: entity.name,
        legal_name: entity.legal_name,
        description: entity.description,
        street_address: entity.street_address,
        district_id: entity.district_id
      };
      if (entity.entity_type_document)
        payload.entity_type_document = entity.entity_type_document;

      return payload;
    }
  },
  methods: {
    openUpdateDialog() {
      this.tempEntity = JSON.parse(JSON.stringify(this.entity));
      if (this.statesFetched == false) this.fetchStates();
      let teste = this.entity.city.split(" - ");
      this.tempEntity.city = teste[0];
      this.tempEntity.state = teste[1];
      this.update = true;
    },
    validate() {
      return this.$refs.form.validate();
    },
    handleUpdateEntity(ev) {
      ev.preventDefault();
      if (this.validate()) {
        this.loading = true;
        this.onUpdateEntityCB(this.entity.id, this.entityPayload)
          .then(() => {
            this.$store.commit("showMessage", {
              content: "Entidade alterada com sucesso!",
              error: false
            });
          })
          .catch(e => {
            this.$store.commit("showMessage", { content: "Erro", error: true });
          })
          .finally(() => {
            this.loading = false;
            this.update = false;
          });
      }
    },
    handleExit() {
      this.loading = true;
      this.onLeaveEntityCB(this.entity.id)
        .then(() => {
          this.$store.commit("showMessage", {
            content: `Você saiu da entidade ${this.entity.name}!`,
            error: false
          });
        })
        .catch(e => {
          this.$store.commit("showMessage", {
            content:
              "Você é o último usuário remanescente na entidade, não é possível sair.",
            error: true
          });
        })
        .finally(() => {
          this.loading = false;
          this.del = false;
        });
    },
    handleInvite() {
      //VALIDAR!!!!!!!!
      this.loading = true;
      this.onInviteUserCB(this.entity.id, this.email)
        .then(() => {
          this.$store.commit("showMessage", {
            content: `Usuário adicionado com sucesso!`,
            error: false
          });
        })
        .catch(e => {
          this.$store.commit("showMessage", { content: `Erro`, error: true });
        })
        .finally(() => {
          this.loading = false;
          this.invite = false;
          this.email = "";
        });
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
    onCityChange(city) {
      this.tempEntity.district_id = city.id;
    }
  }
};
</script>

<style>
</style>