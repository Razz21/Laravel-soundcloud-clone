import Vue from "vue";

Vue.directive("click-outside", {
  bind(el, binding, vnode) {
    var vm = vnode.context;
    var callback = binding.value;

    el.clickOutsideEvent = function(event) {
      if (!(el == event.target || el.contains(event.target))) {
        return callback.call(vm, event);
      }
    };
    document.body.addEventListener("click", el.clickOutsideEvent);
  },
  unbind(el) {
    document.body.removeEventListener("click", el.clickOutsideEvent);
  }
});

Vue.directive("lowercase", {
  update(el) {
    el.value = el.value.toLoweCase();
  }
});
