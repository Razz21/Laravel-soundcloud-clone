import Vue from "vue";

const LoadingComponent = {
  template: `
  <div class="loader-wrapper">
    <div class="loader">
      <div class="is-loading"></div>
    </div>
  </div>
  `
};
const ErrorComponent = {
  template: `
  <div class="error-wrapper">
    <i class="fas fa-exclamation-circle"></i>
    <h3>Some error occured, try again later.</h3>
  </div>
  `
};

export default item => () => ({
  // The component to load (should be a Promise)
  component: item,
  // A component to use while the async component is loading
  loading: LoadingComponent,
  // A component to use if the load fails
  error: ErrorComponent,
  // Delay before showing the loading component. Default: 200ms.
  delay: 200,
  // The error component will be displayed if a timeout is
  // provided and exceeded. Default: Infinity.
  timeout: 3000
});
