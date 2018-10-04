import Vue from "vue"
import '../Utils/helpers/unit-test-wrappers'
import Full from "@/backend/containers/Full.vue"
import BootstrapVue from 'bootstrap-vue/dist/bootstrap-vue.esm'
import Breadcrumb from '@vendor/components/Breadcrumb/Breadcrumb'
import { shallowMount } from "@vue/test-utils"
import { createRouter } from "@/backend/router"
import { createLocales } from "@/vue-i18n-config"
import { windowRoute } from "../Utils/stubs/load-client-scripts"
import { SidebarFooter, SidebarMinimizer, SidebarNav, Sidebar } from '@vendor/components/Sidebar'

let bootstrapVue = Vue.use(BootstrapVue)
let i18n = createLocales('en')
let router = createRouter('admin', i18n)

Vue.component('Breadcrumb', Breadcrumb)
Vue.component('SidebarMinimizer', SidebarMinimizer)
Vue.component('SidebarFooter', SidebarFooter)
Vue.component('SidebarNav', SidebarNav)
Vue.component('Sidebar', Sidebar)

let what = 'is this code'
let name = 'Jan Daryl Galbo'
let route = "test\.route"
let routeName = 'Test Route'
let routeMatched = true
let supername  = 'superadmin'
let appName  = 'SCIEMMS'
let editorName = 'Vue Test'
let editorSiteUrl = 'https://sciemms.frb.io'
let testLocale = 'Test Locale'
let dummyData = { sync: false,
      i18n,
      router,
      bootstrapVue ,
      mocks: {
        $app: {
          user: {
            name: name,
            can: () => true
          },
          route: (route) => route,
          usurperName: supername,
          appName: appName,
          editorName: editorName,
          editorSiteUrl: editorSiteUrl
        },
        $store : {
          state: {
            counters: {
                newPostsCount: 0,
                pendingPostsCount: 0,
                publishedPostsCount: 0,
                activeUsersCount: 0,
                formSubmissionsCount: 0
              }
          }
        }
      }}
let App = (values = {}) => { return shallowMount(Full, values) }
let wrapper = App(dummyData)

let assertTrue = data => { expect(data).toBeTruthy() }
let assertSnapMatched = element => { expect(element).toMatchSnapshot() }

describe("Full Component", () => {
  it("is a vue instance", () => {

    assertTrue(wrapper.isVueInstance())
  })

  it("is matched in the previos snapshots", () => {

    assertSnapMatched(wrapper.element)
  })
})
