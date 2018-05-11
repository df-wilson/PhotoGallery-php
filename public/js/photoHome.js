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
          <div class="panel panel-default col-xs-8 col-sm-6 col-md-4 col-lg-3" v-for="photo in photos">
            <div class="panel-heading">
                <label id="started">Name</label> <a v-bind:href="'/photos/' + photo.id">{{ photo.name }}</a>
            </div>
            <div class="panel-body">
                <div>
                    <div class="thumbnail img-preview">
                        <img :src="photo.filepath" :alt="photo.description" width="200px" height="150px">
                    </div>
                </div>
            </div>
            <div class="panel-footer">
            <p><b>Description</b><br>
            {{ photo.description }}
            </p>
            </div>
            <!--<photo-single v-bind:path="photo.filepath" v-bind:alt="photo.description"></photo-single>-->
          </div>
      </div>
      `,
    data() {
        return {
            photos: [],
            pageCount: 1,
            endpoint: '/api/photos'
        };
    },
    created() {
        this.fetch();
    },
    mounted() {
        console.log("Photo Home mounted.")
    },
    methods: {
        fetch() {
            axios.get(this.endpoint)
                .then(({data}) => {
                    this.photos = data;
                    console.log("Retrieving photos fetch " + JSON.stringify(data));
                    //this.pageCount = data.meta.last_page;
                });
        }
    }
});
