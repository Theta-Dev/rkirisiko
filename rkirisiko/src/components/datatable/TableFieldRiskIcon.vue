<template>
  <TableField
    :rspan="rspan"
  >
    <v-tooltip bottom>
      <template v-slot:activator="{ on, attrs }">
        <v-icon
          v-bind="attrs"
          v-on="on"
          :color="icon[1]"
        >
          {{ icon[0] }}
        </v-icon>
      </template>
      <span>
        {{ icon[2] }}
      </span>
    </v-tooltip>
  </TableField>
</template>

<script>
import TableField from '@/components/datatable/TableField.vue';
import {mdiAlert, mdiElevationRise, mdiHistory, mdiVirus} from "@mdi/js";

const ICONS = {
  legacy: [mdiHistory, '', 'Ehemals Risikogebiet', 0],
  risk: [mdiAlert, 'orange', 'Risikogebiet', 1],
  high_incidence: [mdiElevationRise, 'red darken-1', 'Hohe Inzidenz', 2],
  mutation: [mdiVirus, 'purple darken-1', 'Virusvarianten-Gebiet', 3],
};

export default {
  name: 'TableFieldIcon',
  components: { TableField },

  props: {
    rspan: Number,
    val: String,
  },

  computed: {
    icon() {
      return ICONS[this.val];
    },
  },

  methods: {
    getFilterable(val) {
      return ICONS[val][2];
    },
    sortFunction(a, b) {
      const va = ICONS[a][3];
      const vb = ICONS[b][3];
      if(va === vb) return 0;
      return va > vb ? 1 : -1;
    }
  },
};
</script>
