<template>
  <v-app>
    <div id="app" class="container-fluid h-100 w-100 p-0">
      <v-snackbar :value="snackbar" top :color="snackbarError ? 'red' : 'success'" :timeout="timeout">
        {{snackbarText}}
        <v-btn text @click="snackbar = false">Close</v-btn>
      </v-snackbar>
      <component :is="layout"></component>
    </div>
  </v-app>
</template>

<script>
import MainLayout from "./components/layouts/MainLayout";
import CompactLayout from "./components/layouts/CompactLayout";
import NoLayout from "./components/layouts/NoLayout";

const default_layout = "main-layout";
export default {
  name: "App",
  components: {
    MainLayout,
    CompactLayout,
    NoLayout
  },
  data: () => ({
    snackbar: false, 
    snackbarText: "",
    snackbarError: false,
    timeout: 2000
  }),
  computed: {
    layout() {
      return this.$route.meta.layout || default_layout;
    }
  },
  mounted() {
    if (!this.$store.profile && this.$store.getters.isLogged) {
      this.$store.dispatch("loadProfile");
    }
  },
  created() {
    this.$store.subscribe((mutation, state) => {
      if (mutation.type === "showMessage") {
        this.snackbarText = state.notificationContent;
        this.snackbarError = state.noticicationError;
        this.snackbar = true;
        setTimeout(()=>{this.snackbar = false}, this.timeout)
      }
    });
  }
};
</script>

<style>
html,
body {
  height: 100%;
}

#app {
  font-family: "Avenir", Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}
</style>
