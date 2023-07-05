import "./bootstrap";
import { createApp } from "vue";

const app = createApp({});

import App from "./components/App.vue";
app.component("App", App);

app.mount("#app");
