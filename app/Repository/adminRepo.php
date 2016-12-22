<?php
/**
 * Created by PhpStorm.
 * User: jsingh7
 * Date: 6/14/2016
 * Time: 5:26 PM
 */
namespace App\Repository;
use App\Entity\admin;
use Doctrine\ORM\EntityManager;

class adminRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\admin';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * adminRepo constructor.
     *
     * @param EntityManager $em
     * @author kaurGuneet
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Get User by email
     *
     * @param $email
     * @return null/object
     * @author kaurGuneet
     * @deprecated This method is deprecated, Use getRowObject
     */
    public function getAdminByEmail($email)
    {
        return $this->em->getRepository($this->class)->findOneBy([
            'email' => $email
        ]);
    }

    /**
     * Return object of the row.
     *
     * @param array $columnNameValuePair [ column name and its value. ]
     * @return null|object
     * @author jsingh7
     */
    public function getRowObject(Array $columnNameValuePair)
    {
        return $this->em->getRepository($this->class)->findOneBy([
            $columnNameValuePair[0] => $columnNameValuePair[1]
        ]);
    }

    /*
     * Edit User Profile
     * todo Add author, date, params, version, return type.
     */
    public function editProfile($firstname,$lastname,$email){
        
        $qb = $this->em->createQueryBuilder();
        $query=$qb->update($this->class, 'a') //todo Alias name should be readable. for e.g. adm
            ->set('a.firstname', '?1')
            ->set('a.lastname', '?2')
            ->where('a.email = ?3')
            ->setParameter(1, $firstname)
            ->setParameter(2, $lastname)
            ->setParameter(3, $email )
            ->getQuery();
        $result = $query->execute();
        return $result;
    }

    /*
     *  Change User Password
     * todo Add author, date, params, version, return type.
     */

    public function changePassword($password,$id){

        $qb = $this->em->createQueryBuilder();
        $query=$qb->update($this->class,'a')
            ->set('a.password', '?1')
            ->where('a.id = ?2')
            ->setParameter(1, $password)
            ->setParameter(2, $id)
            ->getQuery();
        $result = $query->execute();
        return $result;
    }

}