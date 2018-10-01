/**
 * Vue Instance
 */
import Vue from 'vue'

/**
 * Vue Translator & generated locales
 */
import VueI18n from 'vue-i18n'
import Locales from './vue-i18n-locales.generated.js'

/**
 * Use the VueI18n as internationalization plugin.
 */
Vue.use(VueI18n)

/**
 * The createLocales function will set the passed locale value from config app,
 * and set also the vue generated locales that will be correspond to translated data.
 */
export function createLocales (locale) {
  return new VueI18n({
    locale: locale,
    messages: Locales
  })
}
