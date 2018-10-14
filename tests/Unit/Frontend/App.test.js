import { shallowMount, createLocalVue } from "@vue/test-utils"
import AppComponent from "@backend/App.vue"

let App = (values = {}) => { return shallowMount(AppComponent, values) }
let wrapper = App({ stubs: ['router-view'] })

describe("App", () => {
  it("is a Vue instance", () => {
    expect(wrapper.isVueInstance()).toBe(true)
  })

  it("is matched in the previos snapshots", () => {
    expect(wrapper.element).toMatchSnapshot()
  })
})
