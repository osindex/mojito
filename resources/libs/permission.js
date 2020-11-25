import store from '../store'
import config from '../config'

export const hasPermission = (name) => {
  return store.getters.permissions.indexOf(name) >= 0
}

export const hasRole = (name) => {
  return store.getters.roles.indexOf(name) >= 0
}

export const showGuard = () => {
  return config.guardNames.length > 1
}

export const defaultGuard = () => {
  const guard = config.guardNames.find(e=>e.default)
  return guard.value || 'admin'
}