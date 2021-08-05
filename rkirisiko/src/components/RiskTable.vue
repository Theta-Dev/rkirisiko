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
      <DataTable
          :fields="table_fields"
          :data="tableData"
      />
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import testdata from '@/test.json'

// Icons
import DataTable from '@/components/datatable/DataTable.vue';
import TableFieldText from '@/components/datatable/TableFieldText.vue';
import TableFieldDetails from '@/components/datatable/TableFieldDetails.vue';
import TableFieldRiskIcon from '@/components/datatable/TableFieldRiskIcon.vue';

export default {
  name: "RiskTable",
  components: {DataTable},
  data: () => ({
    table_fields: {
      Land: TableFieldText,
      Risiko: TableFieldRiskIcon,
      Datum: TableFieldText,
      Details: TableFieldDetails,
    },
    risk_data: {},
    data_ready: false,
    error_msg: false,
    reload_timeout: null
  }),

  computed: {
    sortedData() {
      return Object.keys(this.risk_data)
          .sort(((a, b) => a.localeCompare(b, 'de-DE')))
          .reduce((obj, key) => {
            obj[key] = this.risk_data[key];
            return obj;
          }, {});
    },

    tableData() {
      return Object.entries(this.sortedData).map(([country, items]) => [
          [country],
          items.map((itm) => itm.type),
          items.map((itm) => itm.date),
          items.map((itm) => [itm.detail, itm.html]),
      ]);
    }
  },

  methods: {
    fetch_data: function () {
      axios.get('https://thetadev.de/rki/api.php')
          .then(response => {
            console.log(response);
            if (response.status === 200 && response.data instanceof Object) {
              this.risk_data = response.data;
              this.data_ready = true;
              if (this.reload_timeout) clearTimeout(this.reload_timeout);
            } else this.fetch_data_error();
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
    this.risk_data = testdata
    this.data_ready = true;
    // this.fetch_data();
  }
}
</script>

<style scoped>

</style>