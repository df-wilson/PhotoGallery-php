Vue.component('photo-explore', {
    template: `
      <div>
        <h1 class="text-center">Explore Photos</h1>
        <div class="keyword-search">
            Keyword Search:
            <select name="keyword-select" v-model="selectedKeywordId">
                <option disabled value="">Keywords</option>
                <option v-bind:value="0" v-on:click="keywordSelected">all</option>
                <option v-for="keyword in keywords" v-bind:value="keyword.id" v-on:click="keywordSelected">
                {{keyword.name}}
                </option>
            </select>
        </div>
          <div class="panel panel-default col-xs-8 col-sm-6 col-md-4 col-lg-3" v-for="photo in photos">
            <div class="panel-heading">
                <label id="started">Name</label> <a v-bind:href="'/photos/explore/photo/' + photo.id">{{ photo.name }}</a>
            </div>
            <div class="panel-body">
                <div>
                    <div class="thumbnail img-preview">
                        <a v-bind:href="'/photos/explore/photo/' + photo.id">
                            <img :src="photo.thumbnail_filepath" :alt="photo.description" width="200px" height="150px">
                        </a>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
            <p style="height: 50px">
               <b>Description</b><br>
               {{ photo.description | truncate}}
            </p>
            </div>
          </div>
      </div>
      `,
    data() {
        return {
            photos: [],
            keywords: [],
            selectedKeywordId: "0",
            pageCount: 1,
            endpoint: '/api/photos/explore'
        };
    },
    created() {
        this.fetch();
        this.fetchKeywords();
    },
    mounted() {
        console.log("Photo Explore mounted.")
    },
    filters: {
        truncate(value) {
            return value.substring(0, 70) + " ...";
        }
    },
    methods: {
        fetch() {
            axios.get(this.endpoint)
                .then(({data}) => {
                    this.photos = data;
                    console.log("Retrieving explore photos fetch " + JSON.stringify(data));
                    //this.pageCount = data.meta.last_page;
                });
        },
        fetchKeywords() {
            axios.get('/api/keywords')
                .then(({data}) => {
                    this.keywords = data;
                    console.log("Retrieving keywords fetch " + JSON.stringify(data));
            });
        },
        keywordSelected() {
            axios.get('/api/photos/explore/keyword/'+this.selectedKeywordId)
                .then(({data}) => {
                    this.photos = data;
                    console.log("Retrieving keywords fetch " + JSON.stringify(data));
                });
        }
    }
});
