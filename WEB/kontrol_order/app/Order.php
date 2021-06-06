<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'id_user', 'number_document', 'card_print_executor', 'id_counterpartie', 'id_type_document', 'punkt_order', 'number_order',
		'date_order', 'id_period_kontrol', 'short_maintenance', 'maintenance_order', 'date_maturity',
		'id_executor', 'second_executor', 'events', 'date_complete_executor', 'archive'
    ];
}
