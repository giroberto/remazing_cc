<template>
  <div class="card w-100">
    <div class="card-header">{{headerText}}</div>
    <div class="card-body">
      <bar-chart :chartdata="chartdata" :options="options" />
    </div>
  </div>
</template>

<script>
import BarChart from "./Chart.js";

export default {
  name: "ChartComponent",
  components: { BarChart },
  props: {
    kpi: String,
    headerText: String,
    chartdata: {
      type: Object
    }
  },
  computed: {
    myStyles() {
      return {
        "max-height": `80px`,
        position: "relative"
      };
    }
  },
  data: () => ({
    options: {
      legend: {
        display: false
      },
      height: 300
    }
  })
  //   mounted() {
  //     let t0 = performance.now();
  //     this.loaded = false;
  //     // try {
  //     //   axios.get(`/${this.kpi}`).then(response => {
  //     //     this.chartdata = response.data;
  //     //     this.timeToComplete = Math.round(performance.now() - t0);
  //     //     this.loaded = true;
  //     //   });
  //     // } catch (e) {
  //     //   console.error(e);
  //     // }
  //   }
};
</script>
