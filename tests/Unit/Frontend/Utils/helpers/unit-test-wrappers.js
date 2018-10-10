////////////////////////////////////////////////////////////////////////////////////////////////////////
///                                    Unit Testing                                                  ///
////////////////////////////////////////////////////////////////////////////////////////////////////////

let assertTrue = data => {
  expect(data).toBeTruthy()
}

let assertFalse = data => {
  expect(data).toBeFalsy()
}

let assertEqual = (data, asserted = true) => {
  expect(data).toBe(asserted)
}

let assertEquals = (data, assertion) => {
    expect(data).toEqual(assertion)
}

let assertSnapMatched = data => {
  expect(element).toMatchSnapshot()
}

let assertCalledWith = (event, asserted) => {
  expect(event).toBeCalledWith(asserted)
}

function assertCalled(event)
{
  expect(event).toBeCalled()
}

export {
    assertTrue,
    assertFalse,
    assertEqual,
    assertEquals,
    assertSnapMatched,
    assertCalledWith,
    assertCalled
}
