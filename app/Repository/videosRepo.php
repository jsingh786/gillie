<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 9/30/2016
 * Time: 5:21 PM
 */

namespace App\Repository;
use App\Entity\users;
use App\Entity\videos;
use Doctrine\ORM\EntityManager;
use Hash;
use Mockery\CountValidator\Exception;


class videosRepo
{


    /**
     * @var string
     */
    private $class = 'App\Entity\videos';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * adminRepo constructor.
     *
     * @param EntityManager $em
     * @author hkaur5
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Returns object of the record on basis of column name - value pair.
     *
     * @param Array $columnNameValuePair ['id', 123]
     * @return null|object
     * @author hkaur5
     * @version 1.1
     *
     */
    public function getRowObject(Array $columnNameValuePair)
    {
        return $this->em->getRepository($this->class)->findOneBy([
            $columnNameValuePair[0] => $columnNameValuePair[1]
        ]);
    }


    /**
     * Saves data to database
     * @param array $params (['name'=>'video name','user_id'=>1])
     * @return video Object
     */

    public function add($params)
    {
        $userRepo = new \App\Repository\usersRepo($this->em);
        $userObj = $userRepo->getRowObject(['id',$params['user_id']]);

        foreach ($params['names'] as $key => $name) {

            $obj = new videos;

            $obj->setVideoName($name);
            $obj->setUsers($userObj);
            $this->em->persist($obj);
            $this->em->flush();
            $video_obj_arr[$key]['name'] = $obj->getVideoName();
            $videoobj_arr[$key]['id'] = $obj->getId();
        }
        return $video_obj_arr;
    }

    /**
     * Get Video object on for given params
     * @param array $params (['id'=>1])
     * @return video Object
     */

    public function getAll($params)
    {
       /* dd($params['name']);
        die;*/
        $userRepo = new \App\Repository\usersRepo($this->em);
        $user_obj = $userRepo->getRowObject(['id',$params['user_id']]);
        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->select('vd')
            ->from('App\Entity\videos','vd');
            if(isset($params['user_id']))
            {
                $q_1->where('vd.users = :user_obj')
                    ->setParameter('user_obj',$user_obj);
            }
            if(isset($params['name']))
            {
                $q_1->andWhere('vd.video_name = :name')
                ->setParameter('name',$params['name'] );
            }
        return $q_1->getQuery()->getResult();

    }


    /**
     * Get video records for given parameters.
     * It also checks if more record present in DB after given
     * offset and limit.
     * @param integer $user_id
     * @param integer $offset(default 0)[optional]
     * @param integer limit (default 10) [optional]
     * @return Array $data (Array of video objects and is_more_records count)
     */

    public function get($user_id, $offset = 0, $limit = 10)
    {

        $data = array();
        $usersEntity = new \App\Repository\usersRepo($this->em);
        $user_obj = $usersEntity->getRowObject(['id',$user_id]);
        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->select('vid')
            ->from('App\Entity\videos','vid')
            ->where('vid.users = :user_obj');
        $q_1->setParameter('user_obj',$user_obj);
        $q_1->setMaxResults($limit);
        $q_1->setFirstResult($offset);
        $q_1->orderBy('vid.id','desc');
        $result = $q_1->getQuery()->getResult();

        if($result)
        {
            $last_record = end($result);
            $q_2 = $qb->select('vd')
                ->from('App\Entity\videos','vd')
                ->where('vd.users = :user_obj')
                ->andWhere('vd.id < ?1');
            $q_2->setParameter('user_obj',$user_obj);
            $q_2->setParameter(1,$last_record->getId());
            $is_more_records = $q_1->getQuery()->getResult();
            $data['videos'] = $result;
            $data['is_more_records'] = count($is_more_records);
        }
        else
        {
            $data['videos'] = 0;
            $data['is_more_records'] = 0;
        }


        return $data;
    }


    /**
     * Removes video object.
     *
     * @param integer $id (video id)
     * @version 1.0
     * @author hkaur5
     * @return boolean True or False if exception occurs
     */
    public function delete($id)
    {
        try
        {
            $video_obj = $this->getRowObject(['id',$id]);
            $this->em->remove( $video_obj );
            $this->em->flush();
            return true;
        }
        catch (Exception $e)
        {
            return false;
        }
    }
}