export default {
  admin: {
    loginRouteName: 'adminLogin',

    dashboardName: 'adminDashboard',

    dashboardFullPath: '/admin/dashboard',

    appName: {
      fullName: process.env.MIX_APP_NAME || 'admin dashboard',
      abbrName: process.env.MIX_APP_ABBR_NAME || 'admin'
    },

    locale: 'en'
  },
  guardNames: [
    {
      label: 'admin',
      value: 'admin',
      default: true
    }
  ],

  showAuthorGitHubUrl: true,
}