<template>
  <div>
    <div v-if="!data_ready">
      Daten werden vom RKI abgerufen
      <v-progress-linear indeterminate/>
    </div>

    <v-snackbar v-model="error_msg">
      Fehler beim Abrufen der Daten. Versuche es in 10s erneut.
    </v-snackbar>

    <div v-if="data_ready">
      <v-text-field v-model="risk_filter" solo label="Suche..." autofocus clearable/>

      <v-simple-table>
        <template v-slot:default>
          <thead class="text-left">
          <tr>
            <th>Land</th>
            <th>Risiko</th>
            <th>Seit wann</th>
            <th>Details</th>
          </tr>
          </thead>
          <tbody class="text-left">
          <template v-for="(cdata, country) in filtered_data">
            <template v-for="(item, index) in cdata">
              <tr :key="country+index">
                <td v-if="index===0" :rowspan="index===0 && cdata.length > 1 ? cdata.length : 1">{{ country }}</td>
                <td>
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                      <v-icon v-bind="attrs" v-on="on" :color="risk_colors[item.type]">
                        {{ risk_icons[item.type] }}
                      </v-icon>
                    </template>
                    <span>{{ risk_types[item.type] }}</span>
                  </v-tooltip>
                </td>
                <td>{{ item.date }}</td>
                <td>
                  <b>{{ item.detail }}</b>
                  <p v-html="item.html"></p>
                </td>
              </tr>
            </template>
          </template>
          </tbody>
        </template>
      </v-simple-table>
    </div>
  </div>
</template>

<script>
// Icons
import {mdiVirus, mdiElevationRise, mdiAlert, mdiHistory} from '@mdi/js'
import axios from "axios";

//import testdata from '../test.json'

export default {
  name: "RiskTable",

  data: () => ({
    risk_icons: {
      mutation: mdiVirus,
      high_incidence: mdiElevationRise,
      risk: mdiAlert,
      legacy: mdiHistory,
    },
    risk_colors: {
      mutation: 'purple darken-1',
      high_incidence: 'red darken-1',
      risk: 'orange',
      legacy: '',
    },
    risk_types: {
      mutation: 'Virusvarianten-Gebiet',
      high_incidence: 'Hohe Inzidenz',
      risk: 'Risikogebiet',
      legacy: 'Ehemals Risikogebiet',
    },
    risk_data: {},
    risk_filter: '',
    data_ready: false,
    error_msg: false,
    reload_timeout: null
  }),

  computed: {
    filtered_data: function () {
      let searchTerm = this.risk_filter.toLowerCase();
      if(searchTerm === null) searchTerm = '';

      return Object.keys(this.risk_data).filter(country => {
        const key = country.toLowerCase();

        return key.includes(searchTerm);
      }).sort(((a, b) => a.localeCompare(b, 'de-DE')))
          .reduce((obj, key) => {
        obj[key] = this.risk_data[key];
        return obj;
      }, {});
    }
  },

  methods: {
    fetch_data: function () {
      axios.get('http://localhost:8001/api.php')
          .then(response => {
            console.log(response);
            if (response.status === 200 && response.data instanceof Object) {
              this.risk_data = response.data;
              this.data_ready = true;
              if(this.reload_timeout) clearTimeout(this.reload_timeout);
            }
            else this.fetch_data_error();
          })
          .catch(reason => {
            console.log(reason);
            this.fetch_data_error();
          });
    },

    fetch_data_error: function () {
      console.log('Fehler beim Abrufen der Daten. Versuche es in 10s erneut.')
      this.error_msg = true;
      this.reload_timeout = setTimeout(this.fetch_data, 10000);
    }
  },

  mounted() {
    this.fetch_data();
  }
}
</script>

<style scoped>

</style>