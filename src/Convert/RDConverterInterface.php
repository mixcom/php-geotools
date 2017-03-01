<?php
namespace GeoTools\Convert;

use GeoTools\Model\GPSCoordinates;
use GeoTools\Model\RDCoordinates;

interface RDConverterInterface
{
    /**
     * @param \GeoTools\Model\RDCoordinates $coords
     * @return \GeoTools\Model\GPSCoordinates
     */
    public function convertRDToWGS84(RDCoordinates $coords);

    /**
     * @param \GeoTools\Model\GPSCoordinates $coords
     * @return \GeoTools\Model\RDCoordinates
     */
    public function convertWGS84ToRD(GPSCoordinates $coords);
}
