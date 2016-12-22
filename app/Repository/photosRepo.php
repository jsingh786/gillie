<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 9/15/2016
 * Time: 10:41 AM
 */


namespace App\Repository;
use App\Entity\photos as photos;
use Doctrine\ORM\EntityManager;


class photosRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\photos';

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
     * Saves data to database
     * @param integer $album_id
     * @param array $photo_name_arr (array(1=>"abc", 2=>"pqr"))
     * @author hkaur5
     * @return  Array name of photos saved.
     */

    public function add($album_id, $photo_name_arr)
    {
        $photo_obj_arr = array();
        $albumsEntity = new \App\Repository\albumRepo($this->em);

        $album_obj = $albumsEntity->getRowObject(['id', $album_id]);
        foreach ($photo_name_arr as $key => $photo_name) {

            $obj = new photos;
            $obj->setAlbums($album_obj);
            $obj->setImage($photo_name);
            $this->em->persist($obj);
            $this->em->flush();
            $photo_obj_arr[$key]['name'] = $obj->getImage();
            $photo_obj_arr[$key]['id'] = $obj->getId();
        }
        return $photo_obj_arr;
    }

    /**
     * Get all photos of given album.
     * @param integer $album_id
     * @author hkaur5
     * @return array of photo objects
     */

    public function getAll($album_id)
    {
        $result = '';
        $albumEntity = new \App\Repository\albumRepo($this->em);
        $album_obj = $albumEntity->getRowObject(['id', $album_id]);
        if($album_obj)
        {
            $qb = $this->em->createQueryBuilder();
            $q_1 = $qb->select('ph')
                ->from('App\Entity\photos', 'ph')
                ->where('ph.albums = :album_obj');
            $q_1->setParameter('album_obj', $album_obj);
            $result = $q_1->getQuery()->getResult();
            return $result;
        }
        else
        {
            return false;
        }


    }

    /**
     * Get photos for given params
     * @param integer $album_id
     * @param integer $offset [optional]
     * @param  integer $limit [ optional ]
     * @author hkaur5
     * @return Array ( array consisting of photos object and is_more_record count )
     */
    public function get($album_id, $offset = 0, $limit = 10)
    {
        $data = array();
        $albumEntity = new \App\Repository\albumRepo($this->em);
        $album_obj = $albumEntity->getRowObject(['id', $album_id]);
        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->select('ph')
            ->from('App\Entity\photos','ph')
            ->where('ph.albums = :album_obj');
        $q_1->setParameter('album_obj',$album_id);
        $q_1->setMaxResults($limit);
        $q_1->setFirstResult($offset);
        $q_1->orderBy('ph.id','desc');
        $result = $q_1->getQuery()->getResult();
        if($result)
        {
            $last_record = end($result);
            $q_2 = $qb->select('phts')
                ->from('App\Entity\photos','phts')
                ->where('phts.albums = :album_obj')
                ->andWhere('phts.id < ?1');
            $q_2->setParameter('album_obj',$album_obj);
            $q_2->setParameter(1,$last_record->getId());
            $is_more_records = $q_1->getQuery()->getResult();
            $data['photos'] = $result;
            if($is_more_records)
            {
                $data['is_more_records'] = count($is_more_records);

            }
            else
            {
                $data['is_more_records'] = 0;
            }
        }
        else
        {
            $data['photos'] = false;
            $data['is_more_records'] = false;
        }

        return $data;
    }

    /**
     * Removes photo from database.
     * @param integer $id (photo id)
     * @version 1.0
     * @author hkaur5
     * @return void
     */
    public function delete($id)
    {
        $album_obj = $this->getRowObject(['id',$id]);
        $this->em->remove( $album_obj );
        $this->em->flush();
    }
}