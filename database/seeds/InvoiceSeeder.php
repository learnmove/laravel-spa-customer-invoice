<?php

use Illuminate\Database\Seeder;
use App\Invoice;
use Faker\Factory;
use App\InvoiceItem;
class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker=Factory::create();
        $discount=$faker->numberBetween(0,100);
        $sub_total=$faker->numberBetween(500,1000);
        Invoice::truncate();
        InvoiceItem::truncate();
        foreach(range(1,25) as $i){
          $invoice= Invoice::create([
            'customer_id'=>$i,
            'title'=>$faker->sentence,
            'date'=>'2017-'.mt_rand(1,12).'-'.mt_rand(1,28),
            'due_date'=>'2017-'.mt_rand(1,12).'-'.mt_rand(1,28),
            'discount'=>$discount,
            'sub_total'=>$sub_total,
            'total'=>$sub_total-$discount
           ]);
                foreach(range(1,6) as $i){
                 InvoiceItem::create([
                'invoice_id'=>$invoice->id,
                'description'=>$faker->sentence,
                'qty'=>$faker->numberBetween(1,7),
                'unit_price'=>$faker->numberBetween(100,400)
            ]);
        }
   
        }
    }
}
