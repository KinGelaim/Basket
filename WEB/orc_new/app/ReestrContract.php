<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReestrContract extends Model
{
    protected $fillable = ['id_contract_reestr','id_view_contract','amount_contract_reestr','fix_amount_contract_reestr','big_deal_reestr','amount_invoice_reestr','selection_supplier_reestr','type_document_reestr',
							'place_save_contract_reestr', 'count_save_contract_reestr', 'date_save_contract_reestr', 'date_save_contract_el_reestr',
							'executor_contract_reestr', 'executor_reestr', 'oud_original_contract_reestr', 'otd_original_contract_reestr', 'date_contract_on_first_reestr', 'date_signing_contract_reestr', 
							'date_control_signing_contract_reestr', 'date_registration_project_reestr', 'date_registration_application_reestr',
							'application_reestr', 'date_signing_contract_counterpartie_reestr', 'date_entry_into_force_reestr', 
							'reconciliation_protocol_reestr', 'reconciliation_agreement_reestr',
							'date_the_end_contract_reestr', 'number_inquiry_reestr', 'date_inquiry_reestr', 'number_answer_reestr', 'date_answer_reestr', 'days_reconciliation_reestr', 'count_mounth_reestr',
							'begin_date_reconciliation_reestr', 'end_date_reconciliation_reestr', 'count_days_reconciliation_reestr',
							'base_reestr', 'app_outgoing_number_reestr', 'app_incoming_number_reestr', 'result_second_department_date_reestr', 'result_second_department_number_reestr', 'date_complete_reestr', 
							'reestr_number_reestr', 
							'marketing_reestr', 'marketing_goz_reestr', 'participation_reestr', 'marketing_fz_223_reestr', 'marketing_fz_44_reestr',
							'export_reestr', 'interfactory_reestr',
							'procurement_reestr', 'single_provider_reestr', 'own_funds_reestr', 'investments_reestr', 'purchase_reestr',
							'procurement_fz_223_reestr', 'procurement_fz_44_reestr', 'procurement_goz_reestr', 'other_reestr', 'mob_reestr',
							'okpo_reestr', 'okved_reestr', 'number_contestants_reestr', 'denied_admission_reestr',
							'cash_order_reestr', 'cash_contract_reestr', 'non_cash_order_reestr', 'non_cash_contract_reestr', 'date_execution_reestr', 'number_counterpartie_contract_reestr', 'igk_reestr', 'ikz_reestr',
							'okdp_reestr', 'okpd_2_reestr', 'term_action_reestr', 'date_bank_reestr', 'amount_bank_reestr', 'bank_reestr', 'date_b_contract_reestr', 'date_e_contract_reestr', 'date_contract_reestr',
							'date_maturity_date_reestr', 'date_maturity_reestr', 'date_e_maturity_reestr', 'amount_reestr', 'amount_year_reestr', 'amount_contract_year_reestr', 
							'unit_reestr', 'vat_reestr', 'approximate_amount_reestr', 'fixed_amount_reestr',
							'amount_begin_reestr', 'unit_begin_reestr', 'vat_begin_reestr', 'approximate_amount_begin_reestr', 'fixed_amount_begin_reestr',
							'percent_prepayment_reestr', 'prepayment_reestr', 'economy_reestr', 'economy_unit_reestr', 'end_term_repayment_reestr',
							'nmcd_reestr', 'nmcd_unit_reestr', 'amount_comment_reestr', 
							'prepayment_order_reestr', 'score_order_reestr', 'payment_order_reestr', 'prolongation_reestr',
							're_registration_reestr'
	];
}
