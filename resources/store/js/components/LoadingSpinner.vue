<template>
    <svg class="lds-rolling spinner-wheel" :height="(small ? '30px' : '60px')" :width="(small ? '30px' : '60px')"
         preserveAspectRatio="xMidYMid"
         viewBox="0 0 100 100"
         xmlns="http://www.w3.org/2000/svg">
        <circle cx="50" cy="50" fill="none" r="35" stroke="#041E3D"
                stroke-dasharray="164.93361431346415 56.97787143782138"
                :stroke-width="10"
                transform="rotate(82.6816 50 50)">
            <animateTransform attributeName="transform" begin="0s"
                              calcMode="linear"
                              dur="1s"
                              keyTimes="0;1" repeatCount="indefinite"
                              type="rotate"
                              values="0 50 50;360 50 50"></animateTransform>
        </circle>
    </svg>
</template>

<script>
    export default {
        name: "LoadingSpinner",
        props: ['small', 'text', 'svg', 'svg-only'],
    }
</script>

<style scoped>

</style>
