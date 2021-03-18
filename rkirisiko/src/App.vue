<template>
  <v-app>
    <v-app-bar app color="primary" dark>

      <v-icon large left>{{ icons.virus }}</v-icon>

      <h3>SARS-CoV-2 Risikogebiete</h3>

      <v-spacer/>

      <div>
        <v-tooltip v-if="!$vuetify.theme.dark" bottom>
          <template v-slot:activator="{ on }">
            <v-btn v-on="on" icon @click="toggleDarkMode">
              <v-icon>{{ icons.dark_mode }}</v-icon>
            </v-btn>
          </template>
          <span>Dark Mode On</span>
        </v-tooltip>

        <v-tooltip v-else bottom>
          <template v-slot:activator="{ on }">
            <v-btn v-on="on" icon @click="toggleDarkMode">
              <v-icon>{{ icons.light_mode }}</v-icon>
            </v-btn>
          </template>
          <span>Dark Mode Off</span>
        </v-tooltip>
      </div>
    </v-app-bar>

    <v-main>
      <HelloWorld/>
    </v-main>

    <v-footer color="primary darken-1" padless>
      <v-row justify="center" no-gutters>
        <v-btn text rounded color="white" class="my-2" href="https://thetadev.de" target="_blank">
          ThetaDev
        </v-btn>
        <v-btn text rounded color="white" class="my-2" href="https://github.com/Theta-Dev/rkirisiko" target="_blank">
          GitHub
        </v-btn>
        <v-btn text rounded color="white" class="my-2" href="https://www.rki.de/DE/Content/InfAZ/N/Neuartiges_Coronavirus/Risikogebiete_neu.html" target="_blank">
          Quelle: RKI
        </v-btn>
      </v-row>
    </v-footer>
  </v-app>
</template>

<script>
import HelloWorld from './components/HelloWorld';

// Icons
import {mdiVirus, mdiOpenInNew, mdiLightbulb, mdiWhiteBalanceSunny} from '@mdi/js'

export default {
  name: 'App',

  components: {
    HelloWorld,
  },

  data: () => ({
    icons: {
      virus: mdiVirus,
      open_in_new: mdiOpenInNew,
      dark_mode: mdiLightbulb,
      light_mode: mdiWhiteBalanceSunny,
    },
  }),

  methods: {
    toggleDarkMode: function () {
      this.$vuetify.theme.dark = !this.$vuetify.theme.dark;
    }
  },

  mounted() {
    // Does user prefer dark mode?
    this.$vuetify.theme.dark = window.matchMedia &&
        window.matchMedia('(prefers-color-scheme: dark)').matches;
  },
};
</script>
