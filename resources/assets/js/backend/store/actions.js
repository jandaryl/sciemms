/**
 * Use Axios for Ajax request.
 */
import axios from 'axios'

/**
 * Function that will be make ajax request and store as shared data.
 * It will fetch the draft, pending, published, active posts and form submissions.
 *
 * 1. The LOAD_COUNTERS will listen in the vue instance.
 * 2. If it will dispatch then the ajax call to server will trigger.
 * 3. The data if success from ajax request will store in the shared variable.
 */
export function createActions (route) {
  return {
    LOAD_COUNTERS: ({commit}) => {
      return new Promise((resolve) => {
        axios.all([
          axios.get(route('admin.posts.draft.counter')),
          axios.get(route('admin.posts.pending.counter')),
          axios.get(route('admin.posts.published.counter')),
          axios.get(route('admin.users.active.counter')),
          axios.get(route('admin.form_submissions.counter'))
        ])
          .then(axios.spread(
            (
              newPostsCount,
              pendingPostsCount,
              publishedPostsCount,
              activeUsersCount,
              formSubmissionsCount
            ) => {
              commit('SET_COUNTER', {type: 'newPostsCount', counter: newPostsCount.data})
              commit('SET_COUNTER', {type: 'pendingPostsCount', counter: pendingPostsCount.data})
              commit('SET_COUNTER', {type: 'publishedPostsCount', counter: publishedPostsCount.data})
              commit('SET_COUNTER', {type: 'activeUsersCount', counter: activeUsersCount.data})
              commit('SET_COUNTER', {type: 'formSubmissionsCount', counter: formSubmissionsCount.data})

              resolve()
            }))
      })
    }
  }
}
