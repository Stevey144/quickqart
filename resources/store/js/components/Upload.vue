<template>
    <div>
        <div v-if="processingUpload" class="progress-bar bg-success" role="progressbar">
            <span :style="'width: ' + progress + '%'" class="progress-indicator"></span></div>
        <input ref="file" style="display: none!important;" type="file" @change="handleFileUpload">
    </div>
</template>

<script>

    export default {
        name: "Upload",
        props: ['triggerId', 'types'],
        data() {
            return {
                progress: 60,
                file: false,
                processingUpload: false,
                filename: 'Select a file',
                uploadError: '',
                uploadRecord: false
            }
        },

        methods: {
            handleFileUpload() {
                this.uploadError = '';
                this.uploadRecord = false;
                let file = this.$refs.file.files[0];
                this.file = false;
                this.filename = 'Select a file to upload';

                if (!file) {
                    return;
                }
                // if (this.types) {
                //     if (file.type.indexOf(this.types) < 0) {
                //         this.showError( 'An image file is required.');
                //         return;
                //     }
                // }
                // if (file.size > 2100792) {
                //     this.showError('File size cannot be more than 2 MB.');
                //     return;
                // }

                this.file = file;
                this.createUpload();

            },
            createUpload() {
                if (!this.file) {
                    this.showError('A file of size not more than 2 MB is required.');
                    return;
                }
                this.processingUpload = true;
                let formData = new FormData();
                formData.append('file', this.file);

                this.progress = 0;

                axios.post(baseUrl + 'upload', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    onUploadProgress: (progressEvent) => {
                        this.progress = parseInt(Math.round((progressEvent.loaded * 100) / progressEvent.total))
                        this.$root.$emit(eventName.uploadProgress, {uploadId: this.triggerId, value: this.progress})
                    }
                }).then(response => {
                    if (response) {
                        this.uploadRecord = response.data.data;
                        this.$root.$emit(eventName.uploadDone, {uploadId: this.triggerId, value: response.data.data})
                    }

                    this.processingUpload = false;
                }).catch((error) => {
                    this.processingUpload = false;
                    this.showError('');
                });
            },
            showError(error) {
                this.$root.$emit(eventName.uploadError, {uploadId: this.triggerId, value: error});
            }
        },

        mounted() {
            this.$root.$on(eventName.triggerUpload, (data) => {
                if (this.triggerId === data) {
                    this.$refs.file.click();
                }
            });
        },
        beforeDestroy() {
            this.$root.$off(eventName.triggerUpload);
        }

    }
</script>

<style scoped>
    .progress-bar {
        height: 5px!important;
        width: 100%;
        position: relative;
    }

    .progress-indicator {
        display: inline-block;
        transition: width ease 0.5s;
        background: #0c5c30;
        height: 5px!important;
    }
</style>
