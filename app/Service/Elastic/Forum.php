<?php

namespace App\Service\Elastic;

use App\Service\Elastic\Main as main;


use App\Repository\forumCommentsRepo as forumCommentsRepo;
use App\Repository\forumRepo as forumRepo;
use App\Repository\forumRatingsRepo as forumRatingsRepo;
use Faker\Provider\zh_TW\DateTime;

class Forum
{
    private $forumCommentsRepo;
    private $forumRepo;
    private $forumRatingsRepo;

    public function __construct(forumCommentsRepo $forumCommentsRepo,
                                forumRepo $forumRepo,
                                forumRatingsRepo $forumRatingsRepo,
                                main $main)
    {
        $this->forumCommentsRepo = $forumCommentsRepo;
        $this->forumRepo = $forumRepo;
        $this->forumRatingsRepo = $forumRatingsRepo;
        $this->main = $main;
    }

    /**
     * Add forum data to document in elastic search.
     * It uses forum id fetch all the data from SQL database.
     *
     * @param integer $forumId
     * @param boolean $alreadyExisting [by default false, if true the updates the existing forum.]
     * @author rkaur3
     * @author jsingh7 [Removed extra parameters and provided support for update functionalty also.]
     * @version 1.1
     */
    public function addForum($forumId, $alreadyExisting = false)
    {
        $forumArr = array();
        $forumDetail = $this->forumRepo->getRowObject(['id', $forumId]);
        //To index a document, we need to specify four pieces of information: index, type, id and a document body.
        $forumArr['index'] = Constants::INDEX;
        $forumArr['type'] = Constants::DOC_TYPE_FORUM;
        $forumArr['id'] = $forumId;
//        echo $forumId; die;

        $doc = ['title'             => $forumDetail->getTitle(),
            'date_posted'           => $forumDetail->getCreatedAt()->format('Y-m-d h:i:s'),
            'user_name'             => $forumDetail->getUsers()->getFirstname() . " " . $forumDetail->getUsers()->getLastname(),
            'user_address'          => $forumDetail->getUsers()->getAddress(),
            'forumCommentsCount'    => count($forumDetail->getforumComments()),
            'no_of_views'           => $forumDetail->getNoOfViews(),
            'activity_date'         => $forumDetail->getActivityAt()->format(\DateTime::ATOM),
            'status'                => \App\Repository\forumRepo::ACTIVE,
            'user_id'                    => $forumDetail->getUsers()->getId(),
            'rating_stars'          => $this->forumRepo->getAvgRating($forumId),
            'forum_category_id'     => $forumDetail->getForumCategories()->getId(),
            'trending'              => $forumDetail->getTrending(),
            'description'           => \Helper_common::extractContentFromHTML(
        $forumDetail->getDescription()),
        ];

        if ($alreadyExisting) {
            //If you want to partially update a document (e.g. change an existing field, or add a new one)
            // you can do so by specifying the doc in the body parameter.
            // This will merge the fields in doc with the existing document.
            $forumArr['body'] = ['doc' => $doc];
            $this->main->client->update($forumArr);//updates.
        } else {
            $forumArr['body'] = $doc;
            $this->main->client->index($forumArr); //Adds.
        }
    }

    /**
     * Removes document.
     *
     * This will structure of returned array.
     * <samp>
     * Array
     * (
     *  [found] => 1
     *  [_index] => my_index
     *  [_type] => my_type
     *  [_id] => my_id
     *  [_version] => 2
     *  )
     * <samp>
     *
     * @param $id
     * @return array
     */
    public function delete($id)
    {
        $params = [
            'index' => Constants::INDEX,
            'type' => Constants::DOC_TYPE_FORUM,
            'id' => $id
        ];

        return $this->main->client->delete($params);
    }

    /**
     * Update forum data(trending, comments count) and add forum comments
     *
     * @param integer $forumId
     * @author rkaur3
     * @author jsingh7 [Removed second parameter and changed the name of function]
     * @version 1.1
     *
     */
    public function updateForumComments($forumId, $increaseTrending=true)
    {
        //get comments of forum
        $comments = $this->forumCommentsRepo->get($forumId);
        $commentsArr = array();

        if($comments['forum_comments']) {
            foreach ($comments['forum_comments'] as $key => $cm) {
                $commentsArr[$key]['comments_id'] = $cm->getId();
                $commentsArr[$key]['message'] = $cm->getMessage();
            }
        }
        $forumArr = array();
        $forumArr['index'] = Constants::INDEX;
        $forumArr['type'] = Constants::DOC_TYPE_FORUM;
        $forumArr['id'] = $forumId;
        $forumDetail = $this->forumRepo->getRowObject(['id', $forumId]);
        $forumCommentsCount = count($forumDetail->getforumComments());

        if($increaseTrending) {
            $forumArr['body'] = ['doc' => [
                'trending' => $forumDetail->getTrending(),
                'comments' => $commentsArr,
                'forumCommentsCount' => $forumCommentsCount
            ]];
        } else {
            $forumArr['body'] = ['doc' => [
                'comments' => $commentsArr,
                'forumCommentsCount' => $forumCommentsCount
            ]];
        }

        $response = $this->main->client->update($forumArr);
    }


    public function searchForum($searchText)
    {
        $params = [
            'index' => Constants::INDEX,
            'type' => Constants::DOC_TYPE_FORUM,
            'body' => ['query' => ['multi_match' => [
                'query' => $searchText,
                'type' => 'best_fields',
                'fields' => ['title', 'description', 'comments.message']
                    ]
                ]
            ]
        ];
        $response = $this->main->client->search($params);

        return $response['hits']['hits'];
    }

    public function searchForumTab(array $forumData)
    {
        if (isset($forumData['from'])) {
            $from = $forumData['from'];
        } else {
            $from = 0;
        }

        if (isset($forumData['length'])) {
            $size = $forumData['length'];
        } else {
            $size = 10;
        }


        if ($forumData['sort'] == 'trending') {
            $sort = ["trending" => ["order" => "desc", "unmapped_type" => "integer"]];
        } else if ($forumData['sort'] == 'latest') {
            $sort = ["date_posted" => ["order" => "desc"]];
        }
        //if upper search text is not empty
        //By default latest filter will be passed
        if (@$forumData['search_text'] && @$forumData['search_text'] != "") {

            $query = ["from" => $from,
                "size" => $size,
                "query" => [
                    "constant_score" => [
                        "filter" => [
                            "bool" => [
                                "must" => [
                                    ["match" => ["forum_category_id" => $forumData['by_cat_id']]],
                                    ["bool" => [
                                        "should" => [
                                            ["match" => ["title" => $forumData['search_text']]],
                                            ["match" => ["comments.message" => $forumData['search_text']]],
                                            ["match" => ["description" => $forumData['search_text']]],
                                            ["match_phrase_prefix" => ["title" => $forumData['search_text']]],
                                            ["match_phrase_prefix" => ["comments.message" => $forumData['search_text']]],
                                            ["match_phrase_prefix" => ["description" => $forumData['search_text']]]


                                        ]
                                    ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                "sort" => $sort
            ];

        } //only category and sorting(trending,latest) combination query
        else {
            $query = ["from" => $from,
                "size" => $size,
                'query' => [
                    'bool' => [
                        'must' => [
                            'match' => ['forum_category_id' => $forumData['by_cat_id']]
                        ]
                    ]
                ],
                "sort" => $sort
            ];
        }


        $params = [
            'index' => Constants::INDEX,
            'type' => Constants::DOC_TYPE_FORUM,
            'body' => $query
        ];


        $response = $this->main->client->search($params);

        return array("forums" => $response['hits']['hits'], "total_count" => $response['hits']['total']);
    }

    /**
     * @param integer $id
     */
    public function get($id)
    {
        $params = [
            'index' => Constants::INDEX,
            'type' => Constants::DOC_TYPE_FORUM,
            'id' => $id
        ];
        $response = $this->main->client->get($params);
        print_r($response);
    }
}