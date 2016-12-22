<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 9/15/2016
 * Time: 10:37 AM
 */

namespace App\Repository;
use App\Entity\album as album;
use Doctrine\ORM\EntityManager;


class albumRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\album';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * userActivitiesRepo constructor.
     *
     * @param EntityManager $em
     * @author hkaur5
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Return object of the row.
     *
     * @param array $columnNameValuePair [ column name and its value. ]
     * @return null|object
     * @author hkaur5
     */
    public function getRowObject(Array $columnNameValuePair)
    {
        return $this->em->getRepository($this->class)->findOneBy([
            $columnNameValuePair[0] => $columnNameValuePair[1]
        ]);
    }

    /**
     * Saves data to database.
     * @param array $params (['name'=>'abc','user_id'=>1])
     * name is display name of album and unique is created
     * within this function.
     * @return album Object
     */

    public function add($params)
    {
        $name = str_replace(' ','_' ,$params['name'] );
        $name  = $name.'_'.time();
        $usersEntity = new \App\Repository\usersRepo($this->em);
        
        $user_obj = $usersEntity->getRowObject(['id',$params['user_id']]);
        $obj = new album;
        $obj->setusers($user_obj);
        $obj->setName($name);
        $obj->setDisplay_name($params['name']);
        $this->em->persist($obj);

        $this->em->flush();
        return $obj;
    }

    /**
     * Get album records on basis of given parameters.
     * It also checks if more record present in DB after given
     * offset and limit.
     * @param integer $user_id
     * @param integer $offset(default 0)[optional]
     * @param integer limit (default 10) [optional]
     * @return Array $data (Array of albums objects and is_more_records count)
     */

    public function get($user_id, $offset = 0, $limit = 10)
    {
        $data = array();
        $usersEntity = new \App\Repository\usersRepo($this->em);
        $user_obj = $usersEntity->getRowObject(['id',$user_id]);
        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->select('alb')
            ->from('App\Entity\album','alb')
            ->where('alb.users = :user_obj');
        $q_1->setParameter('user_obj',$user_obj);
        $q_1->setMaxResults($limit);
        $q_1->setFirstResult($offset);
        $q_1->orderBy('alb.id','desc');
        $result = $q_1->getQuery()->getResult();

        if($result)
        {
            $last_record = end($result);
            $q_2 = $qb->select('albm')
                ->from('App\Entity\album','albm')
                ->where('albm.users = :user_obj')
                ->andWhere('albm.id < ?1');
            $q_2->setParameter('user_obj',$user_obj);
            $q_2->setParameter(1,$last_record->getId());
            $is_more_records = $q_1->getQuery()->getResult();
            $data['albums'] = $result;
            $data['is_more_records'] = count($is_more_records);
        }
        else
        {
            $data['albums'] = 0;
            $data['is_more_records'] = 0;
        }


        return $data;
    }


    /**
     * Removes album
     * and rest of the work managed by
     * cascade statements. Such as deleting
     * photos.
     *
     * @param integer $id (album id)
     * @version 1.0
     * @author hkaur5
     * @return void
     */
    public function delete($id)
    {
        $album_obj = $this->getRowObject(['id',$id]);
        //dd($album_obj);
        $this->em->remove( $album_obj );

        $this->em->flush();
    }

    /**
     * Update album.
     * @author hkaur5
     * @param array $params ( array('id'=>1,'name'=>'abc') )
     * @return int|null|Album object
     */
    public function update($params)
    {
        
        if($params['id'])
        {
            $album_obj = $this->getRowObject(['id',$params['id']]);
            $album_obj->setDisplay_name($params['display_name']);
            $this->em->persist($album_obj);
            $this->em->flush();
            return $album_obj;
        }
        else
        {
            return 0;
        }

    }
}

