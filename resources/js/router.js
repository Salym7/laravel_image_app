import { createRouter, createWebHistory } from "vue-router";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: "/",
            name: "home",
            component: function () {
                return import("./components/Home.vue");
            },
        },
        {
            path: "/posts/",
            name: "post.index",
            component: function () {
                return import("./components/Post/Index.vue");
            },
        },
        {
            path: "/posts/create",
            name: "post.create",
            component: function () {
                return import("./components/Post/Create.vue");
            },
        },
        {
            path: "/posts/update",
            name: "post.update",
            component: function () {
                return import("./components/Post/Update.vue");
            },
        },
    ],
});

export default router;
