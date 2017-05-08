<?php
namespace GeoTools\Model;

final class Point2D
{
    /**
     * @var double
     */
    public $x;

    /**
     * @var double
     */
    public $y;

    /**
     * @param double $x
     * @param double $y
     */
    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }
    
    /**
     * @param Point2D $point
     * @return double
     */
    public function distanceToPoint(Point2D $point) {
        return sqrt($this->squareDistanceToPoint($point));
    }
    
    /**
     * @param Point2D $point
     * @return double
     */
    public function squareDistanceToPoint(Point2D $point) {
        return pow($point->x - $this->x, 2) + pow($point->y - $this->y, 2);
    }

    /**
     * @param Point2D[] $points
     * @return BoundingBox2D
     */
    public static function calculateBoundingBoxForPoints(array $points) {
        if (!$points) {
            return new BoundingBox2D(0, 0, 0, 0);
        }

        $xmin = null;
        $xmax = null;
        $ymin = null;
        $ymax = null;

        foreach ($points as $point) {
            if ($xmin === null || $point->x < $xmin) {
                $xmin = $point->x;
            }
            if ($xmax === null || $point->x > $xmax) {
                $xmax = $point->x;
            }
            if ($ymin === null || $point->y < $ymin) {
                $ymin = $point->y;
            }
            if ($ymax === null || $point->y > $ymax) {
                $ymax = $point->y;
            }
        }

        return new BoundingBox2D($xmin, $xmax, $ymin, $ymax);
    }
}
