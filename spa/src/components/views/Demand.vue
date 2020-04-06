<template>
  <div>
    <modal :name="`updateModal-${demand.id}`" :adaptive="false" height="400px">
      <div class="container mt-2">
        <v-form class="ma-3" ref="form" >
          <div class="form-group">
            <label for="formEditTitle">Titulo</label>
            <!--<input
              v-model="tempDemand.title"
              id="formEditTitle"
              type="text"
              class="form-control"
              placeholder="Entre com o titulo"
            />-->
            <v-text-field
            v-model="tempDemand.title"
            :counter="200"
            :rules="[rules.required]"
            label="Demanda"
          ></v-text-field>
          </div>
          <div class="form-group">
            <label for="demandTitle">Quantidade</label>
            <!--<input
              type="number"
              v-model="tempDemand.quantity"
              class="form-control"
              placeholder="Quantidade necessaria para suprir a demanda"
            />-->
            <v-text-field
            type="number"
            v-model="tempDemand.quantity"
            :rules="[rules.numberRule,rules.required]"
            label="Quantidade"
          ></v-text-field>
          </div>
          <div class="form-group">
            <label for="formEditDescription">Descrição</label>
            <!--<textarea
              v-model="tempDemand.text"
              type="text"
              class="form-control"
              id="formEditDescription"
              rows="4"
              style="resize: none"
            ></textarea>-->
            <v-textarea
            rows="3"
            auto-grow
            :counter="500"
            label="Adicione um descrição"
            v-model="tempDemand.text"
            :rules="[rules.required]"
          ></v-textarea>
          </div>
          <button @click="handleUpdateDemand" class="btn btn-success float-right mx-2">Salvar</button>
          <button @click="handleUpdateCancellDemand" class="btn btn-danger float-right">Cancelar</button>
        </v-form>
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
          <button
            @click="onDeleteDemandCB(demand.id)"
            class="btn btn-danger float-right mx-2"
          >Remover</button>
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
      rules: {
      min: v => v.length >= 1 || "Min 15 caracteres",
      required: value => !!value || "Obrigatório.",
      numberRule: v => {
        if (parseInt(v) && v >= 1) return true;
        return "O campo deve conter apenas múmero. Favor verificar!";
      }
    }
    };
    
  },
  methods: {
    showPopup(ModelName) {
      this.$modal.show(`${ModelName}-${this.demand.id}`);
      this.tempDemand = JSON.parse(JSON.stringify(this.demand));
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
    handleUpdateCancellDemand(ev){
      ev.preventDefault();
      this.hidePopup("updateModal");
    },
    handleUpdateDemand(ev) {
      ev.preventDefault();
      
      //Validar dados

      this.demand.title = this.tempDemand.title;
      this.demand.text = this.tempDemand.text;
      this.demand.quantity = this.tempDemand.quantity;

      this.onUpdateDemandCB(this.demand.id, this.demand);
      this.hidePopup("updateModal");
    }
  }
};
</script>

<style>
</style>