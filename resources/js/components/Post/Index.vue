<template>
    <h2>Index</h2>
    <div class="mt-5">
        <div v-if="post">
            <h4>{{ post.title }}</h4>
            <div v-for="image in post.images" class="mb-3">
                <img :src="image.preview_url" class="mb-3" />
            </div>
            <div class="ql-editor" v-html="post.content"></div>
        </div>
    </div>
</template>

<script>
import axios from "axios"
export default {
    data() {
        return {
            post: null,
        }
    },

    mounted() {
        this.getPost()
    },
    methods: {
        getPost() {
            axios.get("/api/posts").then((res) => {
                this.post = res.data.data
            })
        },
    },
}
</script>
<style></style>
