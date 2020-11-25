class Notify {
  success(message, th) {
    th.$notify({
      title: th.$t('success'),
      message: message,
      type: 'success',
      duration: 2000
    })
  }

  createSuccess(th) {
    this.success(th.$t('createSuccess'), th)
  }

  editSuccess(th) {
    this.success(th.$t('editSuccess'), th)
  }

  deleteSuccess(th) {
    this.success(th.$t('deleteSuccess'), th)
  }

  doneSuccess(th) {
    this.success(th.$t('done'), th)
  }
}

export default new Notify
