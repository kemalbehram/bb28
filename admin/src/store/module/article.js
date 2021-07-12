import { get } from '@/libs/request'

export default {
  state: {
    category: [],
  },

  actions: {
    getArticleCat({ state, commit }, that) {
      if (state.category.length > 0  ) {
        return new Promise((resolve) => resolve(state.category))
      }

      that.loading = true
      return get('/article/cat/index', {}, false).then((res) => {
        if (res.code !== 200) {
          return (that.loading = res.message)
        }
        that.loading = false
        state.category = res.data.items
        return res.data.items
      })
    }
  }
}
