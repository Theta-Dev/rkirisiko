<template>
<td :rowspan="rspan" class="text-left">
  <slot />
</td>
</template>

<script>
export default {
  name: 'TableField',

  props: {
    rspan: {
      type: Number,
      default: 1,
    },
  },

  methods: {
    getSearchable() {
      return null;
    },
    getFilterable() {
      return null;
    },
  },
};
</script>
