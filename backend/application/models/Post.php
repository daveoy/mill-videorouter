<?php

require_once _MODELS . "Entity.php";
require_once _MODELS . "Category.php";

class PostModel extends Model 
{
	private $tableName = "post";
	private $tagTableName = "post_tag";
	protected $uid;
	protected $entity_uid;
	protected $message;
	protected $category_uid;
	protected $hidden = 0;
	protected $deleted = 0;
	protected $created;
	protected $updated = "0000-00-00 00:00:00";
    protected $tags = array();
	
	public function __construct($fields = array())
    {
        parent::__construct();

        if(!empty($fields))
        {
            $this->loadPost($fields);
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

    private function loadPost($fields)
    {
		# build object
    	foreach($fields as $key => $value)
    	{
    		# fix name to retrieve method
    		$method = $this->fixMethodName($key);
			
    		# check if method exists
    		if(method_exists($this, $method))
    		{
    			$this->{$method}($value);
    		}
    	}
    }

    ##
    # Setters
    ##
	public function setUid($uid)
    {
    	$this->uid = $uid;
    }

    public function setEntityUid($entityUid)
    {
    	$this->entity_uid = $entityUid;
    }

    public function setMessage($message)
    {
    	$this->message = $message;
    }

    public function setCategoryUid($categoryUid)
    {
    	$this->category_uid = $categoryUid;
    }

    public function setHidden($hidden)
    {
    	$this->hidden = $hidden;
    }

    public function setDeleted($deleted)
    {
    	$this->deleted = $deleted;
    }

    public function setCreated($created)
    {
    	$this->created = $created;
    }

    public function setUpdated($updated)
    {
    	$this->updated = $updated;
    }

    public function setTags($tags = array())
    {
        $this->tags = $tags;
    }

    ##
    # Getters
    ##

    public function getUid()
    {
    	return $this->uid;
    }

    public function getEntityUid()
    {
    	return $this->entity_uid;
    }

    public function getMessage()
    {
    	return $this->message;
    }
    
    public function getCategoryUid()
    {
    	return $this->category_uid;
    }
    
    public function getHidden()
    {
    	return $this->hidden;
    }
    
    public function getDeleted()
    {
    	return $this->deleted;
    }
    
    public function getCreated()
    {
    	return $this->created;
    }
    
    public function getUpdated()
    {
    	return $this->updated;
    }

    public function getTags()
    {
        return $this->tags;
    }
    
    ##
    # Custom functions
    ##

    public function savePost()
    {
    	# remove db object and uid
    	$object = clone $this;
    	unset($object->db);
    	unset($object->uid);

        # remove tags
        $tags = $object->tags;
        unset($object->tags);

    	# insert new post
    	$id = $this->insert(
    		array(
    			"tableName" => $this->tableName,
    			"object" => $object
    		)
    	);

        # insert new tags
        if(!is_null($id))
        {
            foreach($tags as $tag) {
                $this->insert(
                    array(
                        "tableName" => $this->tagTableName,
                        "object" => array(
                            "post_uid" => $id, 
                            "entity_uid" => $tag
                        )
                    )
                );
            }
        }

    	return $id;
    }

    public function load($uid)
    {
    	$post = $this->select(
    		array(
    			"tableName" => $this->tableName,
    			"criteria" => array("uid" => $uid)
			)
    	);

    	if(isset($post[0])) 
    	{
    		$this->loadPost($post[0]);
    	}
    }

    public function updatePost($postFields)
    {

        # update post object
        if(isset($postFields['entity']))
        {
                $postFields['entity_uid'] = $postFields['entity']['uid'];
                unset($postFields['entity']);
        }

        // if(isset($postFields['category']))
        // {
        //         $postFields['category_uid'] = $postFields['category']['uid'];
        //         unset($postFields['category']);
        // }

        # update post object
        $this->loadPost($postFields);
        
        # remove db object and tags
        $object = clone $this;
        unset($object->db);
        unset($object->tags);

        $return = $this->update(
            array(
                "tableName" => $this->tableName,
                "object" => $object
            )
        );

        if($return > 0) {
                if(!empty($postFields['tags']))
                        $return = $this->updatePostTags($this->getUid(), $postFields['tags']);
        }

        $return = $return > 0 ? true : false;
        
        return $return;
    }

    public function updatePostTags($postUid, $tags)
    {
        $return = array();
        # search tags to delete
        $postTags = $this->select(
            array(
                "tableName" => $this->tagTableName,
                "criteria" => array("post_uid" => $postUid)
            )
        );

        # delete tags
        $this->deletePostTags($postTags);
        
        # insert new tags
        foreach($tags as $entityUid)
        {
            $return = $this->insert(
                array(
                    "tableName" => $this->tagTableName,
                    "object" => array("post_uid" => $postUid, "entity_uid" => $entityUid)
                )
            );
        }

        return $return;   
    }

    public function deletePostTags($postTags)
    {
        foreach($postTags as $tag)
        {
            $this->delete(
                array(
                    "tableName" => $this->tagTableName,
                    "object" => $tag
                )
            );
        }
    }

    public function deletePost()
    {
        # remove db object
        $object = clone $this;
        unset($object->db);

        return $return = $this->delete(
            array(
                "tableName" => $this->tableName,
                "object" => $object
            )
        );
    }

    public function getPost($id)
    {
    	# get user by uid
    	$post = $this->select(
    		array(
    			"tableName" => $this->tableName,
    			// "to_get" => array(),
    			"criteria" => array("uid" => $id)
			)
    	);

        if(isset($post[0]))
    	{
            # get post author
            $entityModel = new EntityModel();
            $post[0]->entity = $entityModel->getEntity(array("entity_uid" => $post[0]->entity_uid));
            
            # get post category
            $categoryModel = new CategoryModel();
            $post[0]->category = $categoryModel->getCategory($post[0]->category_uid);

    		# get tags
    		$tags = $this->select(
    			array(
	    			"tableName" => $this->tagTableName,
	    			"criteria" => array("post_uid" => $post[0]->uid)
    			)
    		);

            # remove unused details
            unset($post[0]->entity_uid);
            unset($post[0]->category_uid);
            
            # retrieve tag informations
            foreach($tags as $tag)
            {
                $post[0]->tags[] = $entityModel->getEntity(array("entity_uid" => $tag->entity_uid));
            }

            // convert created and updated to timestamp
            $post[0]->created = strtotime($post[0]->created); 
            $post[0]->createdString = date("d M Y / H:ia", $post[0]->created);
            $post[0]->updated = strtotime($post[0]->updated);
            $post[0]->updatedString = date("d M Y / H:ia", $post[0]->updated);
    	}

    	return isset($post[0]) ? $post[0] : null;
    }

    public function getPosts($criteria = array())
    {
   //   # get all posts of a given criteria
   //   $posts = $this->select(
   //       array(
   //           "tableName" => $this->tableName,
   //           "criteria" => $criteria,
   //              "limit" => 25
            // )
   //   );

        $query = "SELECT a.*, b.entity_uid AS mention FROM " . $this->tableName . " AS a INNER JOIN " . $this->tagTableName . " AS b ON a.uid = b.post_uid WHERE a.entity_uid = " . $criteria['entity_uid'] . " OR b.entity_uid = " . $criteria['entity_uid'] . " ORDER BY a.created ASC";
        $posts = $this->query($query);

    	# get tags for each post
    	foreach($posts as $key => $post)
    	{
    		$posts[$key] = $this->getPost($post->uid);
    	}

    	return $posts;
    }

}

?>