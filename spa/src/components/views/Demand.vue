<template>
  <div>
    <modal :name="`updateModal-${demand.id}`" :adaptive="false" height="400px">
      <div class="container mt-2">
        <form class="ma-3">
          <div class="form-group">
            <label for="formEditTitle">Titulo</label>
            <input
              v-model="tempDemand.title"
              id="formEditTitle"
              type="text"
              class="form-control"
              placeholder="Entre com o titulo"
            />
          </div>
          <div class="form-group">
            <label for="demandTitle">Quantidade</label>
            <input
              type="number"
              v-model="tempDemand.quantity"
              class="form-control"
              placeholder="Quantidade necessaria para suprir a demanda"
            />
          </div>
          <div class="form-group">
            <label for="formEditDescription">Descrição</label>
            <textarea
              v-model="tempDemand.text"
              type="text"
              class="form-control"
              id="formEditDescription"
              rows="4"
              style="resize: none"
            ></textarea>
          </div>
          <v-btn
            @click="handleUpdateDemand"
            color="success"
            class="float-right mx-2"
            :loading="loading"
          >Salvar</v-btn>
          <button @click="handleUpdateCancellDemand" class="btn btn-danger float-right">Cancelar</button>
        </form>
      </div>
    </modal>

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
            class="float-right mx-2  white--text"
            :loading="loading"
          >Remover</v-btn>
        </div>
      </div>
    </modal>

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">{{ demand.title }}</h5>
        <p class="card-text">{{ demand.text }}</p>
      </div>
      <div class="d-flex card-footer text-muted small">
        <div class="flex-grow-1 align-self-center">Quantidade: {{ demand.quantity }}</div>
        <button class="btn btn-warning btn-sm ml-1" @click="showPopup('updateModal')">Alterar</button>
        <button class="btn btn-danger btn-sm ml-1" @click="handleRemoveDemand">Excluir</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
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
      loading: false
    };
  },
  methods: {
    showPopup(ModelName) {
      this.$modal.show(`${ModelName}-${this.demand.id}`);
      if (ModelName === "updateModal")
        this.tempDemand = JSON.parse(JSON.stringify(this.demand));
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
      this.onDeleteDemandCB(this.demand.id).then(() => {
        this.loading = false;
      });
    },
    handleUpdateDemand(ev) {
      ev.preventDefault();

      //Validar dados
      this.demand.title = this.tempDemand.title;
      this.demand.text = this.tempDemand.text;
      this.demand.quantity = this.tempDemand.quantity;

      this.loading = true;
      console.log("Fired");
      this.onUpdateDemandCB(this.demand.id, this.demand)
        .then(ev => {
          this.hidePopup("updateModal");
        })
        .catch(err => {})
        .finally(() => {
          this.loading = false;
        });
    }
  }
};
</script>

<style>
</style>