import { shallowMount } from '@vue/test-utils'
import { createApp } from "../Utils/stubs/createApp"
import { createLocales } from '@/vue-i18n-config'
import AppHeaderComponent from '@/backend/components/Header.vue'


describe('Header', () => {
    it('just temporary assertion from header component', () => {

        expect(true).toBe(true)
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
