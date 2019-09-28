<template>
  <div>
    example
  </div>
</template>

<script>
  import { hasPermission } from '@/libs/permission'

  export default {
    name: 'exampleIndex',
    components: {
    },
    data: () => ({
    }),
    methods: {
    },
    computed: {
      updatePermission: function() {
        return hasPermission('example.update')
      },
      addPermission: function() {
        return hasPermission('example.store')
      },
      deletePermission: function() {
        return hasPermission('example.destroy')
      }
    },
    created() {
    }
  }
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
</style>
