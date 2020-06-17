<template>
   <div class="filter-bar ui basic segment grid form-template-1">
     <div class="ui form">
       <div class="inline field">
         <label class="control-label">Cerca:</label>
         <div class="row">
             <div class="col-9">
                 <input type="text" v-model="filterText" class="three wide column" @keyup.enter="doFilter">

             </div>
             <div class="col-3">
                 <button class="ui primary button btn btn-primary" @click="doFilter">Trova</button>
                 <button class="ui button btn btn-info" @click="resetFilter">Annulla</button>
             </div>
         </div>

       </div>
     </div>
   </div>
 </template>

 <script>
 export default {
   data () {
     return {
       filterText: ''
     }
   },
   methods: {
     doFilter () {
       this.$emit('filter-set', this.filterText)
     },
     resetFilter () {
       this.filterText = ''
       this.$emit('filter-reset')
     }
   }
 }
</script>
