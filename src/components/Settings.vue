<template>
  <div class="settings">
    <form>
      <div class="form-group">
        <label for="SOLR Connection URL">SOLR Connection URL:</label>
        <input
          type="url"
          class="form-control"
          id="solrUrl"
          aria-describedby="solrUrl"
          placeholder="http://localhost"
        />
        <small class="form-text text-muted">Example: http://localhost:8983/</small>
      </div>
      <div class="form-group">
        <label for="solrCoreName">Solr core name:</label>
        <input type="text" class="form-control" id="solrCoreName" placeholder="articles" />
        <small class="form-text text-muted">Example: articles</small>
      </div>
      <div class="form-group">
        <label for="solrFormula">Solr relevancy calculation:</label>
        <textarea class="form-control" id="solrFormula" rows="6" v-model="solrFormula"></textarea>
        <small
          class="form-text text-muted"
        >Example: Use $k0, $k1, $k2 variables and solr field names</small>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
</template>

<script>
export default {
  name: "Settings",
  props: {
    msg: String
  },
  data: function() {
    return {
      solrFormula:
        "sub(10,product(2,avail))^$k2 product(1,sqrt(salability))^$k1 sub(10,div(log(div(sum(1,abs(age)),10)),0.3))^$k0"
    };
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
h3 {
  margin: 40px 0 0;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
.settings {
  width: 30%;
  padding: 5px;
  text-align: left;
  display: inline-block;
}
</style>
