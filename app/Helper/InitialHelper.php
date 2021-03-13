<?php

namespace App\Helper;

use App\Http\Models\Accounting\AccountingJournal;
use App\Http\Models\Accounting\Debt;
use App\Http\Models\GeneralAdmin\WageSubmission;
use App\Http\Models\Inventory\DeliveryOrders;
use App\Http\Models\Inventory\ReceiptOfGoodsRequest;
use App\Http\Models\Project\RequestMaterials;
use App\Http\Models\Project\SpkProjectAdditionals;
use App\Http\Models\Project\SpkProjects;
use App\Http\Models\Project\WorkAgreementAdditionals;
use App\Http\Models\Project\WorkAgreements;
use App\Http\Models\Purchase\PurchaseOrderDeliveries;
use App\Http\Models\Purchase\PurchaseOrders;
use Carbon\Carbon;

class InitialHelper
{
    /**
     * Generate initials from a name
     *
     * @param string $name
     * @return string
     */
    public static function generate(string $name) : string
    {
        $words = explode(' ', $name);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
        }
        return $this->makeInitialsFromSingleWord($name);
    }

    /**
     * Make initials from a word with no spaces
     *
     * @param string $name
     * @return string
     */
    protected function makeInitialsFromSingleWord(string $name) : string
    {
        preg_match_all('#([A-Z]+)#', $name, $capitals);
        if (count($capitals[1]) >= 2) {
            return substr(implode('', $capitals[1]), 0, 2);
        }
        return strtoupper(substr($name, 0, 2));
    }
}