import { extend } from "vee-validate";
import * as rules from "vee-validate/dist/rules";
import { messages } from "vee-validate/dist/locale/en.json";

import { configure } from "vee-validate";

Object.keys(rules).forEach(rule => {
  extend(rule, {
    ...rules[rule],
    message: messages[rule]
  });
});

configure({
  classes: {
    invalid: "is-danger has-text-danger"
  }
});

extend("maxArray", {
  params: ["arr", "max"],

  validate(value, { arr, max }) {
    return arr.length < max;
  },
  message: (fieldName, placeholders) => {
    return `Number of ${fieldName} must be ${placeholders.max} or less`;
  }
});

extend("maxFileSize", {
  params: ["file", "max"],

  validate(value, { file, max }) {
    return file.size / 1024 <= max;
  },
  message: (fieldName, placeholders) => {
    return `Maximum ${fieldName} size is ${placeholders.max} kilobytes`;
  }
});

extend("minDimensions", {
  params: ["width", "height"],

  validate(value, { width, height }) {
    const file = value[0];
    if (!file) return Promise.resolve(false);
    var URL = window.URL || window.webkitURL;
    return new Promise(function(resolve) {
      var image = new Image();
      image.onerror = function() {
        return resolve(false);
      };
      image.onload = function() {
        return resolve(image.width >= width && image.height >= height);
      };
      image.src = URL.createObjectURL(file);
    });
  },
  message: (fieldName, placeholders) => {
    return `Invalid ${fieldName} dimensions.`;
  }
});
