/**
 * Tip :
 *      Fontawesome           - for icons and brands.
 *      Slick Carousel        - for easy and nice carousel.
 *      Intl-tel-input        - for phone number validate and management.
 *      Pwstrength-bootstrap  - for the awesome password meter and strength.
 *      Sweetalert2           - for the beautiful popup boxes it used to notify.
 *      WebFont               - for @font-face manager that handle different sources.
 *      Turbolinks            - for making the web navigating become faster.
 */
import { fontawesome } from '../fontawesome'
import 'slick-carousel'
import 'intl-tel-input'
import 'pwstrength-bootstrap/dist/pwstrength-bootstrap'
import swal from 'sweetalert2'
import WebFont from 'webfontloader'
import Turbolinks from 'turbolinks'

/**
 * Store the json settings in window to have global access.
 */
let jsonSettings = document.querySelector('[data-settings-selector="settings-json"]')
window.settings = jsonSettings ? JSON.parse(jsonSettings.textContent) : {}

/**
 * Store the swal and language from html in window.
 */
window.swal = swal
window.locale = $('html').attr('lang')

/**
 * Function that will used to initialize the plugins.
 * It will used in the frontend User Interface.
 */
export default (createApp) => {
  /**
   * Start the Turbolinks
   */
  Turbolinks.start()

  /**
   * Load the web font that will be used.
   */
  WebFont.load({
    google: {
      families: ['Roboto']
    }
  })

  /**
   * Add event listener when load the app.
   * It will listen to cookie consent from initialization.
   */
  window.addEventListener('load', () => {
    window.cookieconsent.initialise({
      'palette': {
        'popup': {
          'background': '#fff',
          'text': '#777'
        },
        'button': {
          'background': '#3097d1',
          'text': '#ffffff'
        }
      },
      'showLink': false,
      'theme': 'edgeless',
      'content': {
        'message': window.settings.cookieconsent.message,
        'dismiss': window.settings.cookieconsent.dismiss
      }
    })
  })

  /**
   * Add event listener for turbo links load.
   */
  document.addEventListener('turbolinks:load', () => {
    /**
     * Check if the app is defined in the HTML.
     * Then mount the Vue.
     */
    if (document.getElementById('app') !== null) {
      const {app} = createApp()
      app.$mount('#app')
    }
    /**
     * Bind all bootstrap tooltips.
     */
    $('[data-toggle="tooltip"]').tooltip()
    /**
     * Bind all bootstrap popovers.
     */
    $('[data-toggle="popover"]').popover()
    /**
     * Set the font awesome.
     */
    fontawesome.dom.i2svg()

    /**
     * Set the configs of Slick plugin.
     */
    $('[data-toggle="slider"]')
      .not('.slick-initialized')
      .slick({
        dots: true,
        infinite: true,
        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 3,
        responsive: [
          {
            breakpoint: 768,
            settings: {
              slidesToShow: 2
            }
          },
          {
            breakpoint: 576,
            settings: {
              slidesToShow: 1
            }
          }
        ]
      })

    /**
     * Bind all the Swal to confirm buttons.
     */
    $('[data-toggle="confirm"]').click((e) => {
      /**
       * Prevent the default events.
       */
      e.preventDefault()

      /**
       * Define the Swal settings.
       */
      window.swal({
        title: $(e.currentTarget).attr('data-trans-title'),
        type: 'warning',
        showCancelButton: true,
        cancelButtonText: $(e.currentTarget).attr('data-trans-button-cancel'),
        confirmButtonColor: '#dd4b39',
        confirmButtonText: $(e.currentTarget).attr('data-trans-button-confirm')
      }).then((result) => {
        if (result.value) {
          $(e.target).closest('form').submit()
        }
      })
    })

    /**
     * Bind the password strength meter then define the config.
     */
    $('[data-toggle="password-strength-meter"]').pwstrength({
      ui: {
        bootstrap4: true
      }
    })

    /**
     * Bind the intl Tel Input to type "tel" then define the config.
     */
    $('[type="tel"]').intlTelInput({
      autoPlaceholder: 'aggressive',
      utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.0.14/js/utils.js',
      initialCountry: window.locale === 'en' ? 'us' : window.locale,
      preferredCountries: ['us', 'gb', 'fr']
    })

    /**
     * Bootstrap tabs nav specific hash manager.
     */
    let hash = document.location.hash
    let tabanchor = $('.nav-tabs a:first')

    if (hash) {
      tabanchor = $(`.nav-tabs a[href="${hash}"]`)
    }

    tabanchor.tab('show')

    $('a[data-toggle="tab"]').on('show.bs.tab', (e) => {
      window.location.hash = e.target.hash
    })
  })
}
