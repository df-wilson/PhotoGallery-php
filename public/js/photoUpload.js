
const STATUS_INITIAL = 0, STATUS_SAVING = 1, STATUS_SUCCESS = 2, STATUS_FAILED = 3;
const BASE_URL = 'http://localhost:8000';

Vue.component('photo-upload-form', {
    template:
    `
      <div id="upload">
        <div class="row">
          <div class="col-12">
            <!--UPLOAD-->
            <form enctype="multipart/form-data" novalidate v-if="isInitial || isSaving">
              <div class="dropbox">
                <input type="file" multiple :name="uploadFieldName" :disabled="isSaving" @change="filesChange($event.target.name, $event.target.files); fileCount = $event.target.files.length" accept="image/*" class="input-file">
                  <p v-if="isInitial">
                    Drag your file(s) here to begin<br> or click to browse
                  </p>
                  <p v-if="isSaving">
                    Uploading {{ fileCount }} files...
                  </p>
              </div>
            </form>
            
            <!--SUCCESS-->
            <div v-if="isSuccess">
              <h2>Uploaded {{ uploadedFiles.length }} file(s) successfully.</h2>
               <p>
                  <a href="javascript:void(0)" @click="reset()">Upload Again</a>
                  <a href="/" @click="reset()" style="margin-left: 1em">All Photos</a>
               </p>
               <ul class="list-unstyled">
                 <li v-for="item in uploadedFiles">
                   <img :src="item.url" class="img-responsive img-thumbnail" :alt="item.originalName">
                 </li>
               </ul>
            </div>
            
            <!--FAILED-->
            <div v-if="isFailed">
              <h2>Uploaded failed.</h2>
              <p>
                <a href="javascript:void(0)" @click="reset()">Try again</a>
              </p>
              <pre>{{ uploadError }}</pre>
            </div>          
          </div>        
        </div>      
      </div>
    `,
    data()
    {
        return {
            uploadedFiles: [],
            uploadError: null,
            currentStatus: null,
            uploadFieldName: 'photos[]'
        }
    },
    computed:
    {
        isInitial() {
            return this.currentStatus === STATUS_INITIAL;
        },
        isSaving() {
            return this.currentStatus === STATUS_SAVING;
        },
        isSuccess() {
            return this.currentStatus === STATUS_SUCCESS;
        },
        isFailed() {
            return this.currentStatus === STATUS_FAILED;
        }
    },
    methods:
    {
        reset()
        {
            this.currentStatus = STATUS_INITIAL;
            this.uploadedFiles = [];
            this.uploadError = null;
        },

        save(formData)
        {
            this.currentStatus = STATUS_SAVING;

            this.upload(formData)
                .then(x => {
                    this.uploadedFiles = [].concat(x);
                    this.currentStatus = STATUS_SUCCESS;
                })
                .catch(err => {
                    this.uploadError = err.response;
                    this.currentStatus = STATUS_FAILED;
                    console.log("Error: " + this.uploadError);
                    console.log("Status: " + this.currentStatus);
                });
        },

        upload(formData)
        {
            const url = `${BASE_URL}/api/photos/upload`;
            return axios.post(url, formData)
                .catch(function(error) {
                    console.log("PhotoUpload::upload. Error: " + error);
                })

                .then(x => x.data)

                .then(x => x.map(img => Object.assign({},
                    img, { url: `${BASE_URL}/images/${img.id}` })));
        },

        filesChange(fieldName, fileList)
        {
            const formData = new FormData();

            if (!fileList.length) return;

            Array
                .from(Array(fileList.length).keys())
                .map(x => {
                    formData.append(fieldName, fileList[x], fileList[x].name);
                });
            
            this.save(formData);
        }
    },
    mounted() {
        this.reset();
    }
});
