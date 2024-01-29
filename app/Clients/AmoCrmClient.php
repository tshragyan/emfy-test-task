<?php

namespace App\Clients;

use AmoCRM\Client\AmoCRMApiClient;
use App\Models\AmoCrmToken;
use Illuminate\Support\Facades\Log;
use League\OAuth2\Client\Token\AccessToken;

class AmoCrmClient
{

    public static function getClient()
    {
        $realToken = AmoCrmToken::query()->firstOrFail();

        $client = new AmoCRMApiClient(config('amo.client.id'), config('amo.client.secret'), config('amo.client.redirect_url'));
        $client->setAccountBaseDomain(config('amo.client.base_domain'));
        $token = new AccessToken(json_decode($realToken->token, 1));

        $client->setAccessToken($token);

        $client->onAccessTokenRefresh(function($token) {
            $realToken = AmoCrmToken::query()->firstOrFail();
            $realToken->token = json_encode($token);
            $realToken->save();
        });

        return $client;

    }

    public static function setConnection($code)
    {
        $connection = new AmoCRMApiClient(config('amo.client.id'), config('amo.client.secret'), config('amo.client.redirect_url'));
        $connection->setAccountBaseDomain(config('amo.client.base_domain'));
        $token = $connection->getOAuthClient()->getAccessTokenByCode($code);

        return $token;
    }
}
