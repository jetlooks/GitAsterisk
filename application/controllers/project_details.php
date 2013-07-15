<?php
/**
 * Class presents a Github project (repository) details page for Github browser site - GitAsterisk
 * @author Igor Khromov
 * @date 10 July 2013
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

require_once("application/libraries/Git_manager.php");
require_once("application/libraries/Git_data_mapper.php");

class Project_details extends CI_Controller{

    private $_current_page_name;
    private $_git_manager;
    private $_git_data_mapper;

    public function __construct(){
        parent::__construct();
        $this->_current_page_name = 'Project details';
        $this->_git_manager = new Git_manager(GITHUB_API_URL);
        $this->_git_data_mapper = new Git_data_mapper();
    }

    public function index($owner, $project_name){

        if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == base_url()."search_project"){

            //Get project details

            $project_details = $this->_git_manager->getProjectDetails($project_name, $owner);

            $data['owner'] = $owner;
            $data['project_name'] = $project_name;
            $data['forks'] = $project_details['info']->forks;
            $data['full_name'] = $project_details['info']->full_name;
            $data['description'] = $project_details['info']->description;
            $data['avatar_url'] = $project_details['info']->owner->avatar_url;
            $data['homepage'] = $project_details['info']->homepage;
            $data['watchers'] = $project_details['info']->watchers;
            $data['html_url'] = $project_details['info']->html_url;
            $data['created'] = $project_details['info']->created_at;
            $data['contributors'] = $project_details['contributors'];
            $data['count_object'] = $this->_git_data_mapper;

            $data['current_page'] = $this->_current_page_name;
            $this->load->view('header', $data);
            $this->load->view('content', $data);
            $this->load->view('footer');
        }
        else
        {
            show_404();
        }
    }

}