var Pagination = {
   template: `
      <div>
         <ul class="pagination">
            <li class="pagination-item">
               <button type="button" class="btn"
                       v-on:click="onClickFirstPage" 
                       v-bind:disabled="isInFirstPage">
                  First
               </button>
            </li>
            <li class="pagination-item">
               <button type="button" class="btn"
                       v-on:click="onClickPreviousPage" 
                       v-bind:disabled="isInFirstPage">
                  &lt;
               </button>
            </li>
            
            <!-- Range of pages -->
            <li v-for="page in pages" :key=page.name class="pagination-item">
               <button type="button" class="btn"
                       v-on:click="onClickPage(page.name)" 
                       v-bind:disabled="page.isDisabled"
                       :class="{active: isPageActive(page.name)}">
                  {{page.name}}
               </button>
            </li>
   
            <li class="pagination-item">
               <button type="button" class=btn 
                       v-on:click="onClickNextPage" 
                       v-bind:disabled="isInLastPage">
                  &gt;
               </button>
            </li>
            <li class="pagination-item">
               <button type="button" class=btn 
                       v-on:click="onClickLastPage" 
                       v-bind:disabled="isInLastPage">
                       Last
               </button>
            </li>
         </ul>
      </div>
   `,
   props: {
      maxVisibleButtons: {
         type: Number,
         required: false,
         default: 3
      },
      perPage: {
         type: Number,
         required: true
      },
      totalItems: {
         type: Number,
         required: true
      },
      
      currentPage: {
         type: Number,
         required: true
      }
   },
   computed: {
      visibleButtons()
      {
         if(this.totalPages < this.maxVisibleButtons) {
            return this.totalPages;
         } else {
            return this.maxVisibleButtons;
         }
      },
      totalPages()
      {
         return Math.ceil(this.totalItems/this.perPage)
      },
      
      startPage() 
      {
         let startPage = 1;
         
         if(this.currentPage === 1) {
            startPage = 1;
         } else if (this.currentPage === this.totalPages) {
            startPage = this.totalPages - this.maxVisibleButtons + 1;
         } else {
            startPage = this.currentPage - 1;
         }
         
         if(startPage < 1) {
            startPage = 1;
         }
         
         return startPage;
      },

      pages() 
      {
         const range = [];
         
         for(let i = this.startPage; 
                 i<=(this.startPage + this.visibleButtons - 1);
                 ++i)
         {
            range.push({
               name: i,
               isDisabled: i === this.currentPage
            });
         }
         
         return range;
      },

      isInFirstPage() 
      {
         return this.currentPage === 1;
      },

      isInLastPage() 
      {
         return this.currentPage === this.totalPages;
      }
   },
   
   methods: {
      isPageActive(page)
      {
         return this.currentPage === page;
      },

      onClickFirstPage() 
      {
         this.$emit('pagechanged', 1);
      },

      onClickPreviousPage() 
      {
         this.$emit('pagechanged', this.currentPage - 1);
      },

      onClickPage(page) 
      {
         this.$emit('pagechanged', page);
      },

      onClickNextPage() 
      {
         this.$emit('pagechanged', this.currentPage + 1);
      },

      onClickLastPage() 
      {
         this.$emit('pagechanged', this.totalPages);
      }
   }
};
