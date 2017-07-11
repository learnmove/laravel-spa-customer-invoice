<template>
  
  <div class="panel pane-default">
      <div class="panel-heading">
          <span class="panel-title">{{model.title}}</span>
          <div class="">
              <router-link :to="'/invoice/'+model.id+'/edit'" class=" btn btn-primary btn-sm">Edit</router-link>
              <button class="btn btn-danger btn-sm" @click="remove">Remove</button>
          </div>
          <div class=""></div>
      </div>
      <div class="panel-body">
          <div class="row">
              <div class="col-sm-4">
                  <label for="">Customer</label>
                  <p>{{model.customer.name}} </p>
                  <label for="">Discount</label>
                  <p>{{model.discount}} </p>
                  <label for="">Sub total</label>
                  <p>{{model.sub_total}} </p>
                   <label for="">Total</label>
                  <p>{{model.total}} </p>
              </div>
              <div class="col-sm-4">
                  <label for="">Email</label>
                  <p>{{model.customer.email}} </p>
                    <label for="">Date</label>
                  <p>{{model.date}} </p>
                        <label for="">Due Date</label>
                  <p>{{model.due_date}} </p>
              </div>
                 <div class="col-sm-4">
                  <label for="">Created_at</label>
                  <p>{{model.created_at}} </p>
                
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
                  <tr v-for="item in this.model.items">
                      <td>{{item.description}} </td>
                      <td>{{item.qty}} </td>
                      <td>{{item.unit_price}} </td>
                      <td>{{item.qty*item.unit_price}} </td>
                  </tr>
              </tbody>
          </table>
      </div>
  </div>
</template>
<script>
    export default{
        name:'InvoiceShow',
        data(){
            return {
                model:{},
                resource:'invoice',
                redirect:'/invoice'
            }
        },
        created(){
            this.fetchData();
        },

        methods:{
            
            fetchData(){
                let vm=this
                this.axios.get(`/${this.resource}/${this.$route.params.id}`)
                .then((response)=>{
                    this.model=response.data.model
                    console.log(response.data)
                    Vue.set(vm.$data,'model',response.data.model)
                })
                .catch((error)=>{})
            },
            remove(){
        this.axios.delete(`/${this.resource}/${this.$route.params.id}`)
        .then(({data})=>{console.log(data);this.$router.push('/invoice')})
        .catch((error)=>{console.log(error)})
            }
        }
    }
</script>
