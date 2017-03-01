<?php
namespace GeoTools\Model;

final class RingsBasedShape2D
{
    /**
     * @var BoundingBox2D|null
     */
    public $boundingBox;

    /**
     * @var Ring2D[]
     */
    public $rings;

    /**
     * @param \GeoTools\Model\BoundingBox2D $boundingBox
     * @param Ring2D[] $rings
     */
    public function __construct(array $rings, BoundingBox2D $boundingBox = null)
    {
        $this->rings = $rings;
        $this->boundingBox = $boundingBox;
    }

    /**
     * Get a bounding box for the current shape.
     *
     * @return BoundingBox2D
     */
    public function getBoundingBox()
    {
        if ($this->boundingBox === null) {
            $this->boundingBox = self::calculateBoundingBox($this->rings);
        }
        return $this->boundingBox;
    }

    /**
     * @param Ring2D[] $rings
     * @return BoundingBox2D
     */
    private static function calculateBoundingBox(array $rings) {
        return Ring2D::calculateBoundingBoxForRings($rings);
    }
}
