<template>
    <button class="subChat">
        <img src="https://img.icons8.com/ios-filled/50/000000/hashtag.png"/>
        <div class="title">
                {{subChat.title}}
        </div>
    </button>
</template>

<script>
export default {
    props: ['subChat'],
}
</script>

<style scoped>
    .subChat {
        float: right;
        width: 80%;
        height: 40px;
        color: #2c3e50;
        text-align: left;
        background-color: inherit;
        border-radius: 10px;
        cursor: pointer;
        box-sizing: border-box;
        border: none;
        outline: none;
        margin: 10px 0px 0px 0px;
        padding-left: 15px;
    }

    .subChat:hover {
        background-color: #89e6f1;
    }

    .subChat:focus {
        background-color: #97f3ff;
    }

    img {
        float: left;
        height: 25px;
    }
    
    .title {
        font-size: 16px;
        font-weight: bold;
        text-align: left;
        margin: 4px 0px 0px 30px;
    }
</style>