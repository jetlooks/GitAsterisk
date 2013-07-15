<?php
/**
 * Class for getting data from Github.com using curl.
 * @author Igor Khromov
 * @date 10.07.13
 */

class Git_manager{

    private $_github_api_url;
    private $_curl_desc;

    public function __construct($github_api_url){
        //Sets the basic url for curl query
        $this->_github_api_url = $github_api_url;

        //Initializes a cURC descriptor
        $this->_curl_desc = curl_init();
        curl_setopt($this->_curl_desc, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->_curl_desc, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($this->_curl_desc, CURLOPT_USERPWD, GITHUB_USER_NAME . ":" . GITHUB_PASSWORD);
    }

    public function searchRepos($keyword, $language = '', $sort = 'stars', $order = 'asc'){

        $query_url = $this->_github_api_url . 'legacy/repos/search/:';
        if (is_string($keyword)){
            $query_url .=  trim(strip_tags($keyword)).'?';
        }
        else return -1;


        if(is_string($language) && strlen($language) != 0  ){
            $query_url .= 'language='.trim(strip_tags($language));
        }

        if(is_string($sort) && ( $sort == 'stars' | $sort == 'forks' | $sort == 'updated' )){
            if(strlen($language) == 0)
                $query_url .= 'sort='.$sort;
            else
                $query_url .= '&sort='.$sort;
        }
        else return -1;

        if(is_string($order)  && ($order == 'asc' | $order == 'desc')){
            $query_url .= '&order='.$order;
        }
        else return -1;

        curl_setopt($this->_curl_desc, CURLOPT_URL, $query_url);
        $curl_query_result = json_decode(curl_exec($this->_curl_desc));

        return $curl_query_result;
    }

    public function getProjectDetails($project_name, $owner){

        $query_url_repo = $this->_github_api_url . "repos/$owner/$project_name";

        curl_setopt($this->_curl_desc, CURLOPT_URL, $query_url_repo);
        $project_details = json_decode(curl_exec($this->_curl_desc));

        $query_url_contribs_list = $this->_github_api_url . "repos/$owner/$project_name/contributors";
        curl_setopt($this->_curl_desc, CURLOPT_URL, $query_url_contribs_list);
        $contributors = json_decode(curl_exec($this->_curl_desc));

        return array ("info"=>$project_details, "contributors"=>$contributors);
    }

    public function getUserDetails($user_login){
        $query_url_repo = $this->_github_api_url . "users/$user_login";

        curl_setopt($this->_curl_desc, CURLOPT_URL, $query_url_repo);
        return json_decode(curl_exec($this->_curl_desc));
    }
}