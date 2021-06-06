<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Executor extends Model
{
    protected $fillable = ['id_contract','id_application','isp_dir','zam_isp_dir_niokr','main_in','dir_sip','dir_peo','isp_dir_check',
							'zam_isp_dir_niokr_check','main_in_check','dir_sip_check','dir_peo_check','dep_2','dep_15','dep_93','dep_main_tech',
							'dep_10','shop_1','shop_2','shop_3','ootiz','dep_2_check','dep_15_check','dep_93_check',
							'dep_main_tech_check','dep_10_check','shop_1_check','shop_2_check','shop_3_check','ootiz_check'];
}
