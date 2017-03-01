<?php
namespace GeoTools\Model;

final class Ring2D
{
    /**
     * @var Point2D[]
     */
    public $points;

    /**
     * @var BoundingBox2D|null
     */
    public $boundingBox;

    /**
     * @param Point2D[] $points
     * @param BoundingBox2D|null $boundingBox
     */
    public function __construct(array $points, BoundingBox2D $boundingBox = null)
    {
        if (!$points) {
            throw new \InvalidArgumentException('A ring must have at least 1 point');
        }

        $this->points = $points;
        $this->boundingBox = $boundingBox;
    }

    /**
     * Get vertices for the current ring.
     *
     * @return Vertex2D[]
     */
    public function getVertices()
    {
        $firstPoint = $this->points[0];
        $lastPoint = null;
        $vertices = [];
        foreach ($this->points as $point) {
            if ($lastPoint !== null) {
                $vertices[] = new Vertex2D($lastPoint, $point);
            }
            $lastPoint = $point;
        }
        $vertices[] = new Vertex2D($lastPoint, $firstPoint);
        return $vertices;
    }

    /**
     * Get a bounding box for the current ring.
     *
     * @return BoundingBox2D
     */
    public function getBoundingBox()
    {
        if ($this->boundingBox === null) {
            $this->boundingBox = self::calculateBoundingBox($this->points);
        }
        return $this->boundingBox;
    }

    /**
     * @param Ring2D[] $rings
     * @return BoundingBox2D
     */
    public static function calculateBoundingBoxForRings(array $rings) {
        if (!$rings) {
            return new BoundingBox2D(0, 0, 0, 0);
        }

        $xmin = null;
        $xmax = null;
        $ymin = null;
        $ymax = null;

        foreach ($rings as $ring) {
            $boundingBox = $ring->boundingBox;
            if ($xmin === null || $boundingBox->xmin < $xmin) {
                $xmin = $boundingBox->xmin;
            }
            if ($xmax === null || $boundingBox->xmax > $xmax) {
                $xmax = $boundingBox->xmax;
            }
            if ($ymin === null || $boundingBox->ymin < $ymin) {
                $ymin = $boundingBox->ymin;
            }
            if ($ymax === null || $boundingBox->ymax > $ymax) {
                $ymax = $boundingBox->ymax;
            }
        }

        return new BoundingBox2D($xmin, $xmax, $ymin, $ymax);
    }

    /**
     * @param Point2D[] $points
     * @return BoundingBox2D
     */
    private static function calculateBoundingBox(array $points) {
        return Point2D::calculateBoundingBoxForPoints($points);
    }
}
