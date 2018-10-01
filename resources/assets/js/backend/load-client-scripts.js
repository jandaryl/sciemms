/**
 * Tip:
 *     Sweetalert2        - for beautiful popup boxes.
 *     Flatpicker         - for elegant datetime picker.
 *     FlatpickrLocaleFr  - for datetime picker localization.
 */
import swal from 'sweetalert2'
import Flatpickr from 'flatpickr'
import FlatpickrLocaleFr from 'flatpickr/dist/l10n/fr'

/**
 * Store the JS plugins
 */
window.swal = swal
window.Flatpickr = Flatpickr
window.FlatpickrLocaleFr = FlatpickrLocaleFr

/**
 * JS Settings App
 *
 * Decoded the passed json config from backend.blade.php
 */
let jsonSettings = document.querySelector('[data-settings-selector="settings-json"]')
window.settings = jsonSettings ? JSON.parse(jsonSettings.textContent) : {}
