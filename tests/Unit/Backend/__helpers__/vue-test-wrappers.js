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

let assertSnapMatched = element => {
  expect(element).toMatchSnapshot()
}

let assertCalledWith = (event, asserted) => {
  expect(event).toBeCalledWith(asserted)
}

let assertCalled = event => {
  expect(event).toBeCalled()
}

////////////////////////////////////////////////////////////////////////////////////////////////////////
///                                    Integration Testing                                           ///
////////////////////////////////////////////////////////////////////////////////////////////////////////

let see = (text, selector) => {
    let wrap = selector ? wrapper.find(selector) : wrapper

    expect(wrap.html()).toContain(text)
}

let dontSee = (text, selector) => {
    let wrap = selector ? wrapper.find(selector) : wrapper

    expect(wrap.html()).not.toContain(text)
}

let type = (text, selector) => {
    let node = wrapper.find(selector)

    node.element.value = text
    node.trigger('input')
}

let click = selector => {
    wrapper.find(selector).trigger('click')
}

let press = (selector, action) => {
    wrapper.find(selector).trigger(action)
}

let check = selector => {
   wrapper.find(selector).setChecked()
}

let setProps = object => {
    wrapper.setProps(object)
}

let setData = object => {
    wrapper.setData(object)
}

let setMethod = object => {
    wrapper.setMethod(object)
}

let setValue = (selector, value) => {
    wrapper.find(selector).setValue(value)
}

let contain = selector => {
     wrapper.contains(selector).toBe(true)
}

let notContain = selector => {
     wrapper.contains(selector).toBe(false)
}

let visit = route => {
  router.push(route)
}

////////////////////////////////////////////////////////////////////////////////////////////////////////
///                                    Undefine Helpers                                              ///
////////////////////////////////////////////////////////////////////////////////////////////////////////

 // findText() =  findText("span")

  wrapper.find("span").text()

 // findAllText() =  findAllText("span")

  wrapper.findAll("span").text()

 // findValue() =  findValue("span")

  wrapper.find("span").element.value

// isExists() = isExists('does-not-exist')

  wrapper.find('does-not-exist').exists()

// isVisible() = isVisible('span')

  wrapper.find("span").isVisible()


// isCalled() = isCalled(jest.fn())

  expect(jest.fn()).toHaveBeenCalled()

// assertFalse() or false() = assertFalse(getErrors())

  expect(getErrors()).toBeFalsy()

// assertTrue() or true() = assertTrue(getErrors())

  expect(true()).toBeTruthy();

// assertNull() or null() = assertNull(getNull())

  expect(null()).toBeNull();

// assertUndefined() or undifined() = assertUndefined(undefinedVar())

  expect(undefinedVar()).toBeUndefined()

// assertContained() or Contained() = assertContained(getAllFlavors(), 'lime')

  expect(getAllFlavors()).toContain('lime')

// assertContainedEqual() or Contained() = assertContainedEqual(myBeverage(), myBeverage)

  expect(myBeverages()).toContainEqual(myBeverage)

// assertMatched() or matched() = assertMatched (essayOnTheBestFlavor(), /grapefruit/)

  expect(essayOnTheBestFlavor()).toMatch(/grapefruit/)

// assertObjectMatched() or objectMatched() = assertObjectMatched (houseForSale, desiredHouse)

  expect(houseForSale).toMatchObject(desiredHouse)

// assertEvent() = assertEvent(finished)

  expect(wrapper.emitted().finished).toBeTruthy()

// assertEventPayload()= assertEventEquals(foo[1], [123])

  expect(wrapper.emitted().foo[1]).toEqual([123])

// assertEventCount() = assertEventCount(foo, 2)

  expect(wrapper.emitted().foo.length).toBe(2)

// assertEquals() or equals() = equals( data,  { username: "alice" })

  expect(data).toEqual({ username: "alice" })

// assertNotEquals() or notEquals() = notEquals( data,  { username: "alice" })

  expect(data).not.toEqual({ username: "alice" })

// assertEqualed() = assertEqualed( findText("span"), "Not Authorized"))

  expect(NumberRenderer.computed.numbers()).toBe("1, 3, 5, 7, 9")

// assertNotEqualed() = assertNotEqualed( findText("span"), "Not Authorized"))

  expect(NumberRenderer.computed.numbers()).not.toBe("1, 3, 5, 7, 9")

// hasLength() = hasLength([1, 2, 3], 3)

  expect([1, 2, 3]).toHaveLength(3)

// hasNoLength() = hasLength('', 3)

  expect('').not.toHaveLength(5)

// hasProperty = hasProperty(houseForSale, 'bedrooms', 4)

  expect(houseForSale).toHaveProperty('bedrooms', 4)
