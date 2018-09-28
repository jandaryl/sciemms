/**
 * Backend Client Window Settings
 */
import './load-client-scripts'

/**
 * Vue & Axios with config.
 */
import Vue from 'vue'
import '../axios-config'

/**
 * Babel-polyfill & Bootstrap Vue
 */
import 'babel-polyfill'
import BootstrapVue from 'bootstrap-vue/dist/bootstrap-vue.esm'

/**
 * Vendor plugins components.
 */
import '../../vendor/coreui/components'
import DataTable from './components/Plugins/DataTable'
import RichTextEditor from './components/Plugins/RichTextEditor'
import DateTimePicker from './components/Plugins/DateTimePicker'
import Switch from './components/Plugins/Switch'
import vSelect from './components/Plugins/Select'

/**
 * Vue helper functions.
 */
import { createRouter } from './router'
import { createStore } from './store'
import { createLocales } from '../vue-i18n-config'

/**
 * Main Component
 */
import App from './App.vue'

/**
 * Notification Tool
 */
import Noty from 'noty'

/**
 * Use Bootstrap Vue
 */
Vue.use(BootstrapVue)

/**
 * Use custom components.
 */
Vue.component('v-select', vSelect)
Vue.component('c-switch', Switch)
Vue.component('p-datetimepicker', DateTimePicker)
Vue.component('p-richtexteditor', RichTextEditor)
Vue.component('b-datatable', DataTable)

/**
 * The create app function will use to render into admin dashboard.
 */
export function createApp () {
  /**
   * The create locales will get the locale value from windows that define json settings.
   *
   * Ex. createLocales('en')
   */
  const i18n = createLocales(window.settings.locale)

  /**
   * The create router will get the admin prefix path that define from config app.
   *
   * Ex. createRouter('admin', 'en')
   */
  const router = createRouter(window.settings.adminHomePath, i18n)

  /**
   * The create store will get the named routes that provide by @routes()
   *
   * Ex. createStore('admin.posts.draft.counter')
   */
  const store = createStore(window.route)

  /**
   * Server-side settings
   *
   * To access it as global in all the json settings from load-client-scripts.js
   *
   * Ex. $app.locale, $app.user
   */
  Vue.prototype.$app = window.settings

  /**
   * Server-side named routes
   *
   * Get the named routes from the ziggy function.
   *
   * Ex. @routes()
   */
  Vue.prototype.$app.route = window.route

  /**
   * Client-side permissions
   *
   * 1. Check the user instance if true that came from window settings.
   * 2. Check the user if the id is equal 1 correspond to super admin.
   * 3. Return true or the permission if it is includes from window settings.
   */
  if (Vue.prototype.$app.user) {
    Vue.prototype.$app.user.can = (permission) => {
      if (Vue.prototype.$app.user.id === 1) {
        return true
      }
      return Vue.prototype.$app.permissions.includes(permission)
    }
  }

  /**
   * Form Object to Form Data Object converter
   *
   * The idea is the object is came from Form's Vue.
   * Where it used to act as progressively in vue instance.
   * Object is specifically in model property of data method of vue.
   * Where it was stored the form data as object with form properties.
   *
   * Tip :
   *      Form Data Object use to send form data by using ajax request.
   *
   * 1. Create an new Form Data Object.
   * 2. Separate the property from object.
   * 3. Check if it is a null, append the no value.
   * 4. Check if it is a boolean, append the 1 or 0 value.
   * 5. Check if it is a Date append the converted date value.
   * 6. Check if it is a Object append the property as form key.
   * 7. Store it in the global variable for send request to the backend.
   */
  let objectToFormData = (object, form, namespace) => {
    let formData = form || new FormData()

    for (let property in object) {
      if (!object.hasOwnProperty(property)) {
        continue
      }

      let formKey = namespace ? `${namespace}[${property}]` : property

      if (object[property] === null) {
        formData.append(formKey, '')
        continue
      }
      if (typeof object[property] === 'boolean') {
        formData.append(formKey, object[property] ? '1' : '0')
        continue
      }
      if (object[property] instanceof Date) {
        formData.append(formKey, object[property].toISOString())
        continue
      }
      if (typeof object[property] === 'object' && !(object[property] instanceof File)) {
        objectToFormData(object[property], formData, formKey)
        continue
      }
      formData.append(formKey, object[property])
    }

    return formData
  }

  /**
   * Store the objectToFormData into global variable.
   * To used in the form mixins for sending form request.
   */
  Vue.prototype.$app.objectToFormData = objectToFormData

  /**
   * Set the configs and define function for notification in the dashboard.
   */
  let noty = (type, text) => {
    new Noty({
      layout: 'topRight',
      theme: 'bootstrap-v4',
      timeout: 2000,
      text,
      type
    }).show()
  }

  /**
   * Get the state from the form mixins to notify the user.
   * It might be alert, success, error, warning, and info.
   */
  Vue.prototype.$app.noty = {
    alert: (text) => {
      noty('alert', text)
    },
    success: (text) => {
      noty('success', text)
    },
    error: (text) => {
      noty('error', text)
    },
    warning: (text) => {
      noty('warning', text)
    },
    info: (text) => {
      noty('info', text)
    }
  }

  /**
   * Notify the user if there is an error in the system.
   * It could be 403, undefined, or general exception.
   */
  Vue.prototype.$app.error = (error) => {
    if (error instanceof String) {
      noty('error', error)
      return
    }

    if (error.response) {
      if (error.response.status === 403) {
        noty('error', i18n.t('exceptions.unauthorized'))
        return
      }

      if (error.response.data.error !== undefined) {
        noty('error', error.response.data.message)
        return
      }
    }

    noty('error', i18n.t('exceptions.general'))
  }

  /**
   * Global router guards use to override the HTML title with meta label or app name.
   *
   * Ex. <title>Hello World!</title>
   */
  router.beforeEach((to, from, next) => {
    document.title = `${to.meta.label} | ${window.settings.appName}`
    next()
  })

  /**
   * Make new vue instance with router, store, i18n.
   * Then render it into App component.
   */
  const app = new Vue({
    router,
    store,
    i18n,
    render: (h) => h(App)
  })

  return {app, router, store}
}

/**
 * Check if there is an ID of app that defined in the html.
 * Then mount the vue components.
 */
if (document.getElementById('app') !== null) {
  const {app} = createApp()
  app.$mount('#app')
}
