/**
 * Frontend Client Window Settings
 */
import loadClientScripts from './load-client-scripts'

// Vue & Axios with config.
import Vue from 'vue'
import { axios } from '../axios-config'

/**
 * Babel-polyfill & Bootstrap Vue
 */
import 'babel-polyfill'
import BootstrapVue from 'bootstrap-vue/dist/bootstrap-vue.esm'

/**
 * Vue helper functions.
 */
import { createLocales } from '../vue-i18n-config'

/**
 * Set the axios in window for global access.
 */
window.axios = axios

/**
 * Use Bootstrap Vue
 */
Vue.use(BootstrapVue)

/**
 * The create app function will use to render into frontend.
 */
export function createApp () {
  const i18n = createLocales(window.locale)

  const app = new Vue({
    i18n
  })

  return { app }
}

/**
 * Call the Load Client Scripts then passed the createApp function.
 * To get the new instance of vue and i18n.
 */
loadClientScripts(createApp)
