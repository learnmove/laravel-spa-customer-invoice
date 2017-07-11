<template>
  
  <div class="panel pane-default">
      <div class="panel-heading">
          <span class="panel-title">{{model.company}}/{{model.name}}</span>
          <div class="">
              <router-link :to="'/customer/'+model.id+'/edit'" class=" btn btn-primary btn-sm">Edit</router-link>
              <button class="btn btn-danger btn-sm" @click="remove">Remove</button>
          </div>
          <div class=""></div>
      </div>
      <div class="panel-body">
          <div class="row">
              <div class="col-sm-4">
                  <label for="">Company</label>
                  <p>{{model.company}} </p>
                  <label for="">Name</label>
                  <p>{{model.name}} </p>
              </div>
              <div class="col-sm-4">
                  <label for="">Email</label>
                  <p>{{model.email}} </p>
                  <label for="">pHONE Number</label>
                  <p>  {{model.phone}} </p>
              </div>
              <div class="col-sm-4">
                  <label for="">Created at</label>
                  <p>{{model.created_at}} </p>
                  <label for="">Address</label>
                  <p>  {{model.address}} </p>
              </div>
          </div>
      </div>
  </div>
</template>
<script>
    export default{
        data(){
            return {
                model:{},
                resource:'customer'
            }
        },
        created(){
            this.fetchData();
        },

        methods:{
            
            fetchData(){
                let vm=this
                this.axios.get(`/${this.resource}/${this.$route.params.id}/edit`)
                .then((response)=>{
                    this.model=response.data.form
                    Vue.set(vm.$data,'model',response.data.model)
                })
                .catch((error)=>{})
            },
            remove(){
        this.axios.delete(`/${this.resource}/${this.$route.params.id}`)
        .then(({data})=>{console.log(data);this.$router.push('/customer')})
        .catch((error)=>{console.log(error)})
            }
        }
    }
</script>
