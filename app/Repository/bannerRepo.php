<?php

/* Created by kaurGuneet on 6/23/2016.*/

namespace App\Repository;
use App\Entity\banner;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Config;
class bannerRepo
{

    CONST STATUS_INACTIVE = 0;
    CONST STATUS_ACTIVE = 1;

    /**
     * @var string
     */
    private $class = 'App\Entity\banner';

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

    //todo This method is not acceptable as it does not satisfies generic requirements.
    //todo Naming is not as per defined standards, lack of documentation.
    /**
     * Returns all news.
     *
     * @param $params
     * @param $searchParam [optional]
     * @param $start [optional]
     * @param $length [optional]
     *
     * @author KaurGuneet
     *
     * @return array
     * @version 1.0
     */
    public function getAllBanner($params, $searchParam, $start, $length){
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('b')
            ->addSelect( "b.id as id" )
            ->addSelect( "b.title as title" )
            ->addSelect( "b.description as description" )
            ->addSelect( "b.status as status" )
            ->addSelect( "b.created_at as acc_created_at" )
            ->addSelect( "b.deleted_at as acc_deleted_at" )
            ->addSelect( "b.updated_at" )
            ->from( $this->class, 'b' );
        if($searchParam != "")
        {
            $query->where('b.title LIKE :searchParam');
            $query->orWhere('b.description LIKE :searchParam');
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
                $query->orderBy("b.".$column, $columnDir);
            }
        }

        return $query->getQuery()->getResult();
    }



    /**
     * Returns banners as per parameters.
     *
     * @param array|null $limitNOffset ['limit'=>100, 'offset'=>0]
     * @param array|null $order ['orderColumn'=>'id', 'order'=>'desc']
     * @param array|null $filter ['columns'=>['id','name', 'email'], 'keywords'=>['abc', 'xyz', 'pqr']]
     * @version 1.0
     * @author jsingh7
     */
    public function get(array $limitNOffset = null,
                        array $order = null,
                        array $filter = null)
    {
        $qb     = $this->em->createQueryBuilder();
        $alias  = 'bnr';
        $query  = $qb->select($alias)
            ->from( $this->class, $alias );

        //List length
        if($limitNOffset) {
            $query->setFirstResult($limitNOffset['offset'])
                ->setMaxResults($limitNOffset['limit']);
        }

        //Sorting
        if($order) {
            $query->orderBy($alias.'.'.$order['column'], $order['order']);
        }

        //Filtering
        if($filter) {
            if($filter['columns'] && $filter['keywords'] && is_array($filter['columns']) && is_array($filter['keywords'])) {
                foreach ($filter['columns'] as $key_1 => $filter_column) {
                    foreach ($filter['keywords'] as $key_2 => $filter_keyword) {
                        $query->orWhere($filter_column . ' LIKE :word_' . $key_2)
                            ->setParameter('word_' . $key_2, '%' . $filter_keyword . '%');
                    }
                }
            }
        }

        return $query->getQuery()->getResult();
    }

    /**
     * Saves banner data to database.
     *
     * @param array $bannerData ['image'=>'xyz.png', 'title'=>'abc', 'description'=>'', 'status'=>1 ]
     * @author jsingh7
     * @return id
     * @version 1.0
     */
    public function save(array $bannerData)
    {
        $bannerObj = new banner();
        $bannerObj->setImage($bannerData['image']);
        $bannerObj->setDescription($bannerData['description']);
        $bannerObj->setTitle($bannerData['title']);
        $bannerObj->setStatus($bannerData['status']);
        $this->em->persist($bannerObj);
        $this->em->flush();
        return $bannerObj->getId();
    }

    /**
     * Updates banner data to database. If any key in $bannerData array
     * is missing then it will not update that.
     *
     * @param array $bannerData ['id'=>123, 'image'=>'xyz.png', 'title'=>'abc', 'description'=>'', 'status'=>1 ]
     * @author jsingh7
     * @version 1.0
     * @return void
     */
    public function update(array $bannerData)
    {
        $bannerObj = $this->getRowObject(['id'=>$bannerData['id']]);
        if (isset($bannerData['image']))        { $bannerObj->setImage($bannerData['image']); }
        if (isset($bannerData['description']))  { $bannerObj->setDescription($bannerData['description']); }
        if (isset($bannerData['title']))        { $bannerObj->setTitle($bannerData['title']); }
        if (isset($bannerData['status']))       { $bannerObj->setStatus($bannerData['status']); }
        $this->em->persist($bannerObj);
        $this->em->flush();
    }


    /*
     * Save banner from admin and edit it later
     * @author: KaurGuneet
     * @params: Array of banner data (image,description,title)
     */
    public function saveBanner(array $bannerData){
        if(($bannerData['bannerId']) != "") {
            //Todo Use update method of this class for following.
            $bannerEditObj = $this->em->getRepository($this->class)->find($bannerData['bannerId']);
            $bannerEditObj->setDescription($bannerData['description']);
            $bannerEditObj->setTitle($bannerData['title']);
            $bannerEditObj->setStatus($bannerData['status']);
            $this->em->persist($bannerEditObj);
            $this->em->flush();
            $id = $bannerEditObj->getId();

            //todo Shift following code into controller, this code does not belongs to data mapping class.
            if (isset($bannerData['file']) && $bannerData['file'] != null ) {

                $img = preg_replace('#^data:image/\w+;base64,#i', '', $bannerData['file']);

                //SET MEDIA PATH
                $bannerMediaPath = public_path() . '/backend/images/banners';

                //MAKE NEW DIRECTORY
                if (!is_dir($bannerMediaPath)) {
                    File::makeDirectory($bannerMediaPath, 0777, true);
                }
                //SET IMAGE NAME

                $image_name = "banner_" . $id . ".png";

                $imgdata = base64_decode($img);
                $f = finfo_open();
                $mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);

                $bannerMediaFullPath = $bannerMediaPath . "/" . $image_name;

                if($mime_type == 'image/png' || $mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/gif'){
                    if(File::exists($bannerMediaFullPath))
                    {
                        File::delete($bannerMediaFullPath);
                    }
                }else{
                    return 'Invalide-Fomate';
                }
                $success = Image::make(base64_decode($img))->save($bannerMediaFullPath);
            }
            return 'Updated';

        }else{

            //Todo Use save method of this class for following.
            $newBanner = new banner();
            $newBanner->setDescription($bannerData['description']);
            $newBanner->setTitle($bannerData['title']);
            $newBanner->setStatus($bannerData['status']);

            $this->em->persist($newBanner);
            $this->em->flush();
            $id = $newBanner->getId();

            //todo Shift this code to controller.
            if ($bannerData['file'] != null) {

                $img = preg_replace('#^data:image/\w+;base64,#i', '', $bannerData['file']);

                //SET MEDIA PATH
                $bannerMediaPath = public_path() . '/backend/images/banners';

                //MAKE NEW DIRECTORY
                if (!is_dir($bannerMediaPath)) {
                    File::makeDirectory($bannerMediaPath, 0777, true);
                }
                //SET IMAGE NAME
                $image_name = "banner_" . $id . ".png";
                $bannerMediaFullPath = $bannerMediaPath . "/" . $image_name;

                $imgdata = base64_decode($img);
                $f = finfo_open();
                $mime_type = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);

                if($mime_type == 'image/png' || $mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/gif') {
                    $success = Image::make(base64_decode($img))->save($bannerMediaFullPath);
                }else{
                    return 'Invalide-Fomate';
                }
                $bannerObj = $this->em->getRepository($this->class)->find($id);

                $bannerObj->setImage($image_name);
                $this->em->persist($bannerObj);
                $this->em->flush();

                return $bannerObj->getId();
            }
        }
    }


    //todo Remove this function, use getRowObject of this class.
    public function getBanner($banner_id){
        $bannerObj = $this->em->getRepository($this->class)->find($banner_id);
        return $bannerObj;
    }

    /**
     * @return integer count
     * @author kaurGuneet
     */
    //todo remove this function, instead use get function of this class.
    //todo If you want to use count function of PHP, do it in controller class.
    public function getBannerCount(){
        $banner =$this->em->getRepository($this->class)->findAll();
        return count($banner);
    }

    /**
     * Removes record for banner from database.
     *
     * @param integer $id
     * @return bool
     * @author jsingh7
     */
    public function delete($banner_id) {
        $banner_obj = $this->getRowObject($banner_id);
        $this->em->remove($banner_obj);
        $this->em->flush();
        return true;
    }

    /**
     * @author KaurGuneet
     * @date 24/06/2016
     */
    //todo use delete function of this class instead.
    public function deleteBanner($banner_id){
        $banner_obj = $this->getRowObject($banner_id);
        //todo Mapping class is for database related functionality only. so shift below two lines of code to controller.
        //todo do not use public_path() function. Use contants only.
        // todo there is no need to make Config::get('gillie.banner_image_path'), use Config::get('gillie.image_path')
        $pathToFile=public_path().Config::get('gillie.banner_image_path').$banner_obj->getImage();
        File::delete($pathToFile);//delete image from news folder
        $this->em->remove($banner_obj);
        $this->em->flush();
        return true;
    }

}