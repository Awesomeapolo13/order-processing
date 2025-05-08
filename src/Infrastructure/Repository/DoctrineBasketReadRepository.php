<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\ReadModel\ShortBasketReadModelInterface;
use App\Domain\Repository\BasketReadRepositoryInterface;
use App\Domain\ValueObject\Region;
use App\Infrastructure\ReadModel\Hydrator\ShortBasketHydrator;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineBasketReadRepository extends AbstractDBALReadRepository implements BasketReadRepositoryInterface
{
    public function __construct(
        private readonly ShortBasketHydrator $shortBasketHydrator,
        EntityManagerInterface $entityManager,
    ) {
        parent::__construct($entityManager);
    }

    /**
     * @throws Exception
     * @throws \DateMalformedStringException
     */
    public function findShortBasket(int $userId, Region $region): ?ShortBasketReadModelInterface
    {
        $connection = $this->getConnection();

        $query = $connection
            ->createQueryBuilder()
            ->select([
                'b.id AS basket_id',
                'b.is_express AS is_express',
                'b.is_delivery AS is_delivery',
                'b.has_alco AS has_alco',
                'b.order_date AS order_date',
                'b.total_discount_cost AS total_discount_cost',
                'bi.id AS basket_item_id',
                'bi.sup_code AS sup_code',
                'bi.total_cost AS total_cost',
                'bi.total_discount_cost AS total_discount_cost',
            ])
            ->from('basket', 'b')
            ->leftJoin('b', 'basket_item', 'bi', 'b.id = bi.basket_id')
            ->where('bi.user_id = :userId')
            ->andWhere('bi.region = :region')
            ->getSQL()
        ;

        $result = $connection->fetchAllAssociative(
            $query,
            [
                'userId' => $userId,
                'region' => $region->getRegionCode(),
            ],
            [
                'userId' => ParameterType::INTEGER,
                'region' => ParameterType::INTEGER,
            ]
        );

        return $this->shortBasketHydrator->hydrate($result);
    }
}
