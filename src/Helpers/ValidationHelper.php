<?php

namespace App\Helpers;

use Symfony\Component\HttpClient\CurlHttpClient;

/**
 * Class ValidationHelper
 * @package App\Helpers
 */
class ValidationHelper
{
    /**
     * PHONE_VALIDATION_URL const
     */
    const PHONE_VALIDATION_URL = 'http://apilayer.net/api/validate';

    /**
     * PHONE_VALIDATION_URL_PARAMS const
     */
    const PHONE_VALIDATION_URL_STATIC_PARAMS  = [
        'access_key'   => '',
        'country_code' => '',
        'format'       => 1
    ];

    /**
     * @param string $phoneNumber
     * @param string $apiKey
     * @return bool
     */
    public static function validPhoneNumber(string $phoneNumber, string $apiKey = '') :bool
    {
        $url = self::PHONE_VALIDATION_URL . '?' . http_build_query(
            array_merge(self::PHONE_VALIDATION_URL_STATIC_PARAMS, [
                'access_key' => $apiKey,
                'number'     => $phoneNumber
            ])
        );

        try {
            $client = new CurlHttpClient();
            $response = $client->request('GET', $url);
            $content  = $response->getContent();
            $content  = \json_decode($content, true);

            if (!isset($content['valid']) || !$content['valid']) {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}