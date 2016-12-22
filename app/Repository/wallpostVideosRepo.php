<?php
/**
 * Created by PhpStorm.
 * User: hkaur5
 * Date: 12/15/2016
 * Time: 4:13 PM
 */

namespace App\Repository;
use App\Entity\wallpost_videos as wallpost_videos;
use Doctrine\ORM\EntityManager;


class wallpostVideosRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\wallpost_videos';

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
     * Saves videos data to database.
     * @param array $param ['wallpost_id'=>1,'name'=>'abc.mp4']
     * @author hkaur5
     * @return object wallpost_videos
     */
    public function add($params)
    {
        $wallpostEntity = new \App\Repository\wallpostRepo($this->em);
        $wallpost_obj = $wallpostEntity->getRowObject(['id', $params['wallpost_id']]);
        //Todo: Change $obj to sensable name.
        $obj = new wallpost_videos;
        $obj->setVideosWallpost($wallpost_obj);
        $obj->setName($params['name']);
        $this->em->persist($obj);
        $this->em->flush();
        return $obj;
    }

}