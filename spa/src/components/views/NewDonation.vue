<template>
  <v-stepper v-model="step" vertical>
    <v-stepper-step :complete="step > 1" step="1">
      Select an app
      <small>Summarize if needed</small>
    </v-stepper-step>

    <v-stepper-content step="1">
      <v-card class="mb-12">
        <v-form>
          <div class="column">
            <div class="form-group col-12">
              <v-text-field
                v-model="donator.document"
                :disabled="donator.isanonymous"
                label="Documento"
                placeholder="CPF ou CNPJ"
                type="number"
                :rules="[rules.required, (value) => rules.min(value, 11), (value) => rules.max(value, 13)]"
                outlined
              ></v-text-field>
            </div>
            <div class="form-group col-12">
              <v-text-field
                v-model="donator.name"
                :disabled="donator.isanonymous"
                label="Nome"
                placeholder="Seu nome ou razão social"
                type="text"
                :rules="[rules.required, (value) => rules.min(value, 3), (value) => rules.max(value, 255)]"
                outlined
              ></v-text-field>
            </div>
            <div class="form-group col-12">
              <v-text-field
                v-model="donator.contact"
                :disabled="donator.isanonymous"
                label="Contato"
                placeholder="Email ou telefone"
                type="text"
                :rules="[rules.required, (value) => rules.min(value, 8), (value) => rules.max(value, 200)]"
                outlined
              ></v-text-field>
            </div>
          </div>
        </v-form>
        <div class="form-group col-12">
          <v-switch
            v-model="donator.isanonymous"
            label="Doação anonima"
            hint="Caso ativo, seus dados não serão divulgados."
            persistent-hint
          ></v-switch>
        </div>
      </v-card>
      <v-btn color="primary" @click="step = 2">Continue</v-btn>
      <v-btn text>Cancel</v-btn>
    </v-stepper-content>

    <v-stepper-step :complete="step > 2" step="2">Configure analytics for this app</v-stepper-step>

    <v-stepper-content step="2">
      <v-card color="grey lighten-1" class="mb-12" height="200px"></v-card>
      <v-btn color="primary" @click="step = 3">Continue</v-btn>
      <v-btn text>Cancel</v-btn>
    </v-stepper-content>

    <v-stepper-step :complete="step > 3" step="3">Select an ad format and name ad unit</v-stepper-step>

    <v-stepper-content step="3">
      <v-card color="grey lighten-1" class="mb-12" height="200px"></v-card>
      <v-btn color="primary" @click="step = 4">Continue</v-btn>
      <v-btn text>Cancel</v-btn>
    </v-stepper-content>
  </v-stepper>
</template>

<script>
import rules from "../../util/rules";
import api from "../../api_local"
export default {
  data() {
    return {
      step: 1,
      donator: {
        document: "",
        name: "",
        contact: "",
        isanonymous: false
      }
    };
  },
  methods: {},
  mixins: [rules],
  watch:{
      "donator.document"(){
          if(this.donator.document.length == 11 || this.donator.document.length == 13){
              api.getDonatorByDocument(this.donator.document).then(res=>{
                  console.log(res)
                  this.donator.name = res.data.name
                  this.donator.contact = res.data.contact
              })

          }
      }
  }
};
</script>

<style>
</style>