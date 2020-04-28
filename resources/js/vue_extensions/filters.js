import Vue from "vue";

Vue.filter("mb", (val, precision = 2) => {
  // convert bytes to mb
  return parseFloat(val / 1024 / 1024).toPrecision(precision); //MB
});

Vue.filter("highlight", function(word, query) {
  var check = new RegExp(query, "i");
  return word.toString().replace(check, function(matchedText, a, b) {
    return "<mark>" + matchedText + "</mark>";
  });
});

Vue.filter("remainingTime", function(val, duration) {
  val = ~~(duration - val); //Math.floor
  return (val - (val %= 60)) / 60 + (9 < val ? ":" : ":0") + val;
});

Vue.filter("elapsedTime", function(val) {
  val = ~~val; //Math.floor
  return (val - (val %= 60)) / 60 + (9 < val ? ":" : ":0") + val;
});
