<template>
  <div id="app">
    <div id="header" class="border-bottom">
      <div id="logo">
        <img alt="Solr logo" src="./assets/logo.png" />
      </div>
      <div id="nav">
        <router-link to="/">Tune</router-link>|
        <router-link to="/setup">Setup</router-link>
      </div>
    </div>
    <div class="main">
      <router-view />
    </div>
  </div>
</template>

<style lang="scss">


#app {
  font-family: "Avenir", Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}
#nav {
  padding: 30px;
  a {
    font-weight: bold;
    color: #2c3e50;
    &.router-link-exact-active {
      color: #42b983;
    }
  }
}

#header {
  padding: 10px;
}

#logo {
  float: left;
  padding: 5px;
}
</style>
