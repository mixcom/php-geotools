<?php
namespace GeoTools\Model;

final class Circle2D
{
    /**
     * @var Point2D
     */
    public $p;

    /**
     * @var double
     */
    public $r;

    /**
     * @var BoundingBox2D|null
     */
    private $boundingBox;

    /**
     * @param Point2D $p
     * @param double $r
     */
    public function __construct(Point2D $p, $r)
    {
        $this->p = $p;
        $this->r = abs($r);
    }

    /**
     * Get a bounding box for the current circle.
     *
     * @return BoundingBox2D
     */
    public function getBoundingBox()
    {
        if ($this->boundingBox === null) {
            $this->boundingBox = new BoundingBox2D($this->p->x - $this->r,
              $this->p->x + $this->r, $this->p->y - $this->r,
              $this->p->y + $this->r);
        }
        return $this->boundingBox;
    }
}
