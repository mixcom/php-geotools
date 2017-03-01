<?php
namespace GeoTools\Convert;

use GeoTools\Model\GPSCoordinates;
use GeoTools\Model\RDCoordinates;

/**
 * Convert between RD anD WGS84 coordinates.
 *
 * Port of the Python code found at https://thomasv.nl/2014/03/rd-naar-gps/
 *
 * @package GeoTools\Convert
 */
final class DefaultRDConverter implements RDConverterInterface
{
    private $x0 = 155000;
    private $y0 = 463000;
    private $lat0 = 52.15517440;
    private $long0 = 5.38720621;

    /**
     * @inheritDoc
     */
    public function convertRDToWGS84(RDCoordinates $coords)
    {
        $kp = [0, 2, 0, 2, 0, 2, 1, 4, 2, 4, 1];
        $kq = [1, 0, 2, 1, 3, 2, 0, 0, 3, 1, 1];
        $kpq = [
          3235.65389,
          -32.58297,
          -0.24750,
          -0.84978,
          -0.06550,
          -0.01709,
          -0.00738,
          0.00530,
          -0.00039,
          0.00033,
          -0.00012,
        ];
        $lp = [1, 1, 1, 3, 1, 3, 0, 3, 1, 0, 2, 5];
        $lq = [0, 1, 2, 0, 3, 1, 1, 2, 4, 2, 0, 0];
        $lpq = [
          5260.52916,
          105.94684,
          2.45656,
          -0.81885,
          0.05594,
          -0.05607,
          0.01199,
          -0.00256,
          0.00128,
          0.00022,
          -0.00022,
          0.00026,
        ];

        $dx = 1e-5 * ($coords->x - $this->x0);
        $dy = 1e-5 * ($coords->y - $this->y0);

        $lat = $this->lat0;
        for ($i = 0; $i < count($kpq); $i++) {
            $lat += ($kpq[$i] * pow($dx, $kp[$i]) * pow($dy, $kq[$i])) / 3600;
        }

        $long = $this->long0;
        for ($i = 0; $i < count($lpq); $i++) {
            $long += ($lpq[$i] * pow($dx, $lp[$i]) * pow($dy, $lq[$i])) / 3600;
        }

        return new GPSCoordinates($lat, $long);
    }

    /**
     * @inheritDoc
     */
    public function convertWGS84ToRD(GPSCoordinates $coords)
    {
        $rp = [0, 1, 2, 0, 1, 3, 1, 0, 2];
        $rq = [1, 1, 1, 3, 0, 1, 3, 2, 3];
        $rpq = [
          190094.945,
          -11832.228,
          -114.221,
          -32.391,
          -0.705,
          -2.340,
          -0.608,
          -0.008,
          0.148,
        ];
        $sp = [1, 0, 2, 1, 3, 0, 2, 1, 0, 1];
        $sq = [0, 2, 0, 2, 0, 1, 2, 1, 4, 4];
        $spq = [
          309056.544,
          3638.893,
          73.077,
          -157.984,
          59.788,
          0.433,
          -6.439,
          -0.032,
          0.092,
          -0.054,
        ];

        $dlat = 0.36 * ($coords->latitude - $this->lat0);
        $dlong = 0.36 * ($coords->longitude - $this->long0);

        $x = $this->x0;
        $y = $this->y0;

        for ($i = 0; $i < count($rpq); $i++) {
            $x += ($rpq[$i] * pow($dlat, $rp[$i]) * pow($dlong, $rq[$i]));
        }

        for ($i = 0; $i < count($spq); $i++) {
            $y += ($spq[$i] * pow($dlat, $sp[$i]) * pow($dlong, $sq[$i]));
        }

        return new RDCoordinates($x, $y);
    }

}
