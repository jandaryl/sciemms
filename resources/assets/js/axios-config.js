/**
 * Axios
 */
import axios from 'axios'

/**
 * Define the Axios header to XMLHttpRequest.
 */
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

/**
 * Store the CSRF token that generate from the HTML head.
 */
let token = document.head.querySelector('meta[name="csrf-token"]')

/**
 * Check if there is a token then set it to Axios header.
 */
if (token) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
}

export { axios }
