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
                    <!--<v-text-field 
                      v-model="tempEntity.name" 
                      label="Nome">
                    </v-text-field>-->
                    <v-text-field
                      ref="name"
                      v-model="tempEntity.name"
                      :counter="200"
                      :rules="[rules.required]"
                      label="Nome"
                      placeholder="Nome da entidade"
                      outlined
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="4" md="4">
                    <v-text-field v-model="tempEntity.cnpj" disabled label="CNPJ"></v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <!--<v-text-field 
                    v-model="tempEntity.legal_name" 
                    label="Razão Social"></v-text-field>-->
                    <v-text-field
                      ref="legal_name"
                      v-model="tempEntity.legal_name"
                      :counter="300"
                      :rules="[rules.required]"
                      label="Razão Social"
                      placeholder="Insira a razão social da entidade"
                      outlined
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <!--<v-text-field
                    v-model="tempEntity.street_address" 
                    label="Endereço"></v-text-field>-->
                    <v-text-field
                      ref="address"
                      v-model="tempEntity.street_address"
                      :counter="300"
                      :rules="[rules.required]"
                      label="Endereço"
                      placeholder="Ex: Rua Exemplo, 1029"
                      outlined
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" sm="3" md="2">
                    <v-select v-model="tempEntity.state" :items="states" label="Estado"></v-select>
                  </v-col>
                  <v-col cols="12" sm="9" md="10">
                    <v-text-field v-model="tempEntity.city" label="Cidade"></v-text-field>
                  </v-col>
                  <v-col cols="12">
                    <!--<v-textarea 
                    v-model="tempEntity.description" 
                    auto-grow 
                    label="Descrição">
                    </v-textarea>-->
                    <v-textarea
                      ref="description"
                      rows="3"
                      auto-grow
                      :counter="500"
                      label="Adicione um descrição"
                      placeholder="(Mínimo de 10 caracteres) Adicione uma descrição, descrevendo por exemplo o que a entidade faz, pelo que é responsável, etc."
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
            <v-btn color="warning" @click="handleUpdateEntity">
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
            <v-btn @click="handleExit" color="red" dark>
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
                <v-text-field v-model="email" label="email" hint="ex: exemplo@exemplo.exp" required></v-text-field>
              </v-col>
            </v-row>
          </v-container>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn @click="invite=false" color="red" dark>Cancelar</v-btn>
            <v-btn @click="handleInvite" color="primary" dark>
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
          <span
            style="font-size: 14px;"
            class="badge badge-pill badge-success"
            v-if="isActiveEntityCB(entity.id)"
          >Ativo</span>
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
        <hr />
        {{ entity.description }}
        <hr />
        {{ entity.street_address }} - {{ entity.city}} - {{entity.state}}
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
export default {
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
      states: [
        "AC",
        "AL",
        "AM",
        "AP",
        "BA",
        "CE",
        "DF",
        "ES",
        "GO",
        "MA",
        "MG",
        "MS",
        "MT",
        "PA",
        "PB",
        "PE",
        "PI",
        "PR",
        "RJ",
        "RN",
        "RO",
        "RR",
        "RS",
        "SC",
        "SE",
        "SP",
        "TO"
      ],
      update: false,
      del: false,
      invite: false,
      email: "",
      rules: {
        min: v => v.length >= 1 || "Minimo 15 caracteres",
        required: value => !!value || "Obrigatório.",
        numberRule: v => {
          if (parseInt(v) && v >= 1) return true;
          return "O campo deve conter apenas números.";
        }
      }
    };
  },
  methods: {
    openUpdateDialog() {
      this.tempEntity = JSON.parse(JSON.stringify(this.entity));
      this.update = true;
    },
    validate() {
      console.log(this.$refs);
      return this.$refs.form.validate();
    },
    handleUpdateEntity(ev) {
      ev.preventDefault();
      //Validar dados
      if (this.validate()) {
        this.entity.name = this.tempEntity.name;
        this.entity.legal_name = this.tempEntity.legal_name;
        //this.entity.cnpj = this.tempEntity.cnpj;
        this.entity.street_address = this.tempEntity.street_address;
        this.entity.city = this.tempEntity.city;
        this.entity.state = this.tempEntity.state;
        this.entity.description = this.tempEntity.description;

        this.onUpdateEntityCB(this.entity.id, this.entity);
        this.update = false;
      }
    },
    handleExit() {
      this.onLeaveEntityCB(this.entity.id);
      this.del = false;
    },
    handleInvite() {
      this.onInviteUserCB(this.entity.id, this.email);
      this.invite = false;
    }
  }
};
</script>

<style>
</style>