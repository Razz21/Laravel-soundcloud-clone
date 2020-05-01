import axios from "axios";
import { mutations } from "@/store";

axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
axios.defaults.withCredentials = true;

const instance = axios.create({
  baseURL: "/api"
});

instance.interceptors.response.use(
  response => response,
  error => {
    const statusCode = error.response ? error.response.status : null;
    if (statusCode === "401") {
      mutations.setAuthModal("login");
    }
    throw error;
  }
);

export default instance;
