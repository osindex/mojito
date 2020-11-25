<template>
  <div>
    <el-form :inline="true" :model="queryParams" size="mini">
      <el-form-item>
        <el-button type="primary" icon="el-icon-refresh" @click="requestData">{{ $t('refresh') }}</el-button>
      </el-form-item>
    </el-form>

    <el-table
      v-loading="loading"
      :data="tableListData"
      row-key="id"
      :tree-props="{children: 'children', hasChildren: 'hasChildren'}"
      border
      stripe
      default-expand-all
      class="init_table"
    >
      <el-table-column
        :label="$t('name')"
        min-width="160"
        show-overflow-tooltip
        align="left"
        prop="name"
      >
      </el-table-column>
      <el-table-column
        prop="uri"
        :label="$t('router')"
      />
      <el-table-column
        prop="permission_name"
        :label="$t('permission')"
      />
      <el-table-column
        align="center"
        :label="$t('icon')"
      >
        <template slot-scope="scope">
          <i :class="scope.row.icon" />
        </template>
      </el-table-column>

      <el-table-column
        align="center"
        :label="$t('globalDisplay')"
      >
        <template slot-scope="scope">
          <el-switch
            v-model="scope.row.is_display"
            disabled
            :active-text="$t('yes')"
            :active-value="1"
            :inactive-text="$t('no')"
            :inactive-value="0"
          />
        </template>
      </el-table-column>

      <el-table-column
        align="center"
        :label="$t('assignMenu')"
      >
        <template slot-scope="scope">
          <el-switch
            v-model="scope.row.assigned"
            :active-text="$t('yes')"
            :inactive-text="$t('no')"
            @change="(e)=>toggleMenusFunc(scope.row,e)"
          />
        </template>
      </el-table-column>
    </el-table>

  </div>
</template>
<script>
import Vue from 'vue'
import SelectIcon from '../../../components/Select/SelectIcon'
import { roleMenu, roleToggleMenu } from '../../../api/role'
import { getMenuList, addMenu, editMenu, deleteMenu } from '../../../api/menu'
import { tableDefaultData, editSuccess, addSuccess, deleteSuccess } from '../../../libs/tableDataHandle'
import MenuCascader from '../../../components/Cascader/Menu'
import { hasPermission } from '../../../libs/permission'
import notify from "../../../libs/notify"

export default {
  name: 'RoleMenu',
  components: {
    MenuCascader,
    SelectIcon
  },
  data() {
    return {
      ...tableDefaultData(),
      tableListData: [],
      foldList: [],
      roleMenus: []
    }
  },
  computed: {
    updateRoleMenu: function() {
      return hasPermission('role.assign-menus')
    }
  },
  created() {
    this.requestData()
  },
  methods: {
    requestData() {
      this.loading = true
      const getMenuLists = getMenuList(this.queryParams)
      const roleMenus = roleMenu(this.$route.params.id)
      Promise.all([getMenuLists, roleMenus]).then(result => {
        this.roleMenus = result[1].data.data
        this.tableListData = this.setAssigned(result[0].data.data)
        this.loading = false
      })
    },
    setAssigned(arr) {
      arr.map(x=>{
        x.assigned = this.roleMenus.includes(x.id)
        if (!x.hasOwnProperty('children')) {
          x.children = []
        }
        if (x.children.length > 0){
          x.children = this.setAssigned(x.children)
        }
        return x
      })
      return arr
    },
    toggleMenusFunc(row, bool) {
      roleToggleMenu(this.$route.params.id, [row.id]).then(response=>{
        if(response.status === 204){
          row.assigned = bool
          notify.doneSuccess(this)   
        }
      })

    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
  .app_title {
    display:block;
    width:100%;
    font-size:24px;
    line-height:60px;
    color:#41dae4;
    text-align:center;
  }
  .permission_toggleFold {
    vertical-align:middle;
    padding-right:5px;
    font-size:16px;
    cursor:pointer;
  }
  .permission_placeholder {
    content:' ';
    display:inline-block;
    width:16px;
    font-size:16px;
  }

  .init_table {
    th {
      background-color: #edf6ff;
      text-align: center !important;
      color: #066cd4;
      font-weight:bold;
      .cell {
        padding:0 !important;
      }
    }
    td, th {
      font-size:12px;
      padding:0 !important;
      height:40px !important;
    }
    .el-table--border, .el-table--group {
      border: 1px solid #dde2ef;
    }

    td, th.is-leaf {
      border-bottom: 1px solid #dde2ef
    }

    .el-table--border td, .el-table--border th, .el-table__body-wrapper .el-table--border.is-scrolling-left~.el-table__fixed {
      border-right: 1px solid #dde2ef
    }

    .el-table--striped .el-table__body tr.el-table__row--striped td {
      background-color:#f7f9fa;
    }
  }

</style>
