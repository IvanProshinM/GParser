<?php

namespace app\services;

class QueryDetailService
{

    public function createQuery($place)
    {

        $detailQueryUrl = 'https://maps.googleapis.com/maps/api/place/details/json?';
        $detailQueryArray = [
            'placeid' => $place['place_id'],
            'fields' => 'opening_hours,website,rating,formatted_address,international_phone_number',
            'key' => 'AIzaSyDgKrL7ZGekAAuAgW6-hi936Nxa_6LAVPM'
        ];
        $detailQueryUrl .= http_build_query($detailQueryArray, '', '&',);
        $detailQueryJson = file_get_contents($detailQueryUrl);
        return json_decode($detailQueryJson, true);
    }
}