<?php
namespace MyBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AvatarRepository extends EntityRepository
{
    public function findOneByUser($user)
    {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
            SELECT a
            FROM MyBundle:Avatar a 
            WHERE a.user = :user
        ');
        $consulta->setParameter('user',$user);
        $consulta->setMaxResults(1);
        return $consulta->getResult();
    }

}