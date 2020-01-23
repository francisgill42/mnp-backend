<?php

namespace App\Exports;

use App\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Order::all();
    }
    public function headings(): array
    {
        return [
            'id',
            'customer_id',
            'order_total', 
            'order_tax',
            'order_gross',
            'created_at',	
            'updated_at',
            'order_status_id',
            'order_confirmed_date',	
            'order_shipped_date',
            'order_delivered_date',
            'discounted_price',
            'delivery_date',
            'payment_due_date'
        ];
}
}
