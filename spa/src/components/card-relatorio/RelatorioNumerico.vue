<template>
  <v-card>
    <v-card-title class="justify-center">
      {{ title }}
    </v-card-title>
    <v-card-text class="py-0 px-1">
      <v-container fluid>
        <v-row dense no-gutters>
          <v-col cols="12">
            <span class="display-1 orange--text">
              {{ qtd }}
            </span>
          </v-col>
        </v-row>
      </v-container>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn icon>
        <v-icon>fa-share</v-icon>
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
  export default {
    name: "RelatorioNumerico",
    props: {
      title: String,
      qtd: Number
    },
  }
</script>