<?php

namespace Akyos\CloudflareapiBundle\Service;

use stdClass;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;


class CloudflareService
{
    const BASE_URI = "https://api.cloudflare.com/client/v4/zones/";

    const API_URI = [
        'brotli' => [
            "uri" => CloudflareService::BASE_URI."{zoneId}/settings/brotli",
            "label" => "Brotli",
            "help" => "Speed up page load times for your visitor’s HTTPS traffic by applying Brotli compression.",
            "type" => "checkbox"
        ],
        'early_hints' => [
            "uri" => CloudflareService::BASE_URI."{zoneId}/settings/early_hints",
            "label" => "Early Hints",
            "help" => "Cloudflare's edge will cache and send 103 Early Hints responses with Link headers from your HTML pages. Early Hints allows browsers to preload linked assets before they see a 200 OK or other final response from the origin.",
            "type" => "checkbox"
        ],
        'auto_minify' => [
            "uri" => CloudflareService::BASE_URI."{zoneId}/settings/minify",
            "label" => "Auto Minify",
            "help" => "Reduce the file size of source code on your website.",
            "type" => "checkbox"
        ],
        'purge_cache' => [
            "uri" => CloudflareService::BASE_URI."{zoneId}/purge_cache",
            "label" => "Purge Cache",
            "help" => "Clear cached files to force Cloudflare to fetch a fresh version of those files from your web server. You can purge files selectively or all at once.            ",
            "type" => "button"
        ]
    ];

    public function __construct(private readonly ContainerBagInterface $params)
    {
    }

    public function getValue(string $uri) : mixed
    {
        try{
            $curl = curl_init();

            curl_setopt_array($curl, [
              CURLOPT_URL => str_replace('{zoneId}', $this->params->get('cloudflare_zone'), $uri),
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "GET",
              CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "X-Auth-Email: aubin.amardeil@akyos.com",
                "X-Auth-Key: ".$this->params->get('cloudflare_api_key')
              ],
            ]);

            $response = (object) json_decode((string) curl_exec($curl));

            if($response->errors){
                $error = $response->errors[0];
                throw new Exception($error->message, $error->code);
            }
            $value = $response->result->value;
            return  $value instanceof stdClass ? json_decode(json_encode($value), true) : $value;

        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function setValue(string $value, string $name): mixed
    {
        try{
            $curl = curl_init();

            curl_setopt_array($curl, [
              CURLOPT_URL => str_replace('{zoneId}', $this->params->get('cloudflare_zone'), CloudflareService::API_URI[$name]['uri']),
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "X-Auth-Email: aubin.amardeil@akyos.com",
                "X-Auth-Key: ".$this->params->get('cloudflare_api_key')
              ],
            ]);

            if($value){
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $value);
            }else{
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($curl, CURLOPT_POSTFIELDS, '{"purge_everything":true}');
            }

            $response = json_decode(curl_exec($curl));

            if($response->errors){
                $error = $response->errors[0];
                throw new Exception($error->message, $error->code);
            }

        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }

        return new JsonResponse($response);
    }

}
