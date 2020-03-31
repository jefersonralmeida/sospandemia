<template>
  <div class="card">
      <div class="card-body">
        <h5 class="card-title">{{ demand.title }}</h5>
        <p class="card-text">{{ demand.text }}</p>
      </div>
        <div class="d-flex card-footer text-muted small" >
            <div class="flex-grow-1 align-self-center">Quantidade: {{ demand.quantity }}</div>
            <button class="btn btn-primary btn-sm" @click="onViewDemandCB(demand.id)">Info (console)</button>
            <button class="btn btn-warning btn-sm ml-1" @click="onUpdateDemandCB(demand.id)">Alterar (hard coded)</button>
            <button class="btn btn-danger btn-sm ml-1" @click="onDeleteDemandCB(demand.id)">Excluir</button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        demand:{
            type:Object,
            required: true
        },
        onViewDemandCB:{
            type:Function,
            required: true
        },
        onUpdateDemandCB:{
            type:Function,
            required: true
        },
        onDeleteDemandCB:{
            type:Function,
            required: true
        }
    }
}
</script>

<style>

</style>