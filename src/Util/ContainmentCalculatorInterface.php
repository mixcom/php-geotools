<?php
namespace GeoTools\Util;

use GeoTools\Model\BoundingBox2D;
use GeoTools\Model\Point2D;
use GeoTools\Model\Ring2D;
use GeoTools\Model\RingsBasedShape2D;

interface ContainmentCalculatorInterface
{
    /**
     * @param \GeoTools\Model\Point2D $point
     * @param \GeoTools\Model\BoundingBox2D $boundingBox
     * @return bool
     */
    public function inBoundingBox(Point2D $point, BoundingBox2D $boundingBox);

    /**
     * @param \GeoTools\Model\Point2D $point
     * @param \GeoTools\Model\RingsBasedShape2D $shape
     * @return bool
     */
    public function inRingsBasedShape(Point2D $point, RingsBasedShape2D $shape);
    
    /**
     * @param \GeoTools\Model\Point2D $point
     * @param \GeoTools\Model\RingsBasedShape2D $shape
     * @param double $radius
     * @return bool
     */
    public function inRadiusAroundRingsBasedShape(Point2D $point, RingsBasedShape2D $shape, $radius);

    /**
     * @param \GeoTools\Model\Point2D $point
     * @param \GeoTools\Model\Ring2D $ring
     * @return bool
     */
    public function inRing(Point2D $point, Ring2D $ring);

    /**
     * @param \GeoTools\Model\Point2D $point
     * @param \GeoTools\Model\Ring2D $ring
     * @param double $radius
     * @return bool
     */
    public function inRadiusAroundRing(Point2D $point, Ring2D $ring, $radius);

    /**
     * @param \GeoTools\Model\Point2D $point
     * @param \GeoTools\Model\Ring2D $ring
     * @return double
    */
    public function distanceToRing(Point2D $point, Ring2D $ring);

    /**
     * @param \GeoTools\Model\Point2D $point
     * @param \GeoTools\Model\RingsBasedShape2D $shape
     * @return double
    */
    public function distanceToRingsBasedShape(Point2D $point, RingsBasedShape2D $shape);
}
