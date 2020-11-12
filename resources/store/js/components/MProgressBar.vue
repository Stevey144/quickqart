<template>
    <div style="position: relative">
        <div class="m-progress-bar">
            <div class="line"></div>
            <div class="subline inc"></div>
            <div class="subline dec"></div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "MProgressBar"
    }
</script>

<style scoped>
    .m-progress-bar {
        position: absolute;
        width: 100%;
        height: 5px;
        overflow-x: hidden;
    }

    .line {
        position: absolute;
        opacity: 0.4;
        background: #042C59;
        width: 150%;
        height: 5px;
    }

    .subline {
        position: absolute;
        background: #042C59;
        height: 5px;
    }

    .inc {
        animation: increase 2s infinite;
    }

    .dec {
        animation: decrease 2s 0.5s infinite;
    }

    @keyframes increase {
        from {
            left: -5%;
            width: 5%;
        }
        to {
            left: 130%;
            width: 100%;
        }
    }

    @keyframes decrease {
        from {
            left: -80%;
            width: 80%;
        }
        to {
            left: 110%;
            width: 10%;
        }
    }
</style>
