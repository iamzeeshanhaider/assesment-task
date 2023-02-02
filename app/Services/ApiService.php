<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\Merchant;
use Illuminate\Support\Str;
use RuntimeException;
use App\Mail\AffiliateCreated;
use App\Models\User;

/**
 * You don't need to do anything here. This is just to help
 */
class ApiService
{
    /**
     * Create a new discount code for an affiliate
     *
     * @param Merchant $merchant
     *
     * @return array{id: int, code: string}
     */
    public function createDiscountCode(Merchant $merchant): array
    {

        return [
            'id' => rand(0, 100000),
            'code' => Str::uuid()
        ];
    }

    /**
     * Send a payout to an email
     *
     * @param  string $email
     * @param  float $amount
     * @return void
     * @throws RuntimeException
     */
    public function sendPayout(string $email, float $amount)
    {
        $user = User::where('email', $email)->first();
        $affiliate = $user?->affiliate;
        $mail = new AffiliateCreated($affiliate);
        if (!$mail) {
            throw new RuntimeException('Something went wrong');
        }
    }
}
