<?php
/**
 * Controller for ajax requests
 * @author: Igor Khromov
 * @date: 14.07.13
 */

/**
     * @property CI_DB_active_record $db
     * @property CI_DB_forge $dbforge
     * @property CI_Benchmark $benchmark
     * @property CI_Calendar $calendar
     * @property CI_Cart $cart
     * @property CI_Config $config
     * @property CI_Controller $controller
     * @property CI_Email $email
     * @property CI_Encrypt $encrypt
     * @property CI_Exceptions $exceptions
     * @property CI_Form_validation $form_validation
     * @property CI_Ftp $ftp
     * @property CI_Hooks $hooks
     * @property CI_Image_lib $image_lib
     * @property CI_Input $input
     * @property CI_Lang $lang
     * @property CI_Loader $load
     * @property CI_Log $log
     * @property CI_Model $model
     * @property CI_Output $output
     * @property CI_Pagination $pagination
     * @property CI_Parser $parser
     * @property CI_Profiler $profiler
     * @property CI_Router $router
     * @property CI_Session $session
     * @property CI_Sha1 $sha1
     * @property CI_Table $table
     * @property CI_Trackback $trackback
     * @property CI_Typography $typography
     * @property CI_Unit_test $unit_test
     * @property CI_Upload $upload
     * @property CI_URI $uri
     * @property CI_User_agent $user_agent
     * @property CI_Validation $validation
     * @property CI_Xmlrpc $xmlrpc
     * @property CI_Xmlrpcs $xmlrpcs
     * @property CI_Zip $zip
     * @property CI_Javascript $javascript
     * @property CI_Jquery $jquery
     * @property CI_Utf8 $utf8
     * @property CI_Security $security
     * @property Logger $logger
     */

require_once("application/libraries/Git_data_mapper.php");

class Ajax_manager extends CI_Controller{

    private $_git_data_mapper;

    public function __construct(){
        parent::__construct();

        $this->_git_data_mapper = new Git_data_mapper();

    }

    public function index(){
        show_404();
    }


    public function likeRepo(){

        if(isset($_POST['owner']) &&
            isset($_POST['repo_name']) &&
            isset($_POST['liker_ip']) &&
            preg_match("/([0-9]{1,3}\.){3}[0-9]{1,3}/u", $_POST['liker_ip']) > 0 &&
            isset($_POST['opinion'])
        ){

            $set_result = $this->_git_data_mapper->setLike($_POST['repo_name'], $_POST['owner'], $_POST['liker_ip'], $_POST['opinion']);

            //Check the set like/unlike result
            if($set_result == "changed"){
                $l_unl_count = $this->_git_data_mapper->likesUnlikesCount($_POST['repo_name'], $_POST['owner']);
                echo '{ "status" : "changed", "likes": "'.$l_unl_count["likes"].'", "unlikes" : "'.$l_unl_count["unlikes"].'"}';
            }
            elseif ($set_result == "added"){
                $l_unl_count = $this->_git_data_mapper->likesUnlikesCount($_POST['repo_name'], $_POST['owner']);
                echo '{ "status" : "added", "likes": "'.$l_unl_count["likes"].'", "unlikes": "'.$l_unl_count["unlikes"].'"}';
            }
        }
        //Error 404 if GET query
        else{
            show_404();
        }
    }

    public function likeUser(){

        if(isset($_POST['user_login']) &&
            isset($_POST['liker_ip']) &&
            preg_match("/([0-9]{1,3}\.){3}[0-9]{1,3}/u", $_POST['liker_ip']) > 0
                 &&
                 isset($_POST['opinion'])
                ){

            $ip = trim(strip_tags($_POST['liker_ip']));
            $ulogin = trim(strip_tags($_POST['user_login']));
            $uopinion = trim(strip_tags($_POST['opinion']));

            $opinion = $this->_git_data_mapper->isUserLiked(trim(strip_tags($_POST['user_login'])), trim(strip_tags($_POST['liker_ip'])));

            if($opinion !== FALSE){
                //Update user like
                $this->_git_data_mapper->updateUserLike($ulogin, $ip, $uopinion);
                $l_unl_count = $this->_git_data_mapper->userLikesUnlikesCount($ulogin);
                echo '{ "status" : "changed", "likes": "'.$l_unl_count["likes"].'", "unlikes" : "'.$l_unl_count["unlikes"].'"}';
            }
            else{
                //Add user like
                $this->_git_data_mapper->addUserLike($ulogin, $ip, $uopinion);
                $l_unl_count = $this->_git_data_mapper->userLikesUnlikesCount($ulogin);
                echo '{ "status" : "added", "likes": "'.$l_unl_count["likes"].'", "unlikes" : "'.$l_unl_count["unlikes"].'"}';
            }
        }
    }

}