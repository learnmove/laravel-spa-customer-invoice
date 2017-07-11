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
                  <label for="">Customer</label>
                  <select v-model="form.customer_id">
                      <option v-for="opt in option" :value="opt.id">{{opt.name}} </option>
                  </select>
                  <small class="text-danger" v-if="errors.name">{{errors.name[0]}} </small>
               
                 </div>
                </div>
               <div class="from-group">
                    <div class="col-sm-4">
                  <label for="">Due Date</label>
                  <input type="text" class="form-control" v-model="form.due_date">
                  <small class="text-danger" v-if="errors.due_date">{{errors.due_date[0]}} </small>
               
                 </div>
                </div>
                 <div class="from-group">
                    <div class="col-sm-4">
                  <label for="">Title</label>
                  <input type="text" class="form-control" v-model="form.title">
                  <small class="text-danger" v-if="errors.title">{{errors.title[0]}} </small>
               
                 </div>
                </div>
               
                <div class="form-group">
                    <div class="col-sm-4">
                <button class="btn btn-primary">save</button>

                        </div>                    
                </div>
            </div>
             <table class="table table-striped">
              <thead>
                  <th>Description</th>
                  <th>Qty</th>
                  <th>Price</th>
                  <th>Total</th>
              </thead>
              <tbody>
                  <tr v-for="(item,index) in form.items">
                    <td><input type="text" v-model="item.description"></td>
                    <td><input type="text" v-model="item.qty"></td>
                    <td><input type="text" v-model="item.unit_price"></td>
                    <td>{{item.qty*item.unit_price}} </td>
                    <td><button @click="form.items.splice(index,1)" >&times;</button> </td>
                  </tr>
              </tbody>
              <tfoot>
                <tr>
                      <td>
                      <a @click.stop="addLine">Add Line</a>
                      <td>Sub total</td>
                      <td> {{subTotal}} </td>
                  </td>
                </tr>
                <tr>
                   
                    <td>
                        Discount
                    </td>
                    <td>
                       {{form.discount}}
                    </td>
                </tr>
                   <tr>
                   
                    <td>
                        Total
                    </td>
                    <td>
                       {{total}}
                    </td>
                </tr>
              </tfoot>
          </table>
            
      </form>
  </div>
  </div>
  
</template>
<script>
    export default{
        name:'CustomerForm',
        data(){
            return{
                title:'Create',
                initialize:'/invoice/create',
                redirect:'/',
                store:'/invoice',
                method:'post',
                errors:{},
                option:'',
                form:{}
            }
        },
        computed:{
            subTotal(){
                return this.form.items.reduce((carry,item)=>{
                    return carry + parseFloat(item.qty)*parseFloat(item.unit_price)
                },0)
            },
            total(){
                return this.subTotal-this.form.discount
            }
        },
        beforeMount(){
            if(this.$route.meta.mode==='edit'){
                 this.title='Edit'
                this.initialize='/invoice/'+this.$route.params.id+'/edit'
                this.store='/invoice/'+this.$route.params.id
                this.method='put'
            }
           this.fetchData()
        },
        watch:{
             '$route':'fetchData'
        },
        methods:{
            addLine(){
                this.form.items.push({
                    description:'',
                    unit_price:0,
                    qty:1
                })
            }
            ,
            fetchData(){
                this.axios.get(this.initialize)
                .then(({data})=>{this.form=data.form;this.option=data.option})
                .catch((error)=>{console.log(error)})
            },
            save(){
                this.axios[this.method](this.store,this.form)
                .then(({data})=>{console.log(data);this.$router.push('/customer')})
                .catch((error)=>{console.log(error.response)})
            }
        }

    }
</script>