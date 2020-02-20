<?php

require_once _MODELS . "Entity.php";
require_once _MODELS . "Follower.php";
require_once _MODELS . "Post.php";

class NewsModel extends Model 
{
	private $tableName = "post";
	
	public function __construct($fields = array())
    {
        parent::__construct();

        if(!empty($fields))
        {
        	$this->loadUser($fields);
        }
    }

    private function fixMethodName($methodName)
    {
    	$explodedMethod = explode("_", $methodName);
    	$pieces = count($explodedMethod);
    	
    	# return methodName in case there are no underscores
    	// if($pieces > 1) 
    	// {

    	# build methodName replacing underscores and making uppercase the first character of every piece after the first one
    	$methodName = "";
    	for($i = 0; $i < $pieces; $i++)
    	{
    		// $methodName .= $i > 0 ? ucfirst($explodedMethod[$i]) : $explodedMethod[$i];
    		$methodName .= ucfirst($explodedMethod[$i]);
    	}
	    // }

    	return "set" . $methodName;
    }

    public function getNews($entityUid = null)
    {
    	$news = array();
    	# instantate FollowerModel
    	$followerModel = new FollowerModel();

        # instantate PostModel
    	$postModel = new PostModel();

    	if(!is_null($entityUid))
    	{
            $tmpNews = array();
    		# get most recent posts of followers
    		$followers = $followerModel->getFollowers($entityUid);
    		foreach($followers as $follower)
            {
                $tmpNews[$follower['follower']->uid] = $postModel->getPosts(array("entity_uid" => $follower['follower']->uid));
            }
    	}

        foreach($tmpNews as $entityPosts)
        {
            if(!empty($entityPosts))
            {
                foreach($entityPosts as $post)
                {
                    $news[] = $post;
                }
            }
        }
		  
        usort($news, array("NewsModel", "orderByCreated"));
        return $news;
    }

    public function orderByCreated($a, $b)
    {
        return strcmp($a->created, $b->created);
    }
}