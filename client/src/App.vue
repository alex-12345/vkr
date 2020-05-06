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
  *{ 
        padding: 0; 
        margin: 0; 
  }
  
  body{
    background-color: #fafafa;
  }

  #app {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    color: #2c3e50;
  }
</style>
