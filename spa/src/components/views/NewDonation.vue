<template>
  <v-stepper v-model="step">
    <v-stepper-header>

      <v-stepper-step step="1">Doador</v-stepper-step> 

      <v-divider></v-divider>

      <v-stepper-step step="2">Doação</v-stepper-step>

      <v-divider></v-divider>

      <v-stepper-step step="3">Confirmar Dados</v-stepper-step>
    </v-stepper-header>

    <v-stepper-items>
      
      <v-stepper-content step="1">
        <v-card class="mb-12">
          <v-form ref="form" v-model="valid">
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
        <v-btn color="primary" @click="createDonator" :disabled="!valid && !donator.isanonymous">Continue</v-btn>
        <v-btn text @click="resetForms()">Cancel</v-btn>
      </v-stepper-content>

      <v-stepper-content step="2">
        <v-form ref="form">
          <v-text-field
            v-model="donation.quantity"
            label="Documento"
            placeholder="informe a quantidade"
            type="number"
            :rules="[rules.required, (value) => value<=demand.quantity && value>0]"
            outlined
          ></v-text-field>
          <v-btn color="primary" @click="step = 3">Continue</v-btn>
          <v-btn text @click="resetForms()">Cancel</v-btn>
        </v-form>
      </v-stepper-content>

      <v-stepper-content step="3">
        <h1>Dados do doador</h1>
        <div v-if="!donator.isanonymous">
          <p><strong>Nome: </strong> {{donator.name}}</p> 
          <p><strong>Nº documento: </strong> {{donator.document}} </p>
          <p><strong>Contato: </strong> {{donator.contact}}</p>
        </div>
        <h1 v-else>Doador Anônimo</h1>
        <v-divider></v-divider>
        <h1>Doação</h1>
        <p><strong>quantidade: </strong> {{donation.quantity}} </p>
        <v-btn color="primary" @click="step = 4">Continue</v-btn>
        <v-btn text @click="resetForms()">Cancel</v-btn>
      </v-stepper-content>

    </v-stepper-items>
  </v-stepper>
</template>

<script>
import rules from "../../util/rules";
import api from "../../api_local" 
export default {
  props:{
    demand: {
      type: Object,
      required: true
    },
    close:{
      type: Function,
      required: true
    }
  },
  data() {
    return {
      step: 1,
      valid:true,
      donator: {
        document: "",
        name: "",
        contact: "",
        isanonymous: false
      },
      donation: {
        quantity: 0,
        demand: 0,
        donator: 0
      }
    };
  },
  methods: {
    createDonator: function(){
      //if (!this.isValidForm()) return;
      api.createDonator(this.donator)
      .then(()=>{
          this.step = 2;
        }
      )
    },
    isValidForm() {
      return this.$refs.form.validate();
    },
    resetForms: function(){
      this.close();
      this.donator = {
        document:"",
        name:"",
        contact:"",
        isanonymous:false,
      };
      this.donation = {

      },
      this.step = 1;
      return this.$refs.form.reset();
    }
  },
  mixins: [rules],
  watch:{
      "donator.document"(){
          if(this.donator.document.length == 11 || this.donator.document.length == 13){

            console.log("oi");
              api.getDonatorByDocument(this.donator.document).then(res=>{
                  console.log(res)
                  if(res.data != undefined){
                    this.donator.name = res.data.name
                    this.donator.contact = res.data.contact
                  }
              })
          }
      }
  }
};
</script>

<style>
</style>