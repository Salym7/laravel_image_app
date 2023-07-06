<template>
    <h2>Update Post</h2>
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
                @image-removed="handleImageRemoved"
                @image-added="handleImageAdded"
            />
        </div>
        <input
            @click.prevent="update"
            class="btn btn-primary"
            type="submit"
            value=""
        />
    </div>
    <!-- <div class="mt-5">
        <div v-if="post">
            <h4>{{ post.title }}</h4>
            <div v-for="image in post.images" class="mb-3">
                <img :src="image.preview_url" class="mb-3" />
                <img :src="image.url" alt="" />
            </div>
            <div class="ql-editor" v-html="post.content"></div>
        </div>
    </div> -->
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
            imagesIdForDelete: [],
            imagesUrlForDelete: [],
        }
    },

    mounted() {
        this.dropzone = new Dropzone(this.$refs.dropzone, {
            url: "asd",
            autoProcessQueue: false,
            addRemoveLinks: true,
        })
        this.dropzone.on("removedfile", (file) => {
            this.imagesIdForDelete.push(file.id)
        })
        this.getPost()
    },
    methods: {
        update() {
            const data = new FormData()
            const files = this.dropzone.getAcceptedFiles()
            files.forEach((file) => {
                data.append("images[]", file)
                this.dropzone.removeFile(file)
            })

            this.imagesIdForDelete.forEach((idForDelete) => {
                data.append("image_ids_for_delete[]", idForDelete)
            })
            this.imagesUrlForDelete.forEach((urlForDelete) => {
                data.append("image_urls_for_delete[]", urlForDelete)
            })

            data.append("title", this.title)
            data.append("content", this.content)
            data.append("_method", "PATCH")
            this.title = ""
            this.content = ""
            axios.post(`/api/posts/${this.post.id}`, data).then((res) => {
                let previews =
                    this.dropzone.previewsContainer.querySelectorAll(
                        ".dz-image-preview"
                    )
                previews.forEach((preview) => {
                    preview.remove()
                })
                this.getPost()
            })
        },
        getPost() {
            axios.get("/api/posts").then((res) => {
                this.post = res.data.data
                this.title = this.post.title
                this.content = this.post.content
                // this.dropzone.removeAllFiles()
                this.post.images.forEach((image) => {
                    let file = {
                        id: image.id,
                        name: image.name,
                        size: image.size,
                    }
                    this.dropzone.displayExistingFile(file, image.preview_url)
                })
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
        handleImageRemoved(url) {
            this.imagesUrlForDelete.push(url)
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
</style>
