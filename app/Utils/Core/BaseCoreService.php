<?php

namespace App\Utils\Core;

use App\Utils\General\ApiCallUtils;
use Exception;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;


class BaseCoreService
{
    private const ACCESS_TOKEN_KEY = "__core__app__access__token";

    /**
     * @throws Exception
     */
    public static function getAccessToken(): string
    {
        if (Cache::has(self::ACCESS_TOKEN_KEY)) {
            return Cache::get(self::ACCESS_TOKEN_KEY);
        } else {

            $response = Http::asForm()->post(sprintf('%s/%s', config('core.app.api.url'), Endpoints::TOKEN_ENDPOINT), [
                'grant_type' => 'client_credentials',
                'client_id' => config('env.auth.reachafrika_client_id'),
                'client_secret' => config('env.auth.reachafrika_client_secret'),
                'scope' => '',
            ]);

            if ($response->successful()) {

                ['access_token' => $token, 'expires_in' => $ttl] = $response->json();

                Cache::put(self::ACCESS_TOKEN_KEY, $token, now()->addSeconds($ttl)->subMinute());
                return $token;
            }

            throw new Exception('we could not establish a handshake with our external API');
        }
    }

    /**
     * @throws RequestException
     * @throws Exception
     */
    public static function makeCall(string $endpoint, array $payload = [], string $method = ApiCallUtils::METHOD_POST) : Response
    {
        Log::info("================================ PREPARE PAYLOAD- $endpoint ============================================");
        Log::info(get_class(), [$endpoint => $payload]);

        if (!in_array($method, ApiCallUtils::ALLOWED_METHODS)) throw new InvalidArgumentException("method '$method' not allowed");
        try {
            return Http::retry(3, 100)->withToken(self::getAccessToken())->$method($endpoint, $payload);
        } catch (RequestException $e) {
            if ($e->getCode() == ResponseAlias::HTTP_UNAUTHORIZED) {
                self::getAccessToken();
                return self::makeCall($endpoint, $payload, $method);
            }
            Log::error(get_class(), ['message' => json_decode($e->response->body(), true)]);
            throw $e;
        }

    }
}
