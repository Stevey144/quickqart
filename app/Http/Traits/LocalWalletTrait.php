<?php


namespace App\Http\Traits;


use App\Role;

trait LocalWalletTrait
{
    public function getWalletBalanceAttribute() {
        return $this->total_credit + $this->total_debit;
    }

    public function getTotalCreditAttribute() {
        if ($this->hasRole(Role::USER)) {
            return $this->credits()->where('has_commission', false)->where('status', 'credit')->sum('amount');
        } else if ($this->hasRole(Role::AGENT)) {
            return $this->credits()->local()->where('has_commission', true)->sum('agent');
        }

        return 0;
    }

    public function getTotalDebitAttribute() {
        if ($this->hasRole(Role::USER)) {
            return $this->credits()->where('has_commission', false)->where('status', 'debit')->sum('amount');
        } else if ($this->hasRole(Role::AGENT)) {
            return $this->credits()->local()->where('has_commission', false)->where('status', 'debit')->sum('amount');
        }

        return 0;

    }

    public function getPendingDebitAttribute() {
        if ($this->hasRole(Role::AGENT)) {
            return $this->debitRequests()->local()->whereNull('approved_at')->sum('amount');
        }

        return 0;
    }

}
