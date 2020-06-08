Vue.component('photo-single', {
    template: `
        <div>
        <h1>This is a single photo</h1>
        <p>path {{alt}}</p>
        <img v-bind:src="path" v-bind:alt="alt">        
        </div>
    `,
    props:['path', 'alt'],
    mounted() {
        console.log("Photo Single mounted. Path is " + this.path + " alt " + this.alt)

    }
});

Vue.component('photo-home', {
    template: `
      <div>
        <h1 class="text-center">Photo App</h1>
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
                <label id="started">Name</label> <a v-bind:href="'/photos/' + photo.id">{{ photo.name }}</a>
            </div>
            <div class="panel-body">
                <div>
                    <div class="thumbnail img-preview">
                        <a v-bind:href="'/photos/' + photo.id">
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
            <!--<photo-single v-bind:path="photo.filepath" v-bind:alt="photo.description"></photo-single>-->
          </div>
      </div>
      `,
    props: ['keywordid', 'text', 'publicphotos', 'privatephotos', 'fromdate', 'todate'],
    data() {
        return {
            photos: [],
            keywords: [],
            selectedKeywordId: "0",
            pageCount: 1,
            endpoint: '/api/photos'
        };
    },

    created() {
        this.fetch();
        this.fetchKeywords();
        console.log("PhotoHome::created. Keyword Id is: " + this.keywordid + " text is: " + this.text + " public photos is: "+ this.publicphotos + " private photos is: " + this.privatephotos + " From Date is : " + this.fromdate);
    },

    mounted() {
        console.log("Photo Home mounted.")
    },

    filters: {
        truncate(value) {
            return value.substring(0, 70) + " ...";
        }
    },

    methods: {
        fetch() {
            if(this.keywordid || this.text) {
                console.log("Search request.");
                axios.get('/api/photos/search', {
                    params: {
                        keyword_id: this.keywordid,
                        text: this.text,
                        public_checkbox: this.publicphotos,
                        private_checkbox: this.privatephotos,
                        from_date: this.fromdate,
                        to_date: this.todate
                    }
                })
                    .then(({data}) => {
                        this.photos = data.photos;
                        console.log("fetch. Retrieving search photos. " + JSON.stringify(data));
                        //this.pageCount = data.meta.last_page;
                    });
            } else {
                axios.get(this.endpoint)
                    .then(({data}) => {
                        this.photos = data;
                        console.log("Retrieving all photos. " + JSON.stringify(data));
                        //this.pageCount = data.meta.last_page;
                    });
            }
        },

        fetchKeywords() {
            axios.get('/api/keywords')
                .then(({data}) => {
                    this.keywords = data.keywords;
                    console.log("fetchKeywords. Retrieving keywords " + JSON.stringify(data.keywords));
            });
        },

        keywordSelected() {
            axios.get('/api/photos/keyword/'+this.selectedKeywordId)
                .then(({data}) => {
                    this.photos = data;
                    console.log("Retrieving keywords fetch " + JSON.stringify(data));
                });
        }
    }
});
