<?php
/**
 * Class presents a Github browser site's GitAsterisk page with repository search results
 * @author Igor Khromov
 * @date 10 July 2013
*/


require_once("application/libraries/Git_manager.php");
require_once("application/libraries/Git_data_mapper.php");

class Search_project extends CI_Controller{

    private $_current_page_name;
    private $_git_manager;
    private $_git_data_mapper;

    public function __construct(){
        parent::__construct();

        //Sets the page name that uses in view
        $this->_current_page_name = 'Search project';

        //Creating Github_manager instance to work with Github API v3
        $this->_git_manager = new Git_manager(GITHUB_API_URL);

        $this->_git_data_mapper = new Git_data_mapper();
    }

    public function index(){

        $keyword = NULL;
        $language = 'All';
        $sort = 'stars';
        $order = 'asc';

        if($_SERVER['REQUEST_METHOD'] == "POST"){

            if(isset($_POST['search'])){
                $keyword = strip_tags(trim($_POST['search']));
            }
            else
                $keyword = '';

            if(isset($_POST['lang']) && $_POST['lang'] != 'All'){
                $language = strip_tags(trim($_POST['lang']));
            }

            if(isset($_POST['sort']) && ($_POST['sort'] == 'stars' | $_POST['sort'] == 'forks' | $_POST['sort'] == 'updated'))
            {
                $sort = strip_tags(trim($_POST['sort']));
            }

            if(isset($_POST['order']) && ($_POST['order'] == 'asc' | $_POST['order'] == 'desc'))
            {
                $order = strip_tags(trim($_POST['order']));
            }

            if($keyword !=  NULL){
                $result_array = $this->_git_manager->searchRepos($keyword, $language, $sort, $order);

                //If some results found by given keyword
                if(isset($result_array) && is_object($result_array)){
                    if(count($result_array->repositories) != 0) $data['search_repos_result'] = $result_array;
                    else $data['search_repo_error'] = "No one repository found by keyword: '" . $keyword. "'";
                }

                //If nothing found by keyword
                else{
                    $data['search_repo_error'] = "Error in given search parameters!";
                }
            }
        }

        $data['keyword'] = $keyword;
        $data['selected_language'] = trim($language);
        $data['lang_list'] = file('application/language/lang_list.txt');
        $data['sort'] = $sort;
        $data['order'] = $order;
        $data['current_page'] = $this->_current_page_name;
        $data['count_object'] = $this->_git_data_mapper;

        //Show search_page results
        $this->load->view('header', $data);
        $this->load->view('content', $data);
        $this->load->view('footer');
    }
}