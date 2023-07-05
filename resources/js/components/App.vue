<template>
    <div class="container">
        <div class="row justify-content-center p-5">
            <div class="col-md-8">
                <div class="card">
                    <div
                        ref="dropzone"
                        class="p-5 bg-dark text-center text-light w-25 btn d-block"
                    >
                        Upload
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Dropzone from "dropzone"
export default {
    data() {
        return {
            dropzone: null,
        }
    },

    mounted() {
        this.dropzone = new Dropzone(this.$refs.dropzone, { url: "asd" })
    },
}
</script>
