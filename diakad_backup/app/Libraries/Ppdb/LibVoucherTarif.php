<?php

namespace App\Libraries\Ppdb;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;

use App\Models\VoucherTarif as VoucherTarif;
use DB;

/** 
 * Library function for voucher tarif
 * @author irianto
 */
class LibVoucherTarif
{
    /** 
     * Get data voucher tarif by spesific semester
     * @param String id_semester
     * @return Object voucher_tarif
     */
    static function getVoucherTarifBySemester($id_semester = null)
    {
        /** get tarif voucher */
        $voucher_tarif = VoucherTarif::where('voucher_tarif.id_semester', $id_semester)
            ->LeftJoin('semester', 'semester.id_semester', '=', 'voucher_tarif.id_semester')
            ->LeftJoin('jurusan', 'jurusan.id_jurusan', '=', 'voucher_tarif.id_jurusan')
            ->get();

        return $voucher_tarif;
    }
  
}
