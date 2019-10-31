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
      :row-style="toggleDisplayTr"
      border
      stripe
      class="init_table"
    >
      <el-table-column
        :label="$t('name')"
        min-width="160"
        show-overflow-tooltip
        align="left"
      >
        <template slot-scope="scope">
          <p :style="`margin-left: ${scope.row.__level * 20}px;margin-top:0;margin-bottom:0`"><i class="permission_toggleFold" :class="toggleFoldingClass(scope.row)" @click="toggleFoldingStatus(scope.row)" />
            <el-tooltip class="item" :disabled="!scope.row.description" effect="dark" :content="scope.row.description" placement="right-start">
              <span>
                {{ scope.row.name }}
              </span>
            </el-tooltip>
          </p>
        </template>
      </el-table-column>
      <el-table-column
        prop="uri"
        label="Router"
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
    // Author: zyx <https://github.com/no-simple/vue-tree-table>
    toggleFoldingStatus(params) {
      this.foldList.includes(params.__identity) ? this.foldList.splice(this.foldList.indexOf(params.__identity), 1) : this.foldList.push(params.__identity)
    },

    // Author: zyx <https://github.com/no-simple/vue-tree-table>
    toggleDisplayTr({ row, index }) {
      for (let i = 0; i < this.foldList.length; i++) {
        const item = this.foldList[i]
        if (row.__family.includes(item) && row.__identity !== item) return 'display:none;'
      }
      return ''
    },

    // Author: zyx <https://github.com/no-simple/vue-tree-table>
    toggleFoldingClass(params) {
      return params.children.length === 0 ? 'permission_placeholder' : (this.foldList.indexOf(params.__identity) === -1 ? 'iconfont el-icon-minus' : 'iconfont el-icon-plus')
    },

    // Author: zyx <https://github.com/no-simple/vue-tree-table>
    formatConversion(parent, children, index = 0, family = [], elderIdentity = 'x') {
      if (children.length > 0) {
        children.map((x, i) => {
          Vue.set(x, '__level', index)
          Vue.set(x, '__family', [...family, elderIdentity + '_' + i])
          Vue.set(x, '__identity', elderIdentity + '_' + i)
          x.assigned = this.roleMenus.includes(x.id)
          parent.push(x)
          if (!x.hasOwnProperty('children')) {
            x.children = []
          }
          if (x.children.length > 0) this.formatConversion(parent, x.children, index + 1, [...family, elderIdentity + '_' + i], elderIdentity + '_' + i)
        })
      } return parent
    },
    requestData() {
      this.loading = true
      const getMenuLists = getMenuList(this.queryParams)
      // .then(response => {
      //   this.tableListData = this.formatConversion([], response.data)
      //   this.loading = false
      // })
      const roleMenus = roleMenu(this.$route.params.id)
      Promise.all([getMenuLists, roleMenus]).then(result => {
        this.roleMenus = result[1].data
        this.tableListData = this.formatConversion([], result[0].data)
        this.loading = false
      })
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
