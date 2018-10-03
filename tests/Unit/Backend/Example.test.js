import { mount } from '@vue/test-utils'
import Component from '@/examples/Counter.vue'

describe('Component', () => {
  it('is a Vue instance', () => {
    const wrapper = mount(Component)
    assertTrue(wrapper.isVueInstance())
    assertSnapMatched(wrapper.element)
  })
})












////////////////////////////////////////////////////////////////////////////////////////////////////////
///                                    Vue Test Helper wrapper                                       ///
////////////////////////////////////////////////////////////////////////////////////////////////////////



let assertTrue = data => {
  expect(data).toBeTruthy()
}

let assertSnapMatched = element => {
  expect(element).toMatchSnapshot()
}
