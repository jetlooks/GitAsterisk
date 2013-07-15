<?php

/**
 * MySQL data mapper to get and set data to/from local database.
 *
 * Author: Igor Khromov
 * Date: 12.07.13
 */

class Git_data_mapper{

    private $_CI;

    public function __construct(){
        $this->_CI =& get_instance();
    }

    public function setLike($repo_name, $owner, $liker_ip, $opinion){

        //False if opinion absent in db, or 0/1 if opinion is in db
        $liked = $this->isLiked($repo_name, $owner, $liker_ip);

        //Compare user like status with set before in db
        if($liked !== FALSE){
             //Trying to change like
             $this->_updateLike($repo_name, $owner, $liker_ip, $opinion);
             return "changed";
        }
        //No info in db
        else{
            //Trying to add like
            $this->_addLike($repo_name, $owner, $liker_ip, $opinion);
            return "added";
        }
    }

    //Returns likes and unlikes for special repo
    public function likesUnlikesCount($repo_name, $owner){

        $query_string = "SELECT  SUM(opinion = 1) AS likes,
		                 SUM(opinion = 0) AS unlikes
                         FROM   likes, likers_ips, git_owners, git_repositories
                         WHERE  likes.liker_ip_id  = likers_ips.id
	                     AND likes.git_repo_id  = git_repositories.id
                         AND likes.git_owner_id = git_owners.id
	                     AND git_owners.name	= ".$this->_CI->db->escape($owner)."
	                     AND git_repositories.title = BINARY ".$this->_CI->db->escape($repo_name);

        $query_result = $this->_CI->db->query($query_string);
        if ($query_result->num_rows() > 0)
        {
            $likes_arr = $query_result->row_array();

            //If no values for repo in db
            if($likes_arr['likes'] === NULL){
                return array('likes' => '0', 'unlikes' => '0');
            }
            //Returns array with likes/unlikes count
            else {
                return $query_result->row_array();
            }
        }
    }


    public function isLiked($repo_name, $owner, $liker_ip){

        $query_string = "SELECT opinion
                         FROM   git_repositories, git_owners, likers_ips, likes
                         WHERE  git_repositories.id = likes.git_repo_id
	                     AND git_owners.id = likes.git_owner_id
	                     AND likers_ips.id = likes.liker_ip_id
	                     AND BINARY git_repositories.title = ".$this->_CI->db->escape($repo_name)."
	                     AND git_owners.name = ".$this->_CI->db->escape($owner)."
	                     AND likers_ips.liker_ip = ".$this->_CI->db->escape($liker_ip);

        $query = $this->_CI->db->query($query_string);

        //if like present
        if ($query->num_rows() > 0)
        {
            $row = $query->row();
            return $row->opinion;
        }
        //No results found
        else
            return FALSE;
    }

    //Add like to bd if it is not there yet
    private function _addLike($repo_name, $owner, $liker_ip, $opinion){

        $query_string = "CALL addLike(".$this->_CI->db->escape($owner).", ".$this->_CI->db->escape($repo_name).", ".$this->_CI->db->escape($liker_ip).", ".$opinion.")";

        //Successful if TRUE
        $this->_CI->db->query($query_string);
    }

    //Updates set like
    private function _updateLike($repo_name, $owner, $liker_ip, $opinion){

        $query_string = "UPDATE likes SET opinion = ".$opinion."
                            WHERE git_owner_id    = (SELECT id FROM git_owners WHERE name = ".$this->_CI->db->escape($owner).")
                                AND git_repo_id   = (SELECT id FROM git_repositories WHERE BINARY title = ".$this->_CI->db->escape($repo_name).")
                                AND liker_ip_id   = (SELECT id FROM likers_ips WHERE liker_ip = ".$this->_CI->db->escape($liker_ip).")";

        //Result TRUE/FALSE
        $this->_CI->db->query($query_string);
    }

    //Gets an user status for special IP Address
    public function isUserLiked($user_login, $liker_ip){

        $query_string = "SELECT opinion
                         FROM user_likes, git_owners, likers_ips
                         WHERE
                         user_likes.owner_id = git_owners.id
                         AND user_likes.liker_ip_id = likers_ips.id
                         AND git_owners.name = ".$this->_CI->db->escape($user_login)."
                         AND liker_ip = ".$this->_CI->db->escape($liker_ip);

        $query = $this->_CI->db->query($query_string);

        //if like present
        if ($query->num_rows() > 0)
        {
            $row = $query->row();
            return $row->opinion;
        }
        //No results found
        else
            return FALSE;
    }


    //Get  user likes
    public function userLikesUnlikesCount($user_login){

        $query_string = "SELECT  SUM(opinion = 1) AS likes,
		                 SUM(opinion = 0) AS unlikes
                         FROM   user_likes, likers_ips, git_owners
                         WHERE  user_likes.liker_ip_id  = likers_ips.id
                         AND user_likes.owner_id = git_owners.id
	                     AND git_owners.name	= ".$this->_CI->db->escape($user_login);

        $query_result = $this->_CI->db->query($query_string);
        if ($query_result->num_rows() > 0)
        {
            $likes_arr = $query_result->row_array();

            //If no values for repo in db
            if($likes_arr['likes'] === NULL){
                return array('likes' => '0', 'unlikes' => '0');
            }
            //Returns array with likes/unlikes count
            else {
                return $query_result->row_array();
            }
        }
    }

    public function addUserLike($user_login, $liker_ip, $opinion){
        $query_string = "CALL addUserLike(".$this->_CI->db->escape($user_login).", ".$this->_CI->db->escape($liker_ip).", ".$opinion.")";

        //Successful if TRUE
        $this->_CI->db->query($query_string);
    }

    public function updateUserLike($user_login, $liker_ip, $opinion){
        $query_string = "UPDATE user_likes
                         SET opinion = ".$opinion."
                         WHERE owner_id     = (SELECT id FROM git_owners WHERE git_owners.name = ".$this->_CI->db->escape($user_login).")
                         AND liker_ip_id    = (SELECT id FROM likers_ips WHERE liker_ip = ".$this->_CI->db->escape($liker_ip).")";

        //Result TRUE/FALSE
        $this->_CI->db->query($query_string);
    }

}