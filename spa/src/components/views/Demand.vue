<template>
  <div>
    <v-dialog v-model="update" max-width="600px">
      <v-card>
        <v-card-title>
          <span class="headline">Alterar Demanda</span>
        </v-card-title>
        <v-card-text class="py-0">
          <v-container>
            <v-form ref="form">
              <div class="form-group">
                <v-text-field
                  v-model="tempDemand.title"
                  :rules="[rules.required]"
                  label="Demanda"
                  outlined
                ></v-text-field>
              </div>
              <div class="form-group">
                <v-text-field
                  type="number"
                  v-model="tempDemand.quantity"
                  :rules="[rules.numberRule,rules.required]"
                  label="Quantidade"
                  outlined
                ></v-text-field>
              </div>
              <div class="form-group">
                <v-textarea
                  rows="4"
                  label="Adicione um descrição"
                  outlined
                  v-model="tempDemand.text"
                  :rules="[rules.required]"
                ></v-textarea>
              </div>
            </v-form>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn @click="update=false" class="float-right" color="red" dark>Cancelar</v-btn>
          <v-btn
            @click="handleUpdateDemand"
            color="success"
            class="float-right mx-2"
            :loading="loading"
          >Salvar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <modal :name="`deleteModal-${demand.id}`" :width="300" :height="150">
      <div class="container my-2 text-center">
        <h5>Você tem certeza que deseja remover essa demanda ?</h5>
        <div class="d-flex justify-content-between mt-4">
          <button
            @click="hidePopup('deleteModal')"
            class="btn btn-outline-danger float-right"
          >Cancelar</button>
          <v-btn
            @click="handleRemoveDemandConfirm"
            color="red darken-3"
            class="float-right mx-2 white--text"
            :loading="loading"
          >Remover</v-btn>
        </div>
      </div>
    </modal>

    <div class="card">
      <div class="card-body">
        <div class="card-title">
          <h5>{{ demand.title }}</h5>
        </div>
        <p class="card-text">{{ demand.text }}</p>
      </div>
      <div class="d-flex card-footer text-muted small">
        <div class="flex-grow-1 align-self-center">Quantidade: {{ demand.quantity }}</div>
        <button class="btn btn-warning btn-sm ml-1" @click="openUpdatePopup">Alterar</button>
        <button class="btn btn-danger btn-sm ml-1" @click="handleRemoveDemand">Excluir</button>
      </div>
    </div>
  </div>
</template>

<script>
import rules from "../../util/rules";
export default {
  mixins: [rules],
  props: {
    demand: {
      type: Object,
      required: true
    },
    onUpdateDemandCB: {
      type: Function,
      required: true
    },
    onDeleteDemandCB: {
      type: Function,
      required: true
    }
  },
  data() {
    return {
      tempDemand: {},
      update: false,
      loading: false
    };
  },
  methods: {
    showPopup(ModelName) {
      this.$modal.show(`${ModelName}-${this.demand.id}`);
      if (ModelName === "updateModal")
        this.tempDemand = JSON.parse(JSON.stringify(this.demand));
    },
    openUpdatePopup() {
      this.tempDemand = JSON.parse(JSON.stringify(this.demand));
      this.update = true;
    },
    validate() {
      console.log(this.$refs);
      return this.$refs.form.validate();
    },
    hidePopup(ModelName) {
      this.$modal.hide(`${ModelName}-${this.demand.id}`);
    },
    handleRemoveDemand(ev) {
      ev.preventDefault();

      this.showPopup("deleteModal");
    },
    handleUpdateCancellDemand(ev) {
      ev.preventDefault();
      this.hidePopup("updateModal");
    },
    handleRemoveDemandConfirm(ev) {
      this.loading = true;
      this.onDeleteDemandCB(this.demand.id)
        .then(() => {
          this.$store.commit("showMessage", {
            content: "Demanda removida com sucesso!",
            error: false
          });
        })
        .catch(e => {
          this.$store.commit("showMessage", {
            content: "Erro ao excluir demanda.",
            error: true
          });
        })
        .finally(() => {
          this.loading = false;
        });
    },
    handleUpdateDemand(ev) {
      ev.preventDefault();
      //Validar dados
      if (this.validate()) {
        this.demand.title = this.tempDemand.title;
        this.demand.text = this.tempDemand.text;
        this.demand.quantity = this.tempDemand.quantity;
        this.loading = true;
        console.log("Fired");
        this.onUpdateDemandCB(this.demand.id, this.demand)
          .then(ev => {
            this.hidePopup("updateModal");
            this.$store.commit("showMessage", {
              content: "Demanda salva!",
              error: false
            });
          })
          .catch(err => {
            this.$store.commit("showMessage", {
              content: "Erro ao alterar demanda.",
              error: true
            });
          })
          .finally(() => {
            this.loading = false;
            this.update = false;
          });
      }
    }
  }
};
</script>

<style>
</style>