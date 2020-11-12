<?php


namespace App\Http\Traits;


use App\Role;

trait ForeignWalletTrait
{
    public function getForeignWalletBalanceAttribute() {
        return $this->foreign_total_credit + $this->foreign_total_debit;
    }

    public function getForeignTotalCreditAttribute() {
        if ($this->hasRole(Role::AGENT)) {
            return $this->credits()->foreign()->where('has_commission', true)->sum('agent');
        }

        return 0;
    }

    public function getForeignTotalDebitAttribute() {
        if ($this->hasRole(Role::AGENT)) {
            return $this->credits()->foreign()->where('has_commission', false)->where('status', 'debit')->sum('amount');
        }

        return 0;

    }

    public function getForeignPendingDebitAttribute() {
        if ($this->hasRole(Role::AGENT)) {
            return $this->debitRequests()->foreign()->whereNull('approved_at')->sum('amount');
        }

        return 0;
    }

}
