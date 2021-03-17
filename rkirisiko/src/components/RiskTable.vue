<template>
  <v-simple-table>
    <template v-slot:default>
      <thead class="text-left">
      <tr>
        <th>Land</th>
        <th>Risiko</th>
        <th>Region</th>
        <th>Seit wann</th>
      </tr>
      </thead>
      <tbody class="text-left">
      <template v-for="(cdata, country) in riskdata">
        <template v-for="(item, index) in cdata">
          <tr :key="country+index">
            <td v-if="index===0" :rowspan="index===0 && cdata.length > 1 ? cdata.length*2 : 2">{{ country }}</td>
            <td rowspan="2">
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-icon v-bind="attrs" v-on="on">{{ icons.high_incidence }}</v-icon>
                </template>
                <span>Hohe Inzidenz</span>
              </v-tooltip>
            </td>
            <td>{{ item.detail }}</td>
            <td>{{ item.date }}</td>
          </tr>
          <tr :key="country+index+'_d'">
            <td v-html="item.html" colspan="2"/>
          </tr>
        </template>
      </template>
      </tbody>
    </template>
  </v-simple-table>
</template>

<script>
// Icons
import {mdiVirus, mdiElevationRise, mdiAlert} from '@mdi/js'

import testdata from '../test.json'

export default {
  name: "RiskTable",

  data: () => ({
    icons: {
      mutation: mdiVirus,
      high_incidence: mdiElevationRise,
      risk: mdiAlert
    },
    riskdata: testdata,
  }),
}
</script>

<style scoped>

</style>