<template>
  <div>
    <modal :name="`updateModal-${entity.id}`" :adaptive="false" height="600px">
      <div class="container mt-2">
        <form>
          <h3 class="py-2">Alterar informações</h3>
          <div class="row">
            <div class="form-group col-8">
              <label>Nome</label>
              <input
                type="text"
                v-model="tempEntity.name"
                class="form-control"
                placeholder="Nome da entidade"
              />
            </div>
            <div class="form-group col">
              <label>CNPJ</label>
              <input
                type="text"
                v-model="tempEntity.cnpj"
                disabled
                class="form-control"
                placeholder="informe o CNPJ"
              />
            </div>
          </div>
          <div class="form-group">
            <label>Razão Social</label>
            <input
              type="text"
              class="form-control"
              placeholder="Insira a razão social da entidade"
              v-model="tempEntity.legal_name"
            />
          </div>
          <div class="row">
            <div class="form-group col-9">
              <label>Endereço</label>
              <input
                type="text"
                class="form-control"
                placeholder="Ex: Rua Exemplo, 1029"
                v-model="tempEntity.street_address"
              />
            </div>
            <div class="form-group col">
              <label>Estado</label>
              <select class ="form-control" v-model="tempEntity.state">
                <option v-for="state in states">{{state}}</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Cidade</label>
            <input type="text" class="form-control" v-model="tempEntity.city" />
          </div>
          <div class="form-group">
            <label>Descrição</label>
            <textarea
              type="text"
              class="form-control"
              placeholder="(Mínimo de 10 caracteres) Adicione uma descrição, descrevendo por exemplo o que a entidade faz, pelo que é responsável, etc."
              v-model="tempEntity.description"
              style="resize: none"
            />
          </div>
        </form>
        <button @click="handleUpdateEntity" class="btn btn-success float-right mx-2">Salvar</button>
        <button @click="handleUpdateCancelEntity" class="btn btn-danger float-right">Cancelar</button>
      </div>
    </modal>

    <modal :name="`deleteModal-${entity.id}`" :width="300" :height="150">
      <div class="container my-2 text-center">
        <h5>Você tem certeza que deseja sair desta entidade?</h5>
        <div class="d-flex justify-content-between mt-4">
          <button
            @click="hidePopup('deleteModal')"
            class="btn btn-outline-danger float-right"
          >Cancelar</button>
          <button @click="onLeaveEntityCB(entity.id)" class="btn btn-danger float-right mx-2">Sair</button>
        </div>
      </div>
    </modal>

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
        <button
          class="btn btn-success m-1"
          v-if="!isActiveEntityCB(entity.id)"
          @click="onSelectEntityCB(entity.id)"
        >
          <span class="fa fa-check-double"></span> Ativar
        </button>
        <button class="btn btn-primary m-1" @click="onSelectEntityCB(entity.id, true)">
          <span class="fa fa-syringe"></span> Gerenciar Demandas
        </button>
        <button class="btn btn-primary m-1">
          <span class="fa fa-user-plus"></span> Convidar Usuário
        </button>
        <button class="btn btn-warning m-1" @click="showPopup('updateModal')">
          <span class="fa fa-edit"></span> Alterar
        </button>
        <button @click="handleLeaveEntity" class="btn btn-danger m-1">
          <span class="fa fa-door-open"></span> Sair
        </button>
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
    }
  },
  data() {
    return {
      tempEntity: {},
      states:[
        "AC","AL","AM","AP","BA","CE","DF","ES","GO","MA","MG","MS","MT",
        "PA","PB","PE","PI","PR","RJ","RN","RO","RR","RS","SC","SE","SP","TO"
      ],
    };
  },
  methods: {
    showPopup(ModelName) {
      this.$modal.show(`${ModelName}-${this.entity.id}`);
      this.tempEntity = JSON.parse(JSON.stringify(this.entity));
    },
    hidePopup(ModelName) {
      this.$modal.hide(`${ModelName}-${this.entity.id}`);
    },
    handleLeaveEntity(ev) {
      ev.preventDefault();

      this.showPopup("deleteModal");
    },
    handleUpdateCancelEntity(ev) {
      ev.preventDefault();
      this.hidePopup("updateModal");
    },
    handleUpdateEntity(ev) {
      ev.preventDefault();

      //Validar dados

      this.entity.name = this.tempEntity.name;
      this.entity.legal_name = this.tempEntity.legal_name;
      //this.entity.cnpj = this.tempEntity.cnpj;
      this.entity.street_address = this.tempEntity.street_address;
      this.entity.city = this.tempEntity.city;
      this.entity.state = this.tempEntity.state;
      this.entity.description = this.tempEntity.description;

      this.onUpdateEntityCB(this.entity.id, this.entity);
      this.hidePopup("updateModal");
    }
  }
};
</script>

<style>
</style>