<?php
namespace App\Repository;
use App\Entity\user_notes;
use Doctrine\ORM\EntityManager;

class userNotesRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\user_notes';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * adminRepo constructor.
     *
     * @param EntityManager $em
     * @author rkaur3
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


    /**
     * Add notes of user into database
     *
     * @param $noteData['note','user_id']
     * @return integer id
     * @author rkaur3
     * @version 1.0
     * Dated 15-sep-2016
     */
    public function add($noteData)
    {
        $noteObj = new user_notes();
        $noteObj->setNotes($noteData['note']);
        $userObj = new \App\Repository\usersRepo($this->em);
        $noteObj->setUsers($userObj->getRowObject(['id',$noteData['user_id']]));
        $this->em->persist($noteObj);
        $this->em->flush();
        return array('note_id'=>$noteObj->getId(),'notes'=>$noteObj->getNotes());
    }

    /**
     * Get notes of particular user
     *
     * @param $user_id
     * @param integer $limit
     * @param integer $offset
     * @return array
     * @version 1.1
     * Dated 13-oct-2016
     */
    public function get($user_id, $limit = null,$offset = null )
    {

        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->select('un.id,un.notes')
            ->from('App\Entity\user_notes','un')
            ->setParameter(1, $user_id)
            ->where('un.users=?1')
            ->orderBy('un.created_at','DESC');
             //List length
        if( $limit )
        {
            $q_1->setFirstResult( $offset );
            $q_1->setMaxResults( $limit );
        }

        $result =  $q_1->getQuery()->getResult();
        if($result)
        {
            $last_record = end($result);
           /* var_dump($last_record);
            die();*/
            $q_2 = $qb->select('usn')
                ->from('App\Entity\user_notes','usn')
                ->where('usn.users=?1')
                ->andWhere('usn.id < ?2')
                ->orderBy('usn.created_at','DESC');
            $q_2->setParameter(1,$user_id);
            $q_2->setParameter(2,$last_record['id']);
            $is_more_records = $q_1->getQuery()->getResult();
            $data['user_notes'] = $result;
            $data['is_more_records'] = count($is_more_records);
        }
        else
        {
            $data['user_notes'] = '';
            $data['is_more_records'] = 0;
        }

        return $data;
        
    }

    /**
     * Get count of notes of particular user
     *
     * @param integer $user_id
     * @return int count
     * @author rkaur3
     * @version 1.0
     * Dated 22-sep-2016
     */

    public function getCount( $user_id )
    {
        $qb = $this->em->createQueryBuilder();
        $q_1 = $qb->select('un')
            ->from('App\Entity\user_notes', 'un')
            ->setParameter('1', $user_id)
            ->where('un.users=?1');


        $notes =  $q_1->getQuery()->getResult();
        return count($notes);
    }
}