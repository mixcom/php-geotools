<?php
namespace GeoTools\Model;

final class RDCoordinates
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
     * @return \GeoTools\Model\Point2D
     */
    public function toPoint()
    {
        return new Point2D($this->x, $this->y);
    }
}
