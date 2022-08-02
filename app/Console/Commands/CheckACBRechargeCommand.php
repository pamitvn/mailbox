<?php

namespace App\Console\Commands;

use App\Models\Bank;
use App\Models\RechargeHistory;
use App\Models\User;
use Bavix\Wallet\Internal\Service\DatabaseServiceInterface;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;

class CheckACBRechargeCommand extends Command
{
    protected $signature = 'recharge:acb';

    protected $description = 'Check recharge ACB';

    public function handle()
    {
        $endpoint = settings('web2m_acb', config('web-config.web2m.banks.acb'), 'payment');
        try {
            $client = new Client([
                'http_errors' => false,
            ]);
            $response = $client->get($endpoint);
            $rawData = json_decode($response->getBody()->getContents(), true) ?? [];
            $data = Arr::get($rawData, 'transactions', []);
            $status = Arr::get($rawData, 'success', false);

            $regexUser = '/mailbox[0-9]+/';
            $regexFindUserId = 'mailbox';

            $bank = Bank::where('name', 'LIKE', '%acb%')->first(['id', 'name']);

            if (! $status) {
                return;
            }

            $onlyAtSite = array_filter($data, function ($item) use ($regexUser) {
                $type = Arr::get($item, 'type', 'OUT');
                $description = strtolower(Arr::get($item, 'description'));
                $amount = (int) Arr::get($item, 'amount', 0);

                return $type === 'IN' && preg_match($regexUser, $description) && $amount > 0;
            });

            foreach ($onlyAtSite as $item) {
                $transId = Arr::get($item, 'transactionNumber');
                $amount = (int) Arr::get($item, 'amount', 0);
                $description = strtolower(Arr::get($item, 'description'));

                preg_match($regexUser, $description, $userMatched);
                $userMatched = Arr::first($userMatched);
                $userId = str_replace($regexFindUserId, '', $userMatched);

                if (! $userMatched || ! $userId) {
                    continue;
                }

                try {
                    app(DatabaseServiceInterface::class)->transaction(function () use ($bank, $userId, $transId, $amount) {
                        $user = User::findOrFail($userId);
                        $rechargeExists = RechargeHistory::whereUserId($user->id)
                            ->whereJsonContains('extras->transId', $transId)
                            ->first();

                        if (! is_null($rechargeExists)) {
                            return;
                        }

                        $userBalanceBeforeTrans = $user->balance;

                        $user->deposit($amount);

                        RechargeHistory::create([
                            'bank_id' => $bank?->id,
                            'user_id' => $user->id,
                            'amount' => $amount,
                            'before_transaction' => $userBalanceBeforeTrans,
                            'after_transaction' => $user->balance,
                            'note' => Blade::render('[{{ $bankName }}] Transaction id {{ $transId }}', [
                                'bankName' => $bank->name,
                                'transId' => $transId,
                            ]),
                            'extras' => [
                                'transId' => $transId,
                            ],
                        ]);
                    });
                } catch (Exception $exception) {
                    pam_system_log()->error('[Check::Recharge::ABC] '.$exception->getMessage());
                    continue;
                }
            }
        } catch (Exception  $exception) {
            pam_system_log()->error('[Check::Recharge::ABC] fetching failed : '.$exception->getMessage());
        }
    }
}
