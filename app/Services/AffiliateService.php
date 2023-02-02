<?php

namespace App\Services;

use App\Exceptions\AffiliateCreateException;
use App\Mail\AffiliateCreated;
use App\Models\Affiliate;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AffiliateService
{
    public function __construct(
        protected ApiService $apiService
    ) {
    }

    /**
     * Create a new affiliate for the merchant with the given commission rate.
     *
     * @param  Merchant $merchant
     * @param  string $email
     * @param  string $name
     * @param  float $commissionRate
     * @return Affiliate
     */
    public function register(Merchant $merchant, string $email, string $name, float $commissionRate): Affiliate
    {
        // TODO: Complete this method
        $apiData = $this->apiService->createDiscountCode($merchant);
        $user = $merchant->user;
        $type = $user->type;
        $userAffiliate = $user->affiliate;
        $affiliate = new Affiliate;
        if (!$userAffiliate) {
            throw new AffiliateCreateException('Create Affiliate Account');
        } else {
            if ($type === User::TYPE_MERCHANT) {
                $affiliate->user_id = $user->id;
                $affiliate->merchant_id = $merchant->id;
                $affiliate->commission_rate = $commissionRate;
                $affiliate->discount_code =  $apiData['code'];
            }
            if (!$user->merchant) {
                throw new AffiliateCreateException('Create Affiliate Account');
            } else {
                if ($type === User::TYPE_AFFILIATE) {
                    $affiliate->user_id = $user->id;
                    $affiliate->merchant_id = $merchant->id;
                    $affiliate->commission_rate = $commissionRate;
                    $affiliate->discount_code =  $apiData['code'];
                }
            }
        }
        $affiliate->save();
        return $affiliate;
    }
}
