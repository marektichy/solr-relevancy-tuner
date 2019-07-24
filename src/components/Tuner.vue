<template>
  <div class="container">
    <div class="row py-3">
      <div class="col-3 order-2" id="sticky-sidebar">
        <div class="sticky-top border-left">
          <div class="knobs-group">
            <knob-control v-model="k0"></knob-control>"Prodeje"
            <knob-control v-model="k1"></knob-control>"Dostupnost"
            <knob-control v-model="k2"></knob-control>"Stáří"
            <div class="debug border-top">
              <span v-html="debug"></span>
            </div>
          </div>
        </div>
      </div>
      <div class="col" id="main">
        Hledat:
        <vue-simple-suggest
          v-model="chosen"
          :list="getSuggestions"
          :filter-by-query="false"
          :prevent-submit="true"
        >
          <div slot="suggestion-item" slot-scope="{ suggestion }">
            <div v-html="suggestion"></div>
          </div>
        </vue-simple-suggest>
        <span id="spacer"></span>
        <h2>Doporučujeme:</h2>
        <div class="recommended-group">
          <div class="recommended" v-for="rec in recommended" :key="rec.id">
            <div class="pane">
              <div class="img-wrap">
                <img class="envelope" :src="rec.img" />
              </div>
              <div class="text-wrap">
                <b>
                  <em>
                    <small>{{rec.score}}</small>
                  </em>
                </b>
                <h5>
                  <small>{{rec.title}}</small>
                </h5>
                <em>
                  <small>{{rec.author}}</small>
                </em>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import KnobControl from "vue-knob-control";
import axios from "axios";
import VueSimpleSuggest from "vue-simple-suggest";
import "vue-simple-suggest/dist/styles.css"; // Using a css-loader

export default {
  name: "Tuner",
  components: {
    KnobControl,
    VueSimpleSuggest
  },
  methods: {
    getSuggestions: async function(query) {
      var result = [];
      const response = await axios.get(
        "http://tuner/proxy/tuner.php?q=" +
          query +
          "&k0=" +
          this.k0 +
          "&k1=" +
          this.k1 +
          "&k2=" +
          this.k2 +
          "&f=" +
          this.solrFormula
      );
      response.data.book.forEach(function(book) {
        result.push(book.label);
      });
      this.recommended = response.data.recommended;
      this.debug = response.data.debug;
      return result;
    },
    getRecommendations: async function() {
      const response = await axios.get(
        "http://tuner/proxy/tuner.php?q=" +
          this.chosen +
          "&k0=" +
          this.k0 +
          "&k1=" +
          this.k1 +
          "&k2=" +
          this.k2 +
          "&f=" +
          this.solrFormula
      );
      this.recommended = response.data.recommended;
      this.debug = response.data.debug;
    },
    clickHandler: function() {
      return false;
    }
  },
  watch: {
    k0: function() {
      this.getRecommendations();
    },
    k1: function() {
      this.getRecommendations();
    },
    k2: function() {
      this.getRecommendations();
    }
  },
  data: function() {
    return {
      chosen: "",
      recommended: "",
      debug: "",
      k0: 10,
      k1: 60,
      k2: 20
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

#main {
  padding: 5px;
  width: 85%;
  text-align: left;
  display: inline-block;
}
.knobs-group {
  padding: 5px;
}
#spacer {
  display: inline-block;
  height: 300px;
}
.pane {
  float: left;
}
.img-wrap {
  width: 90px;
  height: 120px;
  padding: 10px;
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  text-align: center;
}
.text-wrap {
  border: 1px;
  width: 90px;
  height: 180px;
  padding: 10px;
  flex: 1;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.envelope {
  max-width: 100%;
}

.debug {
  padding-top: 20px;
}
* {
  box-sizing: border-box;
}
</style>

<style module>
.container {
  position: fixed;
  left: 0;
  top: 0;
  height: 100vh;
  width: 100vw;
  background-color: var(--primary-color);
}
</style>