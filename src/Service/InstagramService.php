<?php

namespace App\Service;

class InstagramService
{
    public function getUserStories($instagramAccountId, $accessToken)
    {
        $endpoint = 'https://graph.facebook.com/v16.0/' . $instagramAccountId . '/stories';

        $params = array(
            'access_token' => $accessToken
        );

        // make the api call and get a response
        $response = $this->makeApiCall($endpoint, 'GET', $params);

        // return data from the response
        return $response["data"]["data"];
    }

    public function getMediaInfo($media, $accessToken)
    {
        // actual endpoint with a media
        $endpoint = 'https://graph.facebook.com/v16.0/' . $media['id'];

        $params = array( // parameters for the endpoint
            'fields' => 'caption,id,ig_id,media_product_type,media_type,media_url,owner,permalink,shortcode,thumbnail_url,timestamp,username',
            'access_token' => $accessToken
        );

        // make the api call and get a response
        $response = $this->makeApiCall($endpoint, 'GET', $params);

        // return data from the response
        return $response["data"];
    }

    private function makeApiCall($endpoint, $type, $params)
    {
        // initialize curl
        $ch = curl_init();

        // create endpoint with params
        $apiEndpoint = $endpoint . '?' . http_build_query($params);

        // set other curl options
        curl_setopt($ch, CURLOPT_URL, $apiEndpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // get response
        $response = curl_exec($ch);

        // close curl
        curl_close($ch);

        return array( // return data
            'type' => $type,
            'endpoint' => $endpoint,
            'params' => $params,
            'api_endpoint' => $apiEndpoint,
            'data' => json_decode($response, true)
        );
    }
}