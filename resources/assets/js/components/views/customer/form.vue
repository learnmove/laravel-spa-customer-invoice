<template>
  <div class="panel panel-default">
      <div class="panel-heading">
          {{title}}
      </div>
  <div class="panel-body">
      <form @submit.prevent="save">
            <div class="row">
                <div class="from-group">
                    <div class="col-sm-4">
                  <label for="">Company</label>
                  <input type="text" class="form-control" v-model="form.company">
                  <small class="text-danger" v-if="errors.company">{{errors.company[0]}} </small>
               
                 </div>
                </div>
               <div class="from-group">
                    <div class="col-sm-4">
                  <label for="">Name</label>
                  <input type="text" class="form-control" v-model="form.name">
                  <small class="text-danger" v-if="errors.name">{{errors.name[0]}} </small>
               
                 </div>
                </div>
                 <div class="from-group">
                    <div class="col-sm-4">
                  <label for="">Phone</label>
                  <input type="text" class="form-control" v-model="form.phone">
                  <small class="text-danger" v-if="errors.phone">{{errors.phone[0]}} </small>
               
                 </div>
                </div>
                 <div class="from-group">
                    <div class="col-sm-4">
                  <label for="">Email</label>
                  <input type="text" class="form-control" v-model="form.email">
                  <small class="text-danger" v-if="errors.form.email">{{errors.email[0]}} </small>
               
                 </div>
                </div>
                 <div class="from-group">
                    <div class="col-sm-4">
                  <label for="">Address</label>
                  <input type="text" class="form-control" v-model="form.address">
                  <small class="text-danger" v-if="errors.address">{{errors.address[0]}} </small>
               
                 </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-4">
                <button class="btn btn-primary">save</button>

                        </div>                    
                </div>
            </div>
            
      </form>
  </div>
  </div>
  
</template>
<script>
    export default{
        name:'Form',
        data(){
            return{
                title:'Create',
                initialize:'/customer/create',
                redirect:'/',
                store:'/customer',
                method:'post',
                errors:{},
                option:'',
                form:{}
            }
        },
        beforeMount(){
            if(this.$route.meta.mode==='edit'){
                 this.title='Edit'
                this.initialize='/customer/'+this.$route.params.id+'/edit'
                this.store='/customer/'+this.$route.params.id
                this.method='put'
            }
           this.fetchData()
        },
        watch:{
             '$route':'fetchData'
        },
        methods:{
            fetchData(){
                this.axios.get(this.initialize)
                .then(({data})=>{this.form=data.form;this.option=data.option})
                .catch((error)=>{console.log(error)})
            },
            save(){
                this.axios[this.method](this.store,this.form)
                .then(({data})=>{console.log(data);this.$router.push('/customer')})
                .catch((error)=>{console.log(error)})
            }
        }

    }
</script>