import "./bootstrap";
import { createApp } from "vue";
import router from "./router";

const app = createApp({});

import App from "./components/App.vue";
app.component("App", App);
app.use(router);

app.mount("#app");
