<?php

namespace App\Service;

class GeoLocation
{
    private const COUNTRY = 'belgium';

    public function returnGeoLocation($city)
    {
        $geo = new \JeroenDesloovere\Geolocation\Geolocation();
        //$geo->getCoordinates();
        //$geolocation = $this->get('jeroendesloovere.geolocation');
//        $result = \JeroenDesloovere\Geolocation\Geolocation::getCoordinates(
//            '',
//            '',
//            $city->getName(),
//            $city->getZipcode(),
//            'belgium'
//        );

        dd($geo);
    }

}
