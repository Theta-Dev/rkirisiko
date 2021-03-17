<template>
  <v-container>
    <v-row class="text-center">
      <v-col class="mb-4">
        <RiskTable/>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import RiskTable from "@/components/RiskTable";

export default {
  name: 'HelloWorld',

  components: {
    RiskTable,
  },

  data: () => ({}),
}
</script>
