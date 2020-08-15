<template>
    <div>
        <el-form :inline="true" :model="queryParams" size="mini">
            <el-form-item :label="$t('guardName')">
                <guard-select :now-value.sync="queryParams.guard_name" />
            </el-form-item>
            <el-form-item>
                <el-button type="primary" icon="el-icon-search" @click="requestData">{{ $t('search') }}</el-button>
                <el-button v-if="addPermission" type="primary" icon="el-icon-plus" @click="dialogAddFormVisible = true">{{ $t('add') }}</el-button>
            </el-form-item>
        </el-form>
        <el-table :data="tableListData" v-loading="loading" row-key="id" :tree-props="{children: 'children', hasChildren: 'hasChildren'}" border stripe class="init_table">
            <el-table-column :label="$t('name')" min-width="200" prop="name" show-overflow-tooltip align="left">
            </el-table-column>
            <el-table-column prop="uri" label="Router" />
            <el-table-column prop="permission_name" :label="$t('permission')" />
            <el-table-column prop="sequence" :label="$t('sequence')" />
            <el-table-column align="center" :label="$t('icon')">
                <template slot-scope="scope">
                    <i :class="scope.row.icon" />
                </template>
            </el-table-column>
            <el-table-column align="center" :label="$t('display')">
                <template slot-scope="scope">
                    <el-switch v-model="scope.row.is_display" disabled :active-text="$t('yes')" :active-value="1" :inactive-text="$t('no')" :inactive-value="0" />
                </template>
            </el-table-column>
            <el-table-column align="center" :label="$t('actions')">
                <template slot-scope="scope">
                    <el-button v-if="updatePermission" size="mini" @click="handleEdit(scope.$index, scope.row)">{{ $t('edit') }}</el-button>
                    <el-button v-if="deletePermission" type="danger" size="mini" @click="handleDelete(scope.$index, scope.row)">{{ $t('delete') }}</el-button>
                </template>
            </el-table-column>
        </el-table>
        <el-dialog :title="$t('add')" :visible.sync="dialogAddFormVisible" width="40%">
            <el-form ref="addForm" :model="addForm" :rules="rules">
                <el-form-item :label="$t('name')" prop="name" :label-width="formLabelWidth">
                    <el-input v-model="addForm.name" />
                </el-form-item>
                <el-form-item :label="$t('router')" prop="uri" :label-width="formLabelWidth">
                    <el-input v-model="addForm.uri" />
                </el-form-item>
                <el-form-item :label="$t('guardName')" prop="guard_name" :label-width="formLabelWidth">
                    <guard-select :now-value.sync="addForm.guard_name" />
                </el-form-item>
                <el-form-item :label="$t('parentMenu')" prop="parent_id" :label-width="formLabelWidth">
                    <menu-cascader :menu-id.sync="addForm.parent_id" :guard-name="addForm.guard_name" />
                </el-form-item>
                <el-form-item :label="$t('permission')" prop="permission_name" :label-width="formLabelWidth">
                    <el-input v-model="addForm.permission_name" />
                </el-form-item>
                <el-form-item :label="$t('icon')" prop="icon" :label-width="formLabelWidth">
                    <select-icon v-model="addForm.icon" />
                </el-form-item>
                <el-form-item :label="$t('sequence')" prop="sequence" :label-width="formLabelWidth">
                    <el-input v-model.number="addForm.sequence" />
                </el-form-item>
                <el-form-item :label="$t('display')" prop="is_display" :label-width="formLabelWidth">
                    <el-switch v-model="addForm.is_display" :active-text="$t('yes')" :active-value="1" :inactive-text="$t('no')" :inactive-value="0" />
                </el-form-item>
                <el-form-item :label="$t('description')" prop="description" :label-width="formLabelWidth">
                    <el-input v-model="addForm.description" type="textarea" />
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogAddFormVisible = false">{{ $t('cancel') }}</el-button>
                <el-button type="primary" @click="handleAddMenu">{{ $t('confirm') }}</el-button>
            </div>
        </el-dialog>
        <el-dialog :title="$t('edit')" :visible.sync="dialogEditFormVisible" width="40%">
            <el-form ref="editForm" :model="editForm" :rules="rules">
                <el-form-item :label="$t('name')" prop="name" :label-width="formLabelWidth">
                    <el-input v-model="editForm.name" />
                </el-form-item>
                <el-form-item :label="$t('router')" prop="uri" :label-width="formLabelWidth">
                    <el-input v-model="editForm.uri" />
                </el-form-item>
                <el-form-item :label="$t('guardName')" prop="guard_name" :label-width="formLabelWidth">
                    <guard-select :now-value.sync="editForm.guard_name" />
                </el-form-item>
                <el-form-item :label="$t('parentMenu')" prop="parent_id" :label-width="formLabelWidth">
                    <menu-cascader :menu-id.sync="editForm.parent_id" :guard-name="editForm.guard_name" />
                </el-form-item>
                <el-form-item :label="$t('permission')" prop="permission_name" :label-width="formLabelWidth">
                    <el-input v-model="editForm.permission_name" />
                </el-form-item>
                <el-form-item :label="$t('icon')" prop="icon" :label-width="formLabelWidth">
                    <select-icon v-model="editForm.icon" />
                </el-form-item>
                <el-form-item :label="$t('sequence')" prop="sequence" :label-width="formLabelWidth">
                    <el-input v-model.number="editForm.sequence" />
                </el-form-item>
                <el-form-item :label="$t('display')" prop="is_display" :label-width="formLabelWidth">
                    <el-switch v-model="editForm.is_display" :active-text="$t('yes')" :active-value="1" :inactive-text="$t('no')" :inactive-value="0" />
                </el-form-item>
                <el-form-item :label="$t('description')" prop="description" :label-width="formLabelWidth">
                    <el-input v-model="editForm.description" type="textarea" />
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogEditFormVisible = false">{{ $t('cancel') }}</el-button>
                <el-button type="primary" @click="handleEditMenu">{{ $t('confirm') }}</el-button>
            </div>
        </el-dialog>
    </div>
</template>
<script>
import GuardSelect from '../../../components/Select/Guard'
import { getMenuList, addMenu, editMenu, deleteMenu } from '../../../api/menu'
import { tableDefaultData, editSuccess, addSuccess, deleteSuccess } from '../../../libs/tableDataHandle'
import MenuCascader from '../../../components/Cascader/Menu'
import { hasPermission } from '../../../libs/permission'

export default {
    name: 'AdminUserIndex',
    components: {
        GuardSelect,
        MenuCascader,
        SelectIcon
    },
    data: () => ({
        ...tableDefaultData(),
        tableListData: [],
        foldList: [],
        addForm: {},
        editForm: {},
        rules: {
            name: [
                { required: true },
                { min: 1, max: 255 }
        ],
            uri: [
                { required: true },
                { min: 1, max: 255 }
        ],
            guard_name: [
                { required: true },
                { min: 1, max: 255 }
        ],
            parent_id: [
                { required: true, type: 'number' }
        ],
            sequence: [
                { type: 'number' }
        ]
        }
    }),
    computed: {
        updatePermission: function () {
            return hasPermission('menu.update')
        },
    }
    methods: {
        handleDelete(index, row) {
            deleteMenu(row.id).then(response => {
                deleteSuccess(index, this)
                this.requestData()
            })
        },
        handleEdit(index, row) {
            this.editForm = row
            this.nowRowData = { index, row }
            this.dialogEditFormVisible = true
        },
        handleEditMenu() {
            this.$refs['editForm'].validate((valid) => {
                if (valid) {
                    editMenu(this.nowRowData.row.id, this.editForm).then(response => {
                        editSuccess(this)
                        this.requestData()
                    })
                } else {
                    return false;
                }
            })
        },
        handleAddMenu() {
            this.$refs['addForm'].validate((valid) => {
                if (valid) {
                    addMenu(this.addForm).then(response => {
                        addSuccess(this)
                        this.requestData()
                    })
                } else {
                    return false;
                }
            })
        },
        requestData() {
            this.loading = true
            getMenuList(this.queryParams).then(response => {
                this.tableListData = response.data.data
                this.loading = false
            })
        }
    }
}
</script>
<style rel="stylesheet/scss" lang="scss" scoped>
</style>