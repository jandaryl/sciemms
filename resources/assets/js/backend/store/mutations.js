/**
 * The mutation is all about storing of latest commit from actions.
 * It will get the data from the actions.js.
 */
export default {
  SET_COUNTER: (state, { type, counter }) => {
    state.counters[type] = counter
  }
}
