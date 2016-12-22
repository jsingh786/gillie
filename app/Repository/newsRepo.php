<?php

/* Created by kaurGuneet on 6/20/2016.*/

namespace App\Repository;
use App\Entity\news;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Config;
class newsRepo
{
    /**
     * @var string
     */
    private $class = 'App\Entity\news';

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
     * todo Desc is required. This function should be available in every repo class.
     * @param $id
     * @return null|object
     */
    public function getRowObject($id)
    {
        return $this->em->getRepository($this->class)->findOneBy([
            'id' => $id
        ]);
    }

    /*
     * Save news from admin and edit it later
     * @author: KaurGuneet
     * @params: Array of news data (image,description,title)
     */

    public function saveNews(array $newsData){
        if($newsData['newsId'] && $newsData['newsId'] != '') {

            $newsEditObj = $this->em->getRepository($this->class)->find($newsData['newsId']);


            $newsEditObj->setDescription($newsData['description']);
            $newsEditObj->setTitle($newsData['title']);
            $this->em->persist($newsEditObj);
            $this->em->flush();
            $id = $newsEditObj->getId();

            if (isset($newsData['file']) && $newsData['file'] != null) {


                $img = preg_replace('#^data:image/\w+;base64,#i', '', $newsData['file']);

                //SET MEDIA PATH
                $newsMediaPath = public_path() . '/backend/images/news';

                //MAKE NEW DIRECTORY
                if (!is_dir($newsMediaPath)) {
                    File::makeDirectory($newsMediaPath, 0777, true);
                }
                //SET IMAGE NAME
                $image_name = "news_" . $id . ".png";
                $newsMediaFullPath = $newsMediaPath . "/" . $image_name;

                $imgdata = base64_decode($img);
                $f = finfo_open();
                $mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);

                if($mime_type == 'image/png' || $mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/gif') {
                    if (File::exists($newsMediaFullPath)) {
                        File::delete($newsMediaFullPath);
                    }
                }else{
                    return 'Invalide-Fomate';
                }
                $success = Image::make(base64_decode($img))->save($newsMediaFullPath);
            }
            return 'Updated';

        }else{
            $newNews = new news();
            $newNews->setDescription($newsData['description']);
            $newNews->setTitle($newsData['title']);

            $this->em->persist($newNews);
            $this->em->flush();
            $id = $newNews->getId();

            if ($newsData['file'] != null) {
                $img = preg_replace('#^data:image/\w+;base64,#i', '', $newsData['file']);
                
                //SET MEDIA PATH
                $newsMediaPath = public_path() . '/backend/images/news';

                //MAKE NEW DIRECTORY
                if (!is_dir($newsMediaPath)) {
                    File::makeDirectory($newsMediaPath, 0777, true);
                }
                //SET IMAGE NAME
                $image_name = "news_" . $id . ".png";
                $newsMediaFullPath = $newsMediaPath . "/" . $image_name;

                $imgdata = base64_decode($img);
                $f = finfo_open();
                $mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);

                if($mime_type == 'image/png' || $mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/gif') {
                    $success = Image::make(base64_decode($img))->save($newsMediaFullPath);
                }else{
                    return 'Invalide-Fomate';
                }
                $newsObj = $this->em->getRepository($this->class)->find($id);

                $newsObj->setImage($image_name);
                $this->em->persist($newsObj);
                $this->em->flush();

                return $newsObj->getId();
            }
        }
    }

    /*
    * get all news from admin
    * @author: KaurGuneet
    * @date:21/06/2016
    * @return:News Data
    */

    public function getAllNews($params,$searchParam,$start,$length){
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('n')
            ->addSelect( "n.id as id" )
            ->addSelect( "n.title as title" )
            ->addSelect( "n.description as description" )
            ->addSelect( "n.created_at as acc_created_at" )
            ->addSelect( "n.updated_at" )
            ->addSelect( "n.deleted_at as acc_deleted_at" )
            ->from( $this->class, 'n' );
        if($searchParam != "")
        {
            $query->where('n.title LIKE :searchParam');
            $query->orWhere('n.description LIKE :searchParam');
            $query->setParameter('searchParam', '%'.$searchParam.'%');
        }
        $query->setFirstResult($start);
        $query->setMaxResults($length);

        if (array_key_exists('order', $params) && !empty($params['order'][0])) {
            if (array_key_exists('column', $params['order'][0])) {
                $columnIndex = $params['order'][0]['column'];
            }
            if (array_key_exists('dir', $params['order'][0])) {
                $columnDir = $params['order'][0]['dir'];
            }

            if(!empty($column = $params['columns'][$columnIndex]['data'])) {
                $query->orderBy("n.".$column, $columnDir);
            }
        }
        return $query->getQuery()->getResult();
    }

    /**
     * @return integer count
     * @author kaurGuneet
     */
    public function getNewsCount(){
        $news =$this->em->getRepository($this->class)->findAll();
        return count($news);
    }

    /**
     * @author KaurGuneet
     * @date 22/06/2016
     */

    public function deleteNews($news_id){
        $news_obj = $this->getRowObject($news_id);
        $pathToFile=public_path().Config::get('gillie.news_image_path').$news_obj->getImage();
        File::delete($pathToFile);//delete image from news folder
        $this->em->remove($news_obj);
        $this->em->flush();
        return true;
    }

    public function getNews($news_id){
        $newsObj = $this->em->getRepository($this->class)->find($news_id);
        return $newsObj;
    }

}
?>