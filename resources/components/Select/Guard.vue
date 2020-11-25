<template>
  <el-select v-model="optionValue" :disabled="disabled" clearable  :placeholder="$t('guardName')">
    <el-option v-for="item in items" :label="item.label" :value="item.value" :key="item.label"></el-option>
  </el-select>
</template>
<script>
  import config from '../../config'
  export default {
    name: 'GuardSelect',
    props: ['nowValue','disabled'],
    data () {
      return {
        optionValue: this.nowValue,
        items: config.guardNames
      }
    },
    created() {
      //this.items = config.admin.guard_names
    },
    watch: {
      optionValue(newValue, oldValue) {
        this.$emit("update:nowValue", newValue);
      },
      nowValue (newValue) {
        this.optionValue = newValue
      }
    }
  }
</script>