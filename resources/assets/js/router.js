import Vue from 'vue';
import VueRouter from 'vue-router'
import Index from './components/Index.vue'
import Customer_Index from './components/views/customer/index.vue'
import Customer_Show from './components/views/customer/show.vue'
import Customer_Form from './components/views/customer/form.vue'
import Invoice_Index from './components/views/invoice/index.vue'
import Invoice_Show from './components/views/invoice/show.vue'
import Invoice_Form from './components/views/invoice/form.vue'

Vue.use(VueRouter)
const router=new VueRouter({
    routes:[
        {path:'/',component:Index},
        {path:'/customer',component:Customer_Index},
        {path:'/customer/create',component:Customer_Form},
        {path:'/customer/:id',component:Customer_Show},
        {path:'/customer/:id/edit',component:Customer_Form,meta:{mode:'edit'}},
        {path:'/invoice',component:Invoice_Index},
        {path:'/invoice/create',component:Invoice_Form},
        {path:'/invoice/:id',component:Invoice_Show},
        {path:'/invoice/:id/edit',component:Invoice_Form,meta:{mode:'edit'}},
        
        
        

        
        
    ]
});
export default router