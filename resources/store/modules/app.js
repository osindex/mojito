import { getAdminMe } from '../../api/adminUser'

const state = {
  local: '',
  admin: {},
  breadcrumb: []
}

const getters = {
  breadcrumb: state => state.breadcrumb,
  admin: state => state.admin
}

const mutations = {
  SET_BREADCRUMB(state, breadcrumb) {
    const title = []
    state.breadcrumb = breadcrumb.filter(item => {
      if (title.indexOf(item.meta.title) >= 0) {
        return false
      }
      title.push(item.meta.title)
      return item.meta.title
    })
  },
  SET_ADMIN(state, admin) {
    state.admin = admin
  }
}

const actions = {
  getMe({ dispatch, commit }) {
    getAdminMe().then(response => {
      commit('SET_ADMIN', response.data)
      Promise.resolve(response.data)
    }).catch(e => {
      console.log(e.response)
    })
  }
}

export default {
  state,
  getters,
  mutations,
  actions
}
