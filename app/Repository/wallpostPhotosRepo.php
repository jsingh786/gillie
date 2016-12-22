<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 12/15/2016
 * Time: 4:10 PM
 */



namespace App\Repository;
use App\Entity\wallpost_photos as wallpost_photos;
use Doctrine\ORM\EntityManager;


class wallpostPhotosRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\wallpost_photos';

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
     * Saves photos data to database.
     * @param integer $wallpost_id
     * @param array $photo_name_arr (array(1=>array('name'=>"abc", 'id'=>1)))
     * @author hkaur5
     * @return  Array name and id of photos saved.
     */

    public function add($wallpost_id, $photo_name_arr)
    {
        $photo_obj_arr = array();
        $wallpostEntity = new \App\Repository\wallpostRepo($this->em);

        $wallpost_obj = $wallpostEntity->getRowObject(['id', $wallpost_id]);
        foreach ($photo_name_arr as $key => $photo_name) {

            $obj = new wallpost_photos;
            $obj->setPhotosWallpost($wallpost_obj);
            $obj->setName($photo_name);
            $this->em->persist($obj);
            $this->em->flush();
            $photo_obj_arr[$key]['name'] = $obj->getName();
            $photo_obj_arr[$key]['id'] = $obj->getId();
        }
        return $photo_obj_arr;
    }

    /**
     * get Wallpost_photos using parameters.
     * @author hkaur5
     * @param array $params['wallpost_id'=>1]
     * @return object wallpost_photos
     */
    public function get($params)
    {
       
        $wallpostRepo= new \App\Repository\wallpostRepo($this->em);
        $wallpost_obj = $wallpostRepo->getRowObject(['id',$params['wallpost_id']]);
        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->select('wp_ph')
            ->from('App\Entity\wallpost_photos','wp_ph')
            ->where('wp_ph.photosWallpost = :wp_obj');
        $q_1->setParameter('wp_obj',$wallpost_obj);
        return $q_1->getQuery()->getResult();

    }


}