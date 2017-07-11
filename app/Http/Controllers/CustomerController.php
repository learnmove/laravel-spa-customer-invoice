<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
class CustomerController extends Controller
{
    //
    public function index(){
       return $this->rj([
            'model'=>Customer::filterPaginateOrder()
        ]);
        
    }
    public function create(){
        return response()->json([
            'form'=>Customer::initialize(),
            'option'=>[]
        ]);
    }
    public function store(Request $rq){
        $this->validate($rq,[
            'company'=>'required',
            'name'=>'required',
            'email'=>'required|email',
            'phone'=>'required',
            'address'=>'required',
        ]);
        $customer=Customer::create($rq->all());
        return $this->rj(['saved'=>true]);
    }
    public function edit($id){
        $customer=Customer::findOrFail($id);
        return $this->rj([
            'form'=>$customer,
            'option'=>[]
        ]);
    }
    public function update(Request $rq,$id){
        $this->validate($rq,[
             'company'=>'required',
            'name'=>'required',
            'email'=>'required|email',
            'phone'=>'required',
            'address'=>'required',
        ]);
        $customer=Customer::find($id);
        $customer->update($rq->all());
        return $this->rj(['save'=>true]);
    }
    public function destroy($id){
        Customer::destroy($id);
        return $this->rj(['delete'=>true]);
    }
        

}
