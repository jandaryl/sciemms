import { shallowMount, createLocalVue } from "@vue/test-utils"
import App from "@/backend/App.vue"

describe("App", () => {
  it("Simple test for demo...", () => {

    let wrapper = shallowMount(App,  {
        stubs: ['router-view']
    })

    expect(wrapper.isVueInstance()).toBe(true)

    expect(wrapper.element).toMatchSnapshot()
  })
})
