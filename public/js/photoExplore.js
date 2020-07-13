Vue.component('photo-explore', {
    template: `
       <div>
          <div class="row">
              <div class="col-12">
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
              </div>
          </div>
                
          <div class="row">
             <div class="col-8 col-md-6 col-lg-4 col-xl-3" v-for="photo in photos">
                <div class="card mb-4">
                   <div class="card-header">
                      <label id="started">Name</label> <a v-bind:href="'/photos/explore/photo/' + photo.id">{{ photo.name }}</a>
                   </div>
                          
                   <div class="card-img">
                      <div class="img-preview mx-auto">
                          <a v-bind:href="'/photos/explore/photo/' + photo.id">
                             <img :src="photo.thumbnail_filepath" :alt="photo.description" width="200px" height="150px">
                          </a>                     
                      </div>
                      <div class="card-footer">
                         <p style="height: 50px">
                            <b>Description</b><br>
                            {{ photo.description | truncate}}                        
                         </p>
                      </div>
                   </div>
                </div>
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
                });
        },
        fetchKeywords() {
            axios.get('/api/keywords')
                .then(({data}) => {
                    this.keywords = data.keywords;
            });
        },
        keywordSelected() {
            axios.get('/api/photos/explore/keyword/'+this.selectedKeywordId)
                .then(({data}) => {
                    this.photos = data;
                });
        }
    }
});
