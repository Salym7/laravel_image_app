<template>
    <div class="container">
        <div class="row justify-content-center p-5">
            <div class="p-4">
                <router-link class="me-3" :to="{ name: 'home' }"
                    >Home</router-link
                >
                <router-link class="me-3" :to="{ name: 'post.index' }"
                    >Watch</router-link
                >
                <router-link class="me-3" :to="{ name: 'post.create' }"
                    >Create</router-link
                >
                <router-link class="me-3" :to="{ name: 'post.update' }"
                    >Update</router-link
                >
                <router-view :key="$route.fullPath"></router-view>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {}
    },

    mounted() {},
    methods: {},
}
</script>
<style></style>
