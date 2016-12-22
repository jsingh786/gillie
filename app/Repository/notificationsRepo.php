<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 11/10/2016
 * Time: 4:55 PM
 */

namespace App\Repository;
use App\Entity\notifications as notifications;
use Doctrine\ORM\EntityManager;
use Mockery\CountValidator\Exception;


class notificationsRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\notifications';

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
     * @param array $params (['for_user_id'=>1,'about_user_id'=>2,'is_read'=>1/0,'type'=>1])
     * @author hkaur5
     * @return album Object
     */         

    public function add($params)
    {

        $usersRepo = new \App\Repository\usersRepo($this->em);
        $notification_typesRepo = new \App\Repository\notification_typesRepo($this->em);

        $for_user_obj = $usersRepo->getRowObject(['id', $params['for_user_id']]);
        $about_user_obj = $usersRepo->getRowObject(['id', $params['about_user_id']]);
        $notification_type_obj = $notification_typesRepo->getRowObject(['id', $params['type_id']]);

        $obj = new notifications();
        $obj->setNotificationType($notification_type_obj);
        $obj->setIsRead($params['is_read']);
        $obj->setForUsersNotification($for_user_obj);
        $obj->setAboutUsersNotification($about_user_obj);
        $this->em->persist($obj);

        $this->em->flush();
        return $obj;
    }

    /**
     * Get notiifcations records on basis of given parameters.
     * It also checks if more record present in DB after given
     * offset and limit.
     * @param array $params ['for_user_id'=>1]
     * @param integer $offset (default 0)[optional]
     * @param integer limit (default 10) [optional]
     * @return Array $data (Array of notifications objects and is_more_records count)
     */

    public function get($params, $offset = 0, $limit = 10)
    {
        $data = array();
        $usersRepo = new \App\Repository\usersRepo($this->em);
        $for_user_obj = $usersRepo->getRowObject(['id', $params['for_user_id']]);
        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->select('notif')
            ->from('App\Entity\notifications', 'notif')
            ->where('notif.forUsersNotification = :for_user_obj');
        $q_1->setParameter('for_user_obj', $for_user_obj);
        $q_1->setMaxResults($limit);
        $q_1->setFirstResult($offset);
        $q_1->orderBy('notif.id', 'desc');
        $result = $q_1->getQuery()->getResult();
        if ($result) {
            $last_record = end($result);
            $q_2 = $qb->select('notifi')
                ->from('App\Entity\notifications', 'notifi')
                ->where('notifi.forUsersNotification = :for_user_obj')
                ->andWhere('notifi.id < ?1');
            $q_2->setParameter('for_user_obj', $for_user_obj);
            $q_2->setParameter(1, $last_record->getId());
            $is_more_records = $q_2->getQuery()->getResult();
            $data['notifications'] = $result;
            $data['is_more_records'] = count($is_more_records);
        } else {
            $data['notifications'] = 0;
            $data['is_more_records'] = 0;
        }


        return $data;
    }



    /**
     * Removes notification.
     *
     * @param integer $id (album id)
     * @version 1.0
     * @author hkaur5
     * @return boolean true or false.
     */
    public function delete($id)
    {
        try{

            $notiification_obj = $this->getRowObject(['id',$id]);
            $this->em->remove( $notiification_obj );
            $this->em->flush();
            return true;
        }
        catch(Exception $e)
        {
            return false;
        }
    }
    
    
}