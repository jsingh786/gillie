<?php
/**
 * Created by PhpStorm.
 * User: jsingh7
 * Date: 6/13/2016
 * Time: 7:47 PM
 */
namespace App\Repository;
use App\Entity\Cms;
use Doctrine\ORM\EntityManager;

class cmsRepo
{

    CONST STATUS_INACTIVE = 0;
    CONST STATUS_ACTIVE = 1;

    /**
     * @var string
     */
    private $class = 'App\Entity\Cms';
    /**
     * @var EntityManager
     */
    private $em;


    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Return onject of the row.
     *
     * @param $id
     * @return null|object
     * @author rkaur3
     */
    public function getRowObject($id)
    {
        return $this->em->getRepository($this->class)->findOneBy([
            'id' => $id
        ]);
    }

    /**
     * Get all cms pages according to parameters.
     *
     * @param  array associative $params
     * @param integer $searchParam
     * @param  integer $start [default = 0]
     * @param  integer $length [default =1]
     * @return array
     * @author rkaur3
     * @version 1.0
     * Dated 22 june,2016
     */
    public function getAllCmsPages($params, $searchParam, $start, $length)
    {
        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->select('cms')
            ->from('App\Entity\cms','cms');
        //todo Please make it dynamic, send $search array as parameter. $search will have columns and search keyword.
        //todo for example search[ columns[ col1, col2, col3 ], keyword['abc'] ].
        //todo Same of users module.
        //filtering
        if($searchParam != "")
        {
            $q_1->where('cms.title LIKE :searchParam');
            $q_1->orWhere('cms.description LIKE :searchParam');
            $q_1->orWhere('cms.status LIKE :searchParam');
            $q_1->setParameter('searchParam', '%'.$searchParam.'%');
        }
        //list length
        if( $length )
        {
            $q_1->setFirstResult($start);
            $q_1->setMaxResults($length);
        }

        //Sorting
        if (array_key_exists('order', $params) && !empty($params['order'][0]))
        {
            if (array_key_exists('column', $params['order'][0]))
            {
                $columnIndex = $params['order'][0]['column'];
            }
            if (array_key_exists('dir', $params['order'][0]))
            {
                $columnDir = $params['order'][0]['dir'];
            }
            if(!empty($column = $params['columns'][$columnIndex]['data']))
            {
                $q_1->orderBy("cms.".$column, $columnDir);
            }
        }
        // todo above code is very massey and complicated.
        // todo Can't we do it like this?
        ////Sorting
        /*if( $orderByColumn && $order )
        {
            $q_1->orderBy( 	$orderByColumn, $order );
        }*/

        return $q_1->getQuery()->getResult();
     }

    /**
     * get count of cms pages
     *
     * @return integer count
     * @author rkaur3
     * @version 1.0
     * Dated 22 june,2016
     */
    public function getTotalCount(){
        $cmsPages =$this->em->getRepository($this->class)->findAll();
        return count($cmsPages);
    }

    /**
     * Save/Edit particular cms page.
     *
     * @param associative array $cmsData
     * @return integer $id
     * @author rkaur3
     * @version 1.0
     * Dated 22nd June,2016
     */
    public function savePage(Array $cmsData)
    {
        //update cms record.
        if(isset($cmsData['page_id']))
        {
            $cmsObj = $this->getRowObject($cmsData['page_id']);

            $cmsObj->setTitle($cmsData['title']);
            $cmsObj->setDescription($cmsData['description']);
            $cmsObj->setStatus($cmsData['status']);
            $this->em->persist($cmsObj);
            $this->em->flush();
            return $cmsObj->getId();
        }
        //add new cms page
        else
        {
            $cmsData['slug'] = str_slug($cmsData['title'], '-');
            $cmsObj = new cms;
            $cmsObj->setTitle($cmsData['title']);
            $cmsObj->setDescription($cmsData['description']);
            $cmsObj->setStatus($cmsData['status']);
            $cmsObj->setSlug($cmsData['slug']);
            $this->em->persist($cmsObj);
            $this->em->flush();
            return $cmsObj->getId();
        }
    }

    /**
     * Softdeletes cms page
     *
     * @param integer $id
     * @return bool
     * @author rkaur3
     * @version 1.0
     * Dated 22June,2016
     */
    public function deletePage($id)
    {
        $cms_obj = $this->getRowObject($id);
        if(count($cms_obj) > 0)
        {
            $this->em->remove($cms_obj);
            $this->em->flush();
            return true;
        }
    }
}