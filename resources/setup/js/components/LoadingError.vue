<template>
    <span class="text-danger" style="font-size: 16px;"><i class="fa fa-exclamation-triangle"></i> &nbsp; <span v-html="error ? error : errorMessage"></span></span>
</template>

<script>
    export default {
        name: "LoadingError",
        props: ['error'],
        data(){
          return {
              errorMessage: 'An error was encountered.'
          }
        },
        mounted() {
            // const error = localStorage.getItem('lastError');
            // if(error) {
            //     this.errorMessage = error;
            // }
            //
            // localStorage.setItem('lastError', '');
        }
    }
</script>

<style scoped>

</style>
