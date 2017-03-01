<?php
namespace GeoTools\Model;

final class GPSCoordinates
{
    /**
     * @var double
     */
    public $latitude;

    /**
     * @var double
     */
    public $longitude;

    /**
     * @param double $latitude
     * @param double $longitude
     */
    public function __construct($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * @return \GeoTools\Model\Point2D
     */
    public function toPoint()
    {
        return new Point2D($this->latitude, $this->longitude);
    }
}