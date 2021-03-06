Vue.component('photo-home', {
    template: `
      <div>
         <div class="row">
            <div class="col-12">
               <h1 class="text-center mb-4">Photo Gallery</h1>
               <div class="keyword-search">
                   Keyword Search:
                   <select name="keyword-select" v-model="selectedKeywordId" class="mb-3">
                       <option disabled value="">Keywords</option>
                       <option v-bind:value="0" v-on:click="keywordSelected">all</option>
                       <option v-for="keyword in keywords" v-bind:value="keyword.id" v-on:click="keywordSelected">
                          {{keyword.name}}
                       </option>
                   </select>
                   <span class="ml-3" style="white-space:nowrap">
                     <button class="btn btn-sm btn-success mr-2" v-on:click="showUploadPage">
                        Add Photos
                     </button>

                     <button class="btn btn-sm btn-success" v-on:click="toggleDeleteMode">
                        <span v-if="isDeleteMode">View Mode</span>
                        <span v-else="isDeleteMode">Delete Mode</span>
                     </button>
                   </span>
                </div>            
            </div>
         </div>
        
        <div class="row">
          <div class="col-8 col-md-6 col-lg-4 col-xl-3" v-for="photo in displayPhotos">
            <div class="card mb-4">
              <div class="card-header">
                <div class="card-title">
                  <label id="started">Name</label> <a v-bind:href="'/photos/' + photo.id">{{ photo.name }}</a>
                </div>                
              </div>
              <div>
                <div class="card-img">
                  <div class="img-preview mx-auto">
                    <a v-bind:href="'/photos/' + photo.id">
                      <img :src="photo.thumbnail_filepath" :alt="photo.name" width="200px" height="150px">
                    </a>
                  </div>
                </div>
              </div>
              <div class="card-footer">
                 <p v-if="isDeleteMode" class="mt-3">
                    <button class="btn btn-danger" v-on:click="deleteImage(photo.id)">Delete</button>
                 </p>

                 <p v-else style="height: 50px">
                    <b>Description</b><br>
                    {{ photo.description | truncate}}
                 </p>
              </div> 
            </div>        
          </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4 col-xl-2">
                <pagination
                    :total-items="photos.length"
                    :per-page="perPage"
                    :current-page="currentPage"
                    @pagechanged="onPageChange"
                >
                </pagination>
            </div>
        </div>
      </div>
    `,
    components: {
        "pagination": Pagination
    },
    props: ['keywordid', 'text', 'publicphotos', 'privatephotos', 'fromdate', 'todate'],
    data() {
        return {
            displayPhotos: [],
            photos: [],
            keywords: [],
            selectedKeywordId: "0",
            pageCount: 1,
            endpoint: '/api/photos',
            isDeleteMode: false,
            perPage: 25,
            currentPage: 1
        };
    },

    created() {
        this.fetch();
        this.fetchKeywords();
    },

    filters: {
        truncate(value) {
            if(value.length > 70) {
                return value.substring(0, 66) + " ...";
            } else {
                return value;
            }
        }
    },

    methods: {
        fetch()
        {
            if(this.keywordid || this.text || this.fromdate || this.todate || this.privatephotos || this.publicphotos) {

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
                        this.updateDisplayPhotos(0, this.perPage);
                    });
            } else {
                axios.get(this.endpoint)
                    .then(({data}) => {
                        this.photos = data;
                        this.updateDisplayPhotos(0, this.perPage);
                    });
            }
        },

        fetchKeywords()
        {
            axios.get('/api/keywords')
                .then(({data}) => {
                    this.keywords = data.keywords;
            });
        },

        keywordSelected() 
        {
            axios.get('/api/photos/keyword/'+this.selectedKeywordId)
                .then(({data}) => {
                    this.photos = data;
                    this.updateDisplayPhotos(0, this.perPage);
                });
        },

        onPageChange(page)
        {
            this.currentPage = page;
            let start = (page-1)*this.perPage;
            if (start < 0) {
                start = 0;
            }

            let end = start+this.perPage;
            if(end > this.photos.length) {
                end = this.photos.length;
            }
            this.updateDisplayPhotos(start, end);
        },

        toggleDeleteMode()
        {
            this.isDeleteMode = !this.isDeleteMode;
        },

        deleteImage(photoId)
        {
            axios.delete("/api/photos/"+photoId)
               .then(response => {
                   this.photos = this.photos.filter(photo => photo.id != photoId);
               })
               .catch(error => {
                   console.log(error);
               });
        },

        showUploadPage()
        {
            window.location.href = "/photos/upload";
        },

        updateDisplayPhotos(start, end)
        {
            this.displayPhotos = this.photos.slice(start,end);
        }
    }
});
