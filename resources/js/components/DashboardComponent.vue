<template>
  <div class="container">
    <form>
      <div class="row">
        <div class="col-md">
          <div class="form-group d-flex">
            <label for="MinPrice" class="text-nowrap pr-3">Min Price</label>
            <input
              type="number"
              step="0.01"
              class="form-control align-content-end"
              id="MinPrice"
              v-model="minprice"
            />
          </div>
        </div>
        <div class="col-md">
          <div class="form-group d-flex">
            <label for="MaxPrice" class="text-nowrap pr-3">Max Price</label>
            <input type="number" step="0.01" class="form-control" id="MaxPrice" v-model="maxprice" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md">
          <div class="form-group d-flex">
            <label class="text-nowrap pr-3" for="MinReviews">Min Reviews</label>
            <input type="number" class="form-control" id="MinReviews" v-model="minreviews" />
          </div>
        </div>
        <div class="col-md">
          <div class="form-group d-flex">
            <label class="text-nowrap pr-3" for="MaxReviews">Max Reviews</label>
            <input type="number" class="form-control" id="MaxReviews" v-model="maxreviews" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md">
          <div class="form-group d-flex">
            <label class="text-nowrap pr-3" for="MinRating">Min Rating</label>
            <input type="number" step="0.1" class="form-control" id="MinRating" v-model="minrating" />
          </div>
        </div>
        <div class="col-md">
          <div class="form-group d-flex">
            <label class="text-nowrap pr-3" for="MaxRating">Max Rating</label>
            <input type="number" step="0.1" class="form-control" id="MaxRating" v-model="maxrating" />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md">
          <div class="form-group d-flex">
            <label class="text-nowrap pr-3" for="MinDate">Min Date</label>
            <input type="date" class="form-control" id="MinDate" min="maxdate" v-model="mindate" />
          </div>
        </div>
        <div class="col-md">
          <div class="form-group d-flex">
            <label class="text-nowrap pr-3" for="MaxDate">Max Date</label>
            <input type="date" class="form-control" id="MaxDate" max="mindate" v-model="maxdate" />
          </div>
        </div>
      </div>
      <div class="row justify-content-center">
        <button type="button" class="btn btn-primary" v-on:click="retrieveStats">Submit</button>
      </div>
    </form>

    <div v-if="!loaded" class="row justify-content-center mt-5">
      <strong class="mr-2 h5">Loading...</strong>
      <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>
    <div v-if="loaded">
      <div class="row justify-content-end">
        <span class="text-danger">Response time {{timeToComplete}} ms</span>
      </div>
      <div class="row justify-content-center mb-4">
        <div class="col-md-4">
          <chart-component headerText="Price range" :chartdata="chartdata.price"></chart-component>
        </div>
        <div class="col-md-4">
          <chart-component headerText="Number of reviews" :chartdata="chartdata.review"></chart-component>
        </div>
        <div class="col-md-4">
          <chart-component headerText="Rating" :chartdata="chartdata.star"></chart-component>
        </div>
      </div>
      <div class="row justify-content-center">
        <chart-component
          class="col-md-10"
          headerText="Date First Listed"
          :chartdata="chartdata.date"
        ></chart-component>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "DashboardComponent",
  data: () => ({
    loaded: false,
    chartdata: [],
    minprice: null,
    maxprice: null,
    minreviews: null,
    maxreviews: null,
    minrating: null,
    maxrating: null,
    mindate: null,
    maxdate: null
  }),
  methods: {
    retrieveStats() {
      this.loaded = false;
      let t0 = performance.now();
      try {
        axios
          .get(`/stats`, {
            params: {
              minprice: this.minprice,
              maxprice: this.maxprice,
              minreviews: this.minreviews,
              maxreviews: this.maxreviews,
              minrating: this.minrating,
              maxrating: this.maxrating,
              mindate: this.mindate,
              maxdate: this.maxdate
            }
          })
          .then(response => {
            this.chartdata = response.data;
            this.timeToComplete = Math.round(performance.now() - t0);
            this.loaded = true;
          });
      } catch (e) {
        console.error(e);
      }
    }
  },
  mounted() {
    // const stats = ["price", "review", "star", "date"];
    this.retrieveStats();
  }
};
</script>
