<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice;
use App\Customer;
use App\InvoiceItem;
class InvoiceController extends Controller
{
    public function index(){
        
        return $this->rj([
            'model'=>Invoice::with('customer')->filterPaginateOrder()
        ]);
    }
    public function create(){
       return $this->rj([
           'form'=> Invoice::initialize(),
           'option'=>Customer::orderBy('name')->get()
       ]);


    }
    public function store(Request $rq){
        $this->validate($rq,[
            'customer_id'=>'required|exists:customers,id',
            'title'=>'required',
            'date'=>'required|date_format:Y-m-d',
            'due_date'=>'required|date_format:Y-m-d',
            'discount'=>'required|numeric|min:0',
            'items'=>'required|array|min:1',
            'items.*.description'=>'required|max:255',
            'items.*.qty'=>'required|min:1',
            'items.*.unit_price'=>'required|numeric|min:0',
            
        ]);
        $data=$rq->except('items');
        $data['sub_total']=0;
        $items=[];
        foreach($rq->items as $item){
            $data['sub_total']+=$item['unit_price']*$item['qty'];
            $items[]=new InvoiceItem($item);
        }
        $data['total']=$data['sub_total']-$data['discount'];
        $invoice=Invoice::create($data);
        $invoice->items()->saveMany($items);
        return $this->rj([
            'save'=>true
        ]);
    }
    public function show($id){
        $invoice=Invoice::with('customer','items')->findOrFail($id);
        return $this->rj([
            'model'=>$invoice
        ]);
    }
    public function edit($id){
        $invoice=Invoice::with('customer','items')->findOrFail($id);
        return $this->rj([
            'form'=>$invoice,
            'option'=>Customer::orderBy('name')->get()
        ]);
    }
    public function update(Request $rq,$id){
          $this->validate($rq,[
            'customer_id'=>'required|exists:customers,id',
            'title'=>'required',
            'date'=>'required|date_format:Y-m-d',
            'due_date'=>'required|date_format:Y-m-d',
            'discount'=>'required|numeric|min:0',
            'items'=>'required|array|min:1',
            'items.*.description'=>'required|max:255',
            'items.*.qty'=>'required|min:1',
            'items.*.unit_price'=>'required|numeric|min:0',
            
        ]);
        $invoice=Invoice::findOrFail($id);
          $data=$rq->except('items','customer');
        $data['sub_total']=0;
        $items=[];
        $itemIds=[];
        foreach($rq->items as $item){
            $data['sub_total']+=$item['unit_price']*$item['qty'];
           if(isset($item['id'])){
            InvoiceItem::whereId($item['id'])
            ->whereInvoiceId($invoice->id)
            ->update($item);
           }else{
               $items[]=new InvoiceItem($item);
           }
        }
        $data['total']=$data['sub_total']-$data['discount'];
        $invoice->update($data);
        if(count($itemIds)){
            InvoiceItem::whereInvoiceId($invoice->id)
            ->whereNotIn('id',$itemIds)
            ->delete();
        }
        if(count($items)){
            $invoice->items()->saveMany($items);
        }
        return $this->rj(['update']);
    }
    public function destroy($id){
        $invoice=Invoice::find($id);
        InvoiceItem::whereInvoiceId($invoice->id)
        ->delete();
        $invoice->delete();
        return $this->rj(['delete']);
    }
}
