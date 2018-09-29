/**
 * Use the Vue and Vuex
 */
import Vue from 'vue'
import Vuex from 'vuex'

/**
 * Use the actions and mutations functions.
 */
import { createActions } from './actions'
import mutations from './mutations'

/**
 * It will register the Vuex in the Vue instance.
 */
Vue.use(Vuex)

/**
 * Function that connect the whole logic of Vuex.
 * It will use actions and mutations JS files.
 * Set the state and wait for AJAX data.
 * Update the counters properties.
 */
export function createStore (route) {
  /**
   * Call the createActions function.
   */
  const actions = createActions(route)

  return new Vuex.Store({
    state: {
      counters: {
        newPostsCount: 0,
        pendingPostsCount: 0,
        publishedPostsCount: 0,
        activeUsersCount: 0,
        formSubmissionsCount: 0
      }
    },
    actions,
    mutations
  })
}
