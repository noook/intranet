<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

use Doctrine\ORM\Query\ResultSetMapping;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function updateRoles($data)
    {
        $class = User::class;
        $ids = implode(', ', array_keys($data));
        $em = $this->getEntityManager();
        $query = $em->createQuery("SELECT u FROM $class u WHERE u.id IN($ids)");
        $iterableResult = $query->iterate();

        $batchSize = 20;
        $i = 0;

        foreach ($iterableResult as $row) {
            $user = $row[0];
            $user->setRoles([$data[$user->getId()]]);
            if (($i % $batchSize) === 0) {
                $em->flush();
                $em->clear();
            }
            ++$i;
        }
        $em->flush();
    }

    private function findTeachersIds()
    {
        $userMapping = new ResultSetMapping;
        $userMapping->addEntityResult(User::class, 'u');
        $userMapping->addFieldResult('u', 'id', 'id');
        $query = "SELECT id FROM \"user\" WHERE '\"ROLE_TEACHER\"' = ANY (ARRAY(select * from json_array_elements(roles))::text[]);";
        $em = $this->getEntityManager();
        $results = $em->createNativeQuery($query, $userMapping)->getResult();
        $ids = array_map(function ($user) {
            return $user->getId();
        }, $results);
        $em->clear();
        
        return $ids;
    }

    public function findTeachers()
    {
        $ids = array_values($this->findTeachersIds());
        return $this->findBy(['id' => $ids], ['email' => 'ASC']);
    }

    private function findStudentsIds()
    {
        $userMapping = new ResultSetMapping;
        $userMapping->addEntityResult(User::class, 'u');
        $userMapping->addFieldResult('u', 'id', 'id');
        $query = "SELECT id FROM \"user\" WHERE '\"ROLE_STUDENT\"' = ANY (ARRAY(select * from json_array_elements(roles))::text[]);";
        $em = $this->getEntityManager();
        $results = $em->createNativeQuery($query, $userMapping)->getResult();
        $ids = array_map(function ($user) {
            return $user->getId();
        }, $results);
        $em->clear();

        return $ids;
    }

    public function findLike($query, $role = 'all')
    {
        $role = "ROLE_" . strtoupper($role);
        $query = '%' . implode('%', str_split(strtolower($query))) . '%';

        $userMapping = new ResultSetMapping;
        $userMapping->addEntityResult(User::class, 'u');
        $userMapping->addFieldResult('u', 'id', 'id');

        $likePart = "unaccent(lower(first_name || ' ' || last_name)) LIKE '$query' LIMIT 3;";

        if ($role == 'ROLE_ALL') {
            $query = "SELECT id FROM \"user\" WHERE $likePart";
        } else {
            $query = "SELECT id FROM \"user\" WHERE '\"$role\"' = ANY (ARRAY(select * from json_array_elements(roles))::text[]) AND $likePart";
        }

        $em = $this->getEntityManager();
        $results = $em->createNativeQuery($query, $userMapping)->getResult();
        $ids = array_map(function ($user) {
            return $user->getId();
        }, $results);
        $em->clear();
        
        return $this->findBy(['id' => $ids]);
    }

    public function findStudents()
    {
        $ids = array_values($this->findStudentsIds());
        return $this->findBy(['id' => $ids], ['email' => 'ASC']);
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
