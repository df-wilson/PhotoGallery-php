Vue.component('photo-search', {
    template: `
      <div class="row">
        <div class="col-12">
        <h1 class="text-center">Search Photos</h1>
        <form id="search-form" action="/photos/search" method="post">
        <input type="hidden" name="_token" :value="csrf">
          <div class="form-group">
            <label for="text-search">Text Search</label>
                <input type="text" id="text-search" name="text_search" class="form-control" 
                       inputmode="text" size="50" v-model="text">
          </div>
          <div class="form-group">
            <label for="keyword-id">
                Keyword Search
                <select id="keyword-id" class="form-control" name="keyword_id" v-model="keyword_id">
                  <option disabled value="">Keywords</option>
                  <option v-bind:value="0">all</option>
                  <option v-for="keyword in keywords" v-bind:value="keyword.id">
                  {{keyword.name}}
                  </option>
                  </select>            
            </label>
          </div>
          
          <div class="form-group">
             <label for="from-date">
                 Photos From
                 <input id="from-date" name="from_date" type="date" class="form-control" v-model="fromdate">
             </label>
             <label for="to-date">
                 Photos To
                 <input id="to-date" type="date" name="to_date" class="form-control" v-model="todate">
             </label>
          </div>
          
          <div class="form-check form-check-inline">
             <label class="form-check-label" for="private-checkbox">
                <input type="checkbox" 
                       id="private-checkbox" 
                       name="private_checkbox" 
                       class="form-check-input" 
                       v-model="private">
                My Photos
             </label>
          </div>
          <div class="form-check form-check-inline">
              <label for="public-checkbox">
                 <input type="checkbox" 
                        id="public-checkbox" 
                        name="public_checkbox"
                        class="form-check-input"
                        v-model="public">
                 Public Photos
              </label>
          </div>
          
          <div id="form-buttons" class="mt-3">
             <button type="button" class="btn btn-primary" v-on:click="reset()">Reset</button>
             <button type="submit" class="btn btn-primary">Submit!</button>
          </div>
        </form>
        </div>
      </div>
      `,
    data() {
        return {
            fromdate: "",
            todate: "",
            text: "",
            private: true,
            public: false,
            keyword_id: '',
            keywords: [],
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')

        };
    },
    created() {
    },
    mounted() {
        var local = new Date();
        this.todate = local.toJSON().slice(0,10);
        this.fetchKeywords();

    },
    filters: {
        truncate(value) {
            return value.substring(0, 70) + " ...";
        }
    },
    methods: {
        fetchKeywords() {
            axios.get('/api/keywords')
                .then(({data}) => {
                    this.keywords = data.keywords;
                    console.log("Retrieving keywords fetch " + JSON.stringify(data.keywords));
                });
        },
        reset()
        {
            this.text = "";
            this.private = true;
            this.public = false;
            this.keyword_id = "";
            this.fromdate = "";
            
            let local = new Date();
            this.todate = local.toJSON().slice(0,10);
        }
    }
});
