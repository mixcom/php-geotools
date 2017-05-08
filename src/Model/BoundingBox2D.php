<?php
namespace GeoTools\Model;

final class BoundingBox2D
{
    /**
     * @var double
     */
    public $xmin;

    /**
     * @var double
     */
    public $xmax;

    /**
     * @var double
     */
    public $ymin;

    /**
     * @var double
     */
    public $ymax;

    /**
     * @param double $xmin
     * @param double $xmax
     * @param double $ymin
     * @param double $ymax
     */
    public function __construct($xmin, $xmax, $ymin, $ymax)
    {
        $this->xmin = $xmin;
        $this->xmax = $xmax;
        $this->ymin = $ymin;
        $this->ymax = $ymax;
    }

    /**
     * @param double $marginX
     * @param double $marginY
     * @return BoundingBox2D
     */
    public function withMargin($marginX, $marginY)
    {
        return new BoundingBox2D($this->xmin - $marginX, $this->xmax + $marginX,
          $this->ymin - $marginY, $this->ymax + $marginY);
    }
}
