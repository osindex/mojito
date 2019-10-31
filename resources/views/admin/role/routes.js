import ParentView from '../../../components/ParentView/ParentView'

export default [
  {
    path: 'role',
    meta: {
      provider: 'admin',
      title: 'role',
      cache: true,
    },
    component: ParentView,
    children: [
      {
        name: 'rolePermission',
        path: 'assign-permissions/:id/:guardName',
        meta: {
          provider: 'admin',
          title: 'roleAssignPermission',
          permission: 'role.assign-permissions'
        },
        component: resolve => void(require(['./permission.vue'], resolve)),
      },
      {
        name: 'roleMenu',
        path: 'assign-menus/:id/:guardName',
        meta: {
          provider: 'admin',
          title: 'roleAssignMenu',
          permission: 'role.assign-menus'
        },
        component: resolve => void(require(['./menu.vue'], resolve)),
      },
      {
        name: 'roleIndex',
        path: '',
        meta: {
          provider: 'admin',
          title: 'role',
          cache: true,
          permission: 'role.index'
        },
        component: resolve => void(require(['./index.vue'], resolve)),
      },
    ]
  },
]
