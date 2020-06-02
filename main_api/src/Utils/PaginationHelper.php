<?php
declare(strict_types=1);

namespace App\Utils;


use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PaginationHelper
{
    public function getPageAndSize(Request $request) : object
    {
        $param = $request->query->get('page');
        $p_number = (isset($param['number']) && $param['number'] > 0) ? (int) $param['number'] : 1;
        $p_size = (isset($param['size']) && $param['size'] > 0) ? (int) $param['size'] : 10;
        return new class($p_number, $p_size)
        {
            private int $number;
            private int $size;

            public function __construct(int $number, int $size)
            {
                $this->number = $number;
                $this->size = $size;
            }

            public function getNumber(): int
            {
                return $this->number;
            }

            public function getSize(): int
            {
                return $this->size;
            }

            public function getFirstResultNumber():int
            {
                return ($this->number - 1) * $this->size;
            }
            public function getMaxResultNumber():int
            {
                return self::getFirstResultNumber() + $this->size;
            }
        };
    }

    public function paginate(Paginator $paginator, NormalizerInterface $normalizer, array $normalize_option = []): array
    {
        $items = [];
        foreach ($paginator as $item)
        {
            $items[] = $normalizer->normalize($item, null, $normalize_option);
        }
        return $items;
    }

}