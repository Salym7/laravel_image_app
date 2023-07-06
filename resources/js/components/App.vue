<template>
    <div class="container">
        <div class="row justify-content-center p-5">
            <div class="col-md-8">
                <div class="card w-25">
                    <input
                        v-model="title"
                        type="text"
                        class="form-control mb-3"
                        placeholder="title"
                    />
                    <div
                        ref="dropzone"
                        class="p-5 bg-dark text-center text-light btn mb-3"
                    >
                        Upload
                    </div>
                    <input
                        @click.prevent="store"
                        class="btn btn-primary"
                        type="submit"
                        value=""
                    />
                </div>
                <div class="mt-5">
                    <div v-if="post">
                        <h4>{{ post.title }}</h4>
                        <div v-for="image in post.images" class="mb-3">
                            <img :src="image.url" alt="" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios"
import Dropzone from "dropzone"
export default {
    data() {
        return {
            title: null,
            dropzone: null,
            post: null,
        }
    },

    mounted() {
        this.dropzone = new Dropzone(this.$refs.dropzone, {
            url: "asd",
            autoProcessQueue: false,
        })
        this.getPosts()
    },
    methods: {
        store() {
            const data = new FormData()
            const files = this.dropzone.getAcceptedFiles()
            files.forEach((file) => {
                data.append("images[]", file)
                this.dropzone.removeFile(file)
            })
            data.append("title", this.title)
            this.title = ""
            axios.post("/api/posts", data)
        },
        getPosts() {
            axios.get("/api/posts").then((res) => {
                this.post = res.data.data
            })
        },
    },
}
</script>
