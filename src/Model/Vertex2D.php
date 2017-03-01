<?php
namespace GeoTools\Model;

final class Vertex2D
{
    /**
     * @var Point2D
     */
    public $p1;

    /**
     * @var Point2D
     */
    public $p2;

    /**
     * @var BoundingBox2D|null
     */
    public $boundingBox;

    /**
     * @param Point2D $p1
     * @param Point2D $p2
     */
    public function __construct(Point2D $p1, Point2D $p2)
    {
        $this->p1 = $p1;
        $this->p2 = $p2;
    }

    /**
     * Get a bounding box for the current vertex.
     *
     * @return BoundingBox2D
     */
    public function getBoundingBox()
    {
        if ($this->boundingBox === null) {
            $this->boundingBox = self::calculateBoundingBox($this->p1, $this->p2);
        }
        return $this->boundingBox;
    }

    private static function calculateBoundingBox(Point2D $p1, Point2D $p2)
    {
        return Point2D::calculateBoundingBoxForPoints([$p1, $p2]);
    }
}
