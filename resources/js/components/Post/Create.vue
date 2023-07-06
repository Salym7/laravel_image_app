<template>
    <h2>Create</h2>
    <div class="card w-75">
        <input
            v-model="title"
            type="text"
            class="form-control mb-3"
            placeholder="title"
        />
        <div ref="dropzone" class="p-5 bg-dark text-center text-light btn mb-3">
            Upload
        </div>
        <div>
            <vue-editor
                id="editor"
                v-model="content"
                class="mb-3"
                useCustomImageHandler
                @image-added="handleImageAdded"
            />
        </div>
        <input
            @click.prevent="store"
            class="btn btn-primary"
            type="submit"
            value=""
        />
    </div>
    <div class="mt-5 post">
        <div v-if="post">
            <h4>{{ post.title }}</h4>
            <div v-for="image in post.images" class="mb-3">
                <img :src="image.preview_url" class="mb-3 preview_image" />
                <img :src="image.url" alt="" class="image" />
            </div>
            <div class="ql-editor" v-html="post.content"></div>
        </div>
    </div>
</template>

<script>
import axios from "axios"
import Dropzone from "dropzone"
import { VueEditor } from "vue3-editor"
export default {
    data() {
        return {
            title: null,
            dropzone: null,
            post: null,
            content: null,
        }
    },

    mounted() {
        this.dropzone = new Dropzone(this.$refs.dropzone, {
            url: "asd",
            autoProcessQueue: false,
            addRemoveLinks: true,
        })

        this.dropzone.on("removedfile", (file) => {
            console.log(file)
        })

        this.getPost()
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
            data.append("content", this.content)
            this.title = ""
            this.content = ""
            axios.post("/api/posts", data).then(this.getPost())
        },
        getPost() {
            axios.get("/api/posts").then((res) => {
                this.post = res.data.data
            })
        },
        handleImageAdded(file, Editor, cursorLocation, resetUploader) {
            const formData = new FormData()
            formData.append("image", file)

            axios
                .post("/api/posts/images", formData)
                .then((result) => {
                    console.log(result)
                    const url = result.data.url // Get url from response
                    Editor.insertEmbed(cursorLocation, "image", url)
                    resetUploader()
                })
                .catch((err) => {
                    console.log(err)
                })
        },
    },
    components: {
        VueEditor,
    },
}
</script>
<style>
.dz-success-mark {
    display: none;
}
.dz-error-mark {
    display: none;
}
.ql-editor img,
.post .image {
    width: 100%;
}
</style>
