<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
	use SoftDeletes;
	
	protected $fillable = ['id_document_contract','id_new_application_contract','id_counterpartie_contract','number_pp','number_contract','name_work_contract', 'item_contract', 'is_sip_contract',
							'id_goz_contract','id_view_work_contract','all_count_contract','concluded_count_contract',
							'amount_concluded_contract','formalization_count_contract','amount_formalization_contract','big_deal_contract','amoun_implementation_contract',
							'comment_implementation_contract','prepayment_score_contract','invoice_score_contract','prepayment_payment_contract','amount_payment_contract',
							'resolution_contract','renouncement_contract','date_renouncement_contract','document_success_renouncement_reestr','number_aftair_renouncement_reestr',
							'archive_contract','reconciliation_contract','date_contact','year_contract'];

    public function viewWork()
	{
		return $this->hasMany('App\ViewWork');
	}
	
	//Изменения модели контракта -> вынесено в журнал, как статчный метод
	/*public function getMyChanges($all_dirty = [])
	{
		$dirty = $this->getDirty();
		foreach($dirty as $key=>$value){
			$contract_pr = $this->getOriginal($key);
			$all_dirty += [$key=>$contract_pr . '->' . $value];
		}
		return $all_dirty;
	}*/
}
