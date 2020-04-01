<template>
  <div>
    <modal :name="`hello-world-${demand.id}`">
      <div class="container my-2">
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
          <button @click="handleUpdateDemand" class="btn btn-success float-right mx-2">Salvar</button>
          <button @click="handleCancelUpdateDemand" class="btn btn-danger float-right">Cancelar</button>
        </form>
      </div>
    </modal>
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">{{ demand.title }}</h5>
        <p class="card-text">{{ demand.text }}</p>
      </div>
      <div class="d-flex card-footer text-muted small">
        <div class="flex-grow-1 align-self-center">Quantidade: {{ demand.quantity }}</div>
        <button class="btn btn-warning btn-sm ml-1" @click="showPopup">Alterar</button>
        <button class="btn btn-danger btn-sm ml-1" @click="onDeleteDemandCB(demand.id)">Excluir</button>
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
      tempDemand: {}
    };
  },
  methods: {
    showPopup() {
      this.$modal.show(`hello-world-${this.demand.id}`);
      this.tempDemand = JSON.parse(JSON.stringify(this.demand));
    },
    hidePopup() {
      this.$modal.hide(`hello-world-${this.demand.id}`);
    },
    handleUpdateDemand(ev) {
      ev.preventDefault();

      //Validar dados

      this.demand.title = this.tempDemand.title;
      this.demand.text = this.tempDemand.text;

      this.onUpdateDemandCB(this.demand.id, this.demand);
      this.hidePopup();
    },
    handleCancelUpdateDemand(ev) {
      ev.preventDefault();
      this.hidePopup();
    }
  }
};
</script>

<style>
</style>