<template>
  <div id="app">

    <router-view />
  </div>
</template>

<script>

export default {
  name: 'App',
  components: {  
     
  }
}
</script>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@600&family=Roboto&display=swap');

  *{ 
        padding: 0; 
        margin: 0; 
  }
  
  body{
    background-color: #fafafa;
    height: 100vh;
  }

  #app {
    font-family: 'Roboto', sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    color: #2c2843;
    height: 100%;
  }
</style>
