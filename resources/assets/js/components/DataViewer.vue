<template>
  
  <div class="panel panel-default">
      <div class="panel-heading">
          <span class="panel-title">{{title}}</span>
           <div class="">
          <router-link :to="create" class="btn btn-primary btn-sm" >Create</router-link>
          <button class="btn btn-default btn-sm" @click="showFilter=!showFilter">F</button>
      </div>
      </div>
     <div class="panel-body">
         <div class="filter" v-if="showFilter">
             <div class="filter-column">
                 <select name="" id="" class="form-control" v-model="params.search_column">

                        <option v-for="column in filter" :value="column">{{column}} </option>
                 </select>
             </div>
             <div class="filter-operator">
                 <select name="" id="" class="form-control" v-model="params.search_operator">
                     <option v-for="(value,key) in operators" :value="key">{{value}} </option>
                 </select>
             </div>
             <div class="filter-input">
                 <input @keyup.enter="fetchData" type="text" name="" id="" class="form-control" v-model="params.search_query_1">
             </div> 
               <div class="filter-input" v-if="params.search_operator=='between'">
                 <input @keyup.enter="fetchData" type="text" name="" id="" class="form-control" v-model="params.search_query_1">
             </div>
             <div class="filter-btn">
                 <button class="btn btn-primary btn-block btn-sm" @click="fetchData">filter</button>
             </div>
         </div>
         <table class="table table-striped">
             <thead>
                 <th v-for="item in thead">
                     
                     <div class="dataviewer-th" @click="sort(item.key)" v-if="item.sort">
                         <span>{{item.title}} </span>
                         <span v-if="params.column===item.key">
                             <span v-if="params.direction==='desc'">&#x25B2;</span>
                             <span v-else>&#x25BC;</span>
                         </span>

                     </div>
                     <div v-else>
                         <span>{{item.title}}</span>
                     </div>
                 </th>
             </thead>
             <tbody>
                 <slot v-for="item in model.data" :item="item"></slot>
             </tbody>

         </table>
     </div>
     <div class="panel-footer pagination-footer">
        <div class="pagination-item">
            <span>per page:</span>
            <select v-model="params.per_page" @change="fetchData" >
 <option >10</option>
            <option >25</option>
            <option >30</option>

            </select>
           
        </div>
        <div class="pagination-item">
            <small>Show {{model.from}} -{{model.to}}of {{model.total}} </small>
        </div>
        <div class="pagination-item">
            <small>current page</small>
            <input type="text" @keyup.enter="fetchData" name="" id="" class="pagination-input" v-model="params.page">
            <small>
                of {{model.last_page}}
            </small>
        </div>
        <div class="pagination-item">
            <button class="btn btn-primary btn-sm" @click="prev">Prev</button>
            <button class="btn btn-primary btn-sm"  @click="next">Next</button>
        </div>
     </div>
  </div>
</template>
<script>
    import Vue from 'vue'
export default {
  props:['source','thead','thead','create','filter','title'],
  data(){
      return{
          showFilter:false,
          model:{
              data:[]
          },
          params:{
              column:'id',
              direction:'desc',
              per_page:'10',
              page:1,
              search_column:'id',
              search_operator:'equal_to',
              search_query_1:'',
              search_query_2:''
              
          },
          operators:{
              equal_to:'=',
        not_equal:'<>',
        less_than:'<',
        greater_than:'>',
        less_than_or_equal_to:'<=',
        greater_than_or_equal_to:'<=',
        in:'IN',
        like:'LIKE',
        not_in:'NOT_IN',
        between:'BETWEEN'
          }
      }
  },
  beforeMount(){
      this.fetchData()
  },
  methods:{
      next(){
          if(this.model.next_page_url){
              this.params.page++
              this.fetchData()
          }
      },
        prev(){
          if(this.model.prev_page_url){
              this.params.page--
              this.fetchData()
          }
      },
      sort(column){
          if(column==this.params.column){
          
            if(this.params.direction=='desc'){
                this.params.direction='asc'
                
            }else if(this.params.direction=='asc'){
                 this.params.direction='desc'
                 
            }
          }else{
               this.params.column=column 
                this.params.direction='asc'
     
          }
       
         
            this.fetchData()


      },
      fetchData(){
          var vm=this
          Vue.axios.get(this.buildUrl())
          .then(function(response){
              Vue.set(vm.$data,'model',response.data.model)
          })
          .catch(function(error){
              console.log(error)
          })
      },
      buildUrl(){
          var p=this.params
          return `${this.source}?column=${p.column}&direction=${p.direction}&per_page=${p.per_page}&page=${p.page}&search_column=${p.search_column}&search_operator=${p.search_operator}&search_query_1=${p.search_query_1}&search_query_2=${p.search_query_2}`
      }
  }
  
}
</script>

