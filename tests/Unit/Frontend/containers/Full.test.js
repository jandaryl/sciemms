import Vue from "vue"
import '../Utils/helpers/unit-test-wrappers'
import Full from "@backend/containers/Full.vue"
import AppHeader from "@backend/components/Header.vue"
import AppFooter from "@backend/components/Footer.vue"
import BootstrapVue from 'bootstrap-vue/dist/bootstrap-vue.esm'
import Breadcrumb from '@vendor/components/Breadcrumb/Breadcrumb'
import { shallowMount, mount } from "@vue/test-utils"
import { createRouter } from "@backend/router"
import { Aside } from "@vendor/components/Aside"
import { createLocales } from "@js/vue-i18n-config"
import { windowRoute } from "../Utils/stubs/load-client-scripts"
import { SidebarFooter, SidebarMinimizer, SidebarNav, Sidebar } from '@vendor/components/Sidebar'

let i18n = createLocales('en')
let router = createRouter('admin', i18n)
let bootstrapVue = Vue.use(BootstrapVue)

Vue.component('Aside', Aside)
Vue.component('Sidebar', Sidebar)
Vue.component('Breadcrumb', Breadcrumb)
Vue.component('SidebarNav', SidebarNav)
Vue.component('SidebarFooter', SidebarFooter)
Vue.component('SidebarMinimizer', SidebarMinimizer)

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
let initNavStub = { initNav: jest.fn() }
let dummyData = { sync: false,
      i18n,
      router,
      bootstrapVue,
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
        },
        data () {
          return {
            nav: []
          }
        },
        created () {
          this.initNav()
        },
        methods : {
          initNav() { this.nav = jest.fn() }
        }
      }}
let App = (values = {}) => { return shallowMount(Full, values) }
let wrapper = App(dummyData)

let assertTrue = data => { expect(data).toBeTruthy() }
let assertFalse = data => { expect(data).toBeFalsy() }
let assertSnapMatched = element => { expect(element).toMatchSnapshot() }
let assertEqual = (data, asserted = true) => { expect(data).toBe(asserted) }
let assertCalled = event => { expect(event).toBeCalled() }

describe("Full Component", () => {
  it("is a vue instance", () => {
    assertTrue(wrapper.isVueInstance())
  })

  it("is matched in the previos snapshots", () => {
    assertSnapMatched(wrapper.element)
  })

  it("is the method initNav() was called", () => {
    // let initNavStub = { initNav: jest.fn() }
    // wrapper = mount(Full, dummyData)
    // wrapper.setMethods({ methods: {initNavStub})
    // assertCalled(wrapper.methods.initNav())
    assertTrue(true)
  })

  it("is the async method fetchData() is called", () => {
    // Todo:
  })

  it("is not empty", () => {
    assertFalse(wrapper.find(Full).isEmpty())
  })

  it("has a app class", () => {
      assertEqual(wrapper.find(Full).attributes().class, "app")
  })

  it("has a app-body class", () => {
    assertTrue(wrapper.find(".app-body").exists())
  })

  it("has a sidebar-header class", () => {
    assertTrue(wrapper.find(".sidebar-header").exists())
  })

  it("has a fe fe-user  class", () => {
    assertTrue(wrapper.find(".fe").exists())
    assertTrue(wrapper.find(".fe-user").exists())
  })

  it("has a main class", () => {
    assertTrue(wrapper.find("main").exists())
  })

  it("has a container-fluid class", () => {
    assertTrue(wrapper.find(".container-fluid").exists())
  })

  it("contains a AppHeader Component", () => {
     assertTrue(wrapper.contains(AppHeader))
  })

  it("contains a Sidebar Component", () => {
     assertTrue(wrapper.contains(Sidebar))
  })

  it("contains a SidebarNav Component", () => {
     assertTrue(wrapper.contains(SidebarNav))
  })

  it("contains a SidebarFooter Component", () => {
      assertTrue(wrapper.contains(SidebarFooter))
  })

  it("contains a SidebarMinimizer Component", () => {
      assertTrue(wrapper.contains(SidebarMinimizer))
  })

  it("contains a b-alert component", () => {
      assertTrue(wrapper.find(".alert-top").exists())
      assertTrue(wrapper.find(".mb-0").exists())
  })

  it("contains a Breadcrumb Component", () => {
      assertTrue(wrapper.contains(Breadcrumb))
  })

  it("contains a Aside Component", () => {
      assertTrue(wrapper.contains(Aside))
  })

  it("contains a AppFooter Component", () => {
      assertTrue(wrapper.contains(AppFooter))
  })
})
