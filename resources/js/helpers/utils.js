export function typeOf(value) {
  var s = typeof value;
  if (s === "object") {
    if (value) {
      if (Object.prototype.toString.call(value) == "[object Array]") {
        s = "array";
      }
    } else {
      s = "null";
    }
  }
  return s;
}

export function checkTypes(argList, typeList) {
  for (var i = 0; i < argList.length; i++) {
    if (typeList.length > i && typeOf(argList[i]) !== typeList[i]) {
      throw "wrong type: expecting " +
        typeList[i] +
        ", found " +
        typeOf(argList[i]);
    }
  }
}

export function debounce1(func, wait, immediate) {
  var timeout;
  return function() {
    var context = this,
      args = arguments;
    var later = function() {
      timeout = null;
      if (!immediate) func.apply(context, args);
    };
    var callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func.apply(context, args);
  };
}

export function debounce(func, wait = 100) {
  let timeout;
  return function(...args) {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
      func.apply(this, args);
    }, wait);
  };
}

export function mergeDeep(target, source) {
  const isObject = obj => obj && typeof obj === "object";

  if (!isObject(target) || !isObject(source)) {
    return source;
  }

  Object.keys(source).forEach(key => {
    const targetValue = target[key];
    const sourceValue = source[key];

    if (Array.isArray(targetValue) && Array.isArray(sourceValue)) {
      target[key] = targetValue.concat(sourceValue);
    } else if (isObject(targetValue) && isObject(sourceValue)) {
      target[key] = mergeDeep(Object.assign({}, targetValue), sourceValue);
    } else {
      target[key] = sourceValue;
    }
  });

  return target;
}
