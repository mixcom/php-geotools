<?php
namespace GeoTools\Util;

use GeoTools\Model\BoundingBox2D;
use GeoTools\Model\Point2D;
use GeoTools\Model\Ring2D;
use GeoTools\Model\RingsBasedShape2D;
use GeoTools\Model\Vertex2D;

final class DefaultContainmentCalculator implements ContainmentCalculatorInterface
{
    /**
     * @param \GeoTools\Model\Point2D $point
     * @param \GeoTools\Model\BoundingBox2D $boundingBox
     * @return bool
     */
    public function inBoundingBox(Point2D $point, BoundingBox2D $boundingBox)
    {
        if ($boundingBox->xmin > $point->x) {
            return false;
        }
        if ($boundingBox->xmax < $point->x) {
            return false;
        }
        if ($boundingBox->ymin > $point->y) {
            return false;
        }
        if ($boundingBox->ymax < $point->y) {
            return false;
        }

        return true;
    }

    /**
     * @param \GeoTools\Model\Point2D $point
     * @param \GeoTools\Model\RingsBasedShape2D $shape
     * @return bool
     */
    public function inRingsBasedShape(Point2D $point, RingsBasedShape2D $shape)
    {
        if (!$this->inBoundingBox($point, $shape->getBoundingBox())) {
            return false;
        }

        foreach ($shape->rings as $ring) {
            if ($this->inRing($point, $ring)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \GeoTools\Model\Point2D $point
     * @param \GeoTools\Model\Ring2D $ring
     * @return bool
     */
    public function inRing(Point2D $point, Ring2D $ring)
    {
        $ringBoundingBox = $ring->getBoundingBox();
        if (!$this->inBoundingBox($point, $ringBoundingBox)) {
            return false;
        }

        $pointOutside = new Point2D(
          $ringBoundingBox->xmax + 1,
          $ringBoundingBox->ymax + 1
        );
        $vertexToOutside = new Vertex2D($point, $pointOutside);

        $vertices = $ring->getVertices();
        $intersections = 0;

        foreach ($vertices as $vertex) {
            if ($this->verticesIntersect($vertex, $vertexToOutside)) {
                $intersections++;
            }
        }

        return $intersections % 2 === 1;
    }

    /**
     * @param \GeoTools\Model\Vertex2D $v1
     * @param \GeoTools\Model\Vertex2D $v2
     * @return bool
     */
    private function linesIntersect(Vertex2D $v1, Vertex2D $v2)
    {
        return $this->lineIntersection($v1, $v2) !== null;
    }

    /**
     * @param \GeoTools\Model\Vertex2D $v1
     * @param \GeoTools\Model\Vertex2D $v2
     * @return \GeoTools\Model\Point2D|null
     */
    private function lineIntersection(Vertex2D $v1, Vertex2D $v2)
    {
        if ($v1->p1->x === $v1->p2->x) {
            // v1 is a vertical line. Simply find v2.y at x.
            if ($v2->p1->x === $v2->p2->x) {
                // v2 is also a vertical line.
                if ($v1->p1->x === $v2->p1->x) {
                    // They are the same line. Return some point as the intersection.
                    return $v1->p1;
                } else {
                    // They are parallel lines and not the same, so do not intersect.
                    return null;
                }
            }
            $dx = $v2->p2->x - $v2->p1->x;
            $dy = $v2->p2->y - $v2->p1->y;
            $slope = $dy / $dx;
            $y = $v2->p1->y + ($v1->p1->x - $v2->p1->x) * $slope;
            return new Point2D($v1->p1->x, $y);
        }

        $slope1 = ($v1->p2->y - $v1->p1->y) / ($v1->p2->x - $v1->p1->x);
        $slope2 = ($v2->p2->y - $v2->p1->y) / ($v2->p2->x - $v2->p1->x);
        $offset1 = $v1->p1->y - ($slope1 * $v1->p1->x);
        $offset2 = $v2->p1->y - ($slope2 * $v2->p1->x);
        if ($slope1 === $slope2) {
            // The lines are parallel.
            if ($offset1 === $offset2) {
                // They are the same line. Return some point as the intersection.
                return new Point2D(0, $offset1);
            } else {
                // They are parallel lines and not the same, so do not intersect.
                return null;
            }
        }

        $x = ($offset2 - $offset1) / ($slope1 - $slope2);
        $y = $offset1 + $slope1 * $x;
        return new Point2D($x, $y);
    }

    /**
     * @param \GeoTools\Model\Vertex2D $v1
     * @param \GeoTools\Model\Vertex2D $v2
     * @return bool
     */
    private function verticesIntersect(Vertex2D $v1, Vertex2D $v2)
    {
        $intersection = $this->lineIntersection($v1, $v2);
        if ($intersection === null) {
            return false;
        }
        if (!$this->inBoundingBox($intersection, $v1->getBoundingBox())) {
            return false;
        }
        if (!$this->inBoundingBox($intersection, $v2->getBoundingBox())) {
            return false;
        }
        return true;
    }
}
