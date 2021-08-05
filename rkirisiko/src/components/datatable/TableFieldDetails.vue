<template>
  <TableField
      :rspan="rspan"
  >
    <b>{{ val[0] }}</b>
    <p v-html="val[1]"></p>
  </TableField>
</template>

<script>
import TableField from '@/components/datatable/TableField.vue';

export default {
  name: 'TableFieldDetails',
  components: {TableField},

  props: {
    rspan: Number,
    val: Array,
  },
};
</script>
