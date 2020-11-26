export default
{
  name: 'proxy',
  path: '/proxy/:path',
  props: { dialog: true },
  meta: {
    dialog: true,
    title: '弹窗详情',
    width: 'w800',
    provider: 'admin',
    permission: 'system.dialog', // 单独弹窗权限
    cache: true
  }
}
