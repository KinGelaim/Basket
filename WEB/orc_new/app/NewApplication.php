<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewApplication extends Model
{
    protected $fillable = ['id_contract_new_application', 'is_contract_new_application', 'is_rkm_new_application', 'number_pp_new_application', 'date_registration_new_application',
							'id_counterpartie_new_application', 'number_outgoing_new_application', 'number_incoming_new_application',
							'result_outgoing_new_application', 'executor_new_application', 'item_new_application', 'name_work_new_application',
							'term_maturity_new_application', 'telephone_new_application', 'on_dk_new_application', 'call_price_new_application',
							'result_vp_new_application', 'rkm_new_application', 'other_new_application',
							'isp_new_application', 'goz_new_application', 'interfactory_new_application',
							'sb_new_application', 'export_new_application', 'view_other_new_application',
							'storage_new_application',
							//'executor_second_new_application', 'result_second_new_application', 'date_second_new_application', 'fio_second_new_application',
							//'executor_ten_new_application', 'result_ten_new_application', 'date_ten_new_application', 'fio_ten_new_application',
							//'executor_ogt_new_application', 'result_ogt_new_application', 'date_ogt_new_application', 'fio_ogt_new_application',
							'result_sip_new_application', 'date_sip_new_application', 'fio_sip_new_application',
							'result_fin_new_application', 'date_fin_new_application', 'fio_fin_new_application',
							'agree_new_application', 'rejection_new_application',
							'date_roll_new_application', 'execution_roll_new_application',
							'date_reception_peo_new_application', 'executor_peo_new_application', 'count_dk_new_application',
							'price_approximate_new_application', 'check_approximate_new_application',
							'price_fixed_new_application', 'check_fixed_new_application',
							'answer_new_application', 'out_in_answer_new_application', 'date_answer_new_application'];
}
