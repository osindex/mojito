export default [
  {
    name: 'exampleIndex',
    path: 'example',
    meta: {
      provider: 'admin',
      title: 'example',
      cache: true,
      permission: 'example.index'
    },
    component: resolve => void(require(['./index.vue'], resolve))
  }
]
