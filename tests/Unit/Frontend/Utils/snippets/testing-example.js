import { shallowMount } from '@vue/test-utils'
import Foo from './Foo'

const factory = (values = {}) => {
  return shallowMount(Foo, {
    data: { ...values  }
  })
}

describe('Foo', () => {
  it('renders a welcome message', () => {
    const wrapper = factory()

    expect(wrapper.find('.message').text()).toEqual("Welcome to the Vue.js cookbook")
  })

  it('renders an error when username is less than 7 characters', () => {
    const wrapper = factory({ username: ''  })

    expect(wrapper.find('.error').exists()).toBeTruthy()
  })

  it('renders an error when username is whitespace', () => {
    const wrapper = factory({ username: ' '.repeat(7) })

    expect(wrapper.find('.error').exists()).toBeTruthy()
  })

  it('does not render an error when username is 7 characters or more', () => {
    const wrapper = factory({ username: 'Lachlan'  })

    expect(wrapper.find('.error').exists()).toBeFalsy()
  })
})










import { mount, createLocalVue } from "@vue/test-utils"
import App from "@/backend/App.vue"
import VueRouter from "vue-router"
import NestedRoute from "@/backend/components/Full.vue"
import routes from "@/backend/routes/index.js"

const localVue = createLocalVue()
localVue.use(VueRouter)

jest.mock("@/backend/components/Full.vue", () => ({
  name: "NestedRoute",
  render: h => h("div")
}))

describe("App", () => {
  it("renders a child component via routing", () => {
    const router = new VueRouter({ routes })
    const wrapper = mount(App, {
      localVue,
      router
    })

    router.push("/")

    expect(wrapper.find(NestedRoute).exists()).toBe(true)
  })
})
