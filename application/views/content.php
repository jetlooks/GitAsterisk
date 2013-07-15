        <div id="page_content">

            <?php if(isset($current_page) && $current_page == 'Search project'): ?>

                    <form action="search_project" method="POST">
                        <span>Project name: </span>
                        <input type="text" name="search" value="<?php echo $keyword?>">
                        <input type="submit" value="Search">

                        <br>
                        <div id="search_form_settings">
                            <span id="s_opt_title">Search options:</span><br>
                            <span class="s_options">Language:<span>
                            <select name="lang">
                                <?php foreach($lang_list as $lang):?>
                                    <option value="<?php echo trim($lang).'"'; if(trim($lang) == $selected_language) echo " selected";?>><?php echo trim($lang);?></option>
                                <?php endforeach;?>
                            </select>
                            <br>
                            <span class="s_options">Sort by:</span>
                            <div id="sort">
                                <input type="radio" name="sort" value="stars" <?php if($sort == 'stars') echo "checked"?>> stars
                                <input type="radio" name="sort" value="forks" <?php if($sort == 'forks') echo "checked"?>> forks
                                <input type="radio" name="sort" value="updated" <?php if($sort == 'updated') echo "checked"?>> updated
                            </div>
                            <span class="s_options">Order direction:</span>
                            <div id="order">
                                <input type="radio" name="order" value="asc" <?php if($order == 'asc') echo "checked"?>> ask
                                <input type="radio" name="order" value="desc" <?php if($order == 'desc') echo "checked"?>> desk
                            </div><br>

                        </div>
                    </form>


                    <?php if(isset($search_repo_error)):?>
                        <h3><?php echo $search_repo_error;?></h3>
                    <?php endif;?>

                    <?php
                        //If Search page working
                        if(isset($search_repos_result) && ! isset($search_repo_error)):?>

                            <h3>We've found <?php echo count($search_repos_result->repositories);?> results:</h3>

                            <?php foreach($search_repos_result->repositories as $value):?>

                                <?php
                                //If all the info about repo present
                                if(isset($value->pushed)):?>

                                    <div class="repo_info">
                                        <div class="repo_res_container">
                                            <div class="repo_name">
                                                <span class="search_repo_titles">Repository name:</span><br>
                                                <a href="<?php echo base_url()."project_details/".$value->owner."/".$value->name;?>" target="_blank"><?php echo $value->name;?></a>
                                                <?php if ($value->private == 1) echo '(private)'; else echo '(public)'; ?>
                                            </div>
                                            <div class="owner">
                                                <span class="search_repo_titles">Owner:</span><br>
                                                <a href="<?php echo base_url().'user_details/'.$value->owner;?>" target="_blank"><?php echo $value->owner;?></a>
                                            </div>
                                            <div class="like_box">

                                                <?php $opinion = $count_object->isLiked($value->name, $value->owner, $_SERVER['REMOTE_ADDR']);
                                                      if($opinion == 0 || $opinion === FALSE):?>
                                                    <div onclick="likeRepo('<?php echo $value->owner;?>', '<?php echo $value->name;?>', 1)" class="likes like">Like</div>
                                                <?php else:?>
                                                    <div onclick="likeRepo('<?php echo $value->owner;?>', '<?php echo $value->name;?>', 0)" class="likes unlike">Unlike</div>
                                                <?php endif;?>

                                                <span>Likes: <?php $c_arr = $count_object->likesUnlikesCount($value->name, $value->owner); echo $c_arr['likes'];?></span>
                                                <span>Unlikes: <?php $c_arr = $count_object->likesUnlikesCount($value->name, $value->owner); echo $c_arr['unlikes'];?></span>
                                            </div>
                                            <div class="repo_add_info">
                                                <?php if($value->homepage != '') echo '<span><a href="'.$value->homepage.'" target="_blank">  Homepage</a></span>';?>
                                                <?php if($value->url != '') echo '<span><a href="'.$value->url.'" target="_blank">  Github page</a></span>';?>

                                                <span class="forks">  Forks: <?php echo $value->forks;?></span>
                                                <?php $date_time = explode('T', $value->created);?>

                                                <span>  Created: <?php echo $date_time[0]."  ".str_replace('Z', '', $date_time[1]); ?></span>
                                                <?php if($value->language != '') echo '<span> Language: '.$value->language.'</span>';?>
                                                <?php if($value->watchers >= 0) echo '<span> Watchers: '.$value->watchers.'</span>';?>
                                                <?php if($value->followers >= 0) echo '<span> Followers: '.$value->followers.'</span>';?>
                                            </div>
                                            <input type="hidden" value="<?php if($_SERVER['REMOTE_ADDR'] == "::1") echo "127.0.0.1"; else echo $_SERVER['REMOTE_ADDR'] ?>">
                                            <div class="clear"></div>
                                        </div>
                                    </div>

                                 <?php else: continue; ?>
                                 <?php endif;?>
                            <?php endforeach;?>
                    <?php endif;?>
            <?php endif;//End Search project page?>


            <?php if(isset($current_page) && $current_page == 'Main'): //Main Page?>

                <div class="yii_logo"><img src="<?php echo base_url();?>img/yii-framework.png"></div>

                <div id="repo_info_box">

                    <?php echo "Project full name: ".$full_name;?><br>

                    <?php echo "Description: ".$description;?><br>

                     Github page: <a href="<?php echo $html_url?>" target="_blank"><?php echo $html_url?></a><br>

                    <?php if(isset($homepage)):?>
                        Home page: <a href="<?php echo $homepage;?>" target="_blank"><?php echo $homepage;?></a><br>
                    <?php endif;?>

                    <?php echo "Forks: ".$forks?><br>

                    <?php echo "Watchers: ".$watchers?><br>

                    <?php $date_time = explode('T', $created); $date = $date_time[0]; $time = str_replace('Z', '', $date_time[1]);
                        echo "Created: ".$date." ",$time?><br>
                </div>

                <h3>Contributors list:</h3><br>

                <?php if (count($contributors) > 0):?>
                    <?php foreach($contributors as $cool_man):?>
                        <div class="user_info_box">
                            <div class="logo_wrapper"><div class="user_logo" style='background-image: url("<?php echo $cool_man->avatar_url;?>")'></div></div>

                            <div class="user_name">
                                <span class="search_repo_titles">User name:</span><br>
                                <a href="<?php echo base_url();?>user_details/<?php echo $cool_man->login;?>" target="_blank"><?php echo $cool_man->login;?></a>
                            </div>
                            <div class="github_page">
                                Github page: <br><a href="<?php echo $cool_man->html_url;?>" target="_blank"><?php echo $cool_man->html_url;?></a>
                            </div>
                            <div class="user_like_box">
                                <?php $opinion = $count_object->isUserLiked($cool_man->login, $_SERVER['REMOTE_ADDR']);
                                if($opinion == 0 || $opinion === FALSE):?>
                                    <div onclick="likeUser('<?php echo $cool_man->login;?>', 1)" class="likes like">Like</div>
                                <?php else:?>
                                    <div onclick="likeUser('<?php echo $cool_man->login;?>', 0)" class="likes unlike">Unlike</div>
                                <?php endif;?>

                                <?php $count_likes = $count_object->userLikesUnlikesCount($cool_man->login);?>
                                <span>Likes: <?php echo $count_likes['likes'];?> </span>
                                <span>Unlikes:  <?php echo $count_likes['unlikes'];?> </span>
                            </div>
                            <div class="clear"></div>
                        </div>
                    <?php endforeach;?>
                <?php endif;?>
                <input type="hidden" value="<?php if($_SERVER['REMOTE_ADDR'] == "::1") echo "127.0.0.1"; else echo $_SERVER['REMOTE_ADDR'] ?>">
            <?php endif;//End Main page?>




            <?php if(isset($current_page) && $current_page == 'Project details'): //Project details page?>
                <div class="user_details">
                        <div class="left_part">
                            <h3><?php echo $full_name;?></h3>
                            <div class="proj_logo"><div class="logo_wrapper"><div class="user_logo" style='background-image: url("<?php echo $avatar_url;?>")'></div></div></div>
                            <div class="repo_desc"><?php echo '<span class="info_title">Description: </span>'.$description;?></div>
                            <div class="like_btn">
                                <div class="like_box">
                                    <?php $opinion = $count_object->isLiked($owner, $project_name, $_SERVER['REMOTE_ADDR']);
                                    if($opinion == 0 || $opinion === FALSE):?>
                                        <div onclick="likeRepo('<?php echo $owner;?>', '<?php echo $project_name;?>', 1)" class="likes like">Like</div>
                                    <?php else:?>
                                        <div onclick="likeRepo('<?php echo $owner;?>', '<?php echo $project_name;?>', 0)" class="likes unlike">Unlike</div>
                                    <?php endif;?><br>

                                    <span class="like_span">Likes: <?php $c_arr = $count_object->likesUnlikesCount($project_name, $owner); echo $c_arr['likes'];?></span><br>
                                    <span class="like_span">Unlikes: <?php $c_arr = $count_object->likesUnlikesCount($project_name, $owner); echo $c_arr['unlikes'];?></span>
                                </div>
                            </div>

                        </div>
                        <div class="right_part">
                            <span class="info_title">Github page:</span> <a href="<?php echo $html_url?>"><?php echo $html_url?></a><br>

                            <?php if(isset($homepage)):?>
                                <span class="info_title">Home page: </span><a href="<?php echo $homepage;?>" target="_blank"><?php echo $homepage;?></a><br>
                            <?php endif;?>

                            <?php echo '<span class="info_title">Forks: </span>'.$forks?><br>

                            <?php echo '<span class="info_title">Watchers: </span>'.$watchers?><br>

                            <?php $date_time = explode('T', $created); $date = $date_time[0]; $time = str_replace('Z', '', $date_time[1]);
                            echo '<span class="info_title">Created: </span>'.$date.' '.$time?><br>
                        </div>
                    <div class="clear"></div>
                </div>

                <?php if ( ! isset($contributors->message) && count($contributors) > 0):?>
                    <h3>Contributors list:</h3><br>
                    <?php foreach($contributors as $cool_man):?>
                        <div class="user_info_box">
                            <div class="logo_wrapper"><div class="user_logo" style='background-image: url("<?php echo $cool_man->avatar_url;?>")'></div></div>

                            <div class="user_name">
                                <span class="search_repo_titles">User name:</span><br>
                                <a href="<?php echo base_url();?>user_details/<?php echo $cool_man->login;?>" target="_blank"><?php echo $cool_man->login;?></a>
                            </div>
                            <div class="github_page">
                                Github page: <br><a href="<?php if(isset($cool_man->html_url)) echo  $cool_man->html_url;?>"><?php if(isset($cool_man->html_url)) echo $cool_man->html_url;?></a>
                            </div>
                            <div class="user_like_box">
                                <?php $opinion = $count_object->isUserLiked($cool_man->login, $_SERVER['REMOTE_ADDR']);
                                if($opinion == 0 || $opinion === FALSE):?>
                                    <div onclick="likeUser('<?php echo $cool_man->login;?>', 1)" class="likes like">Like</div>
                                <?php else:?>
                                    <div onclick="likeUser('<?php echo $cool_man->login;?>', 0)" class="likes unlike">Unlike</div>
                                <?php endif;?>

                                <?php $count_likes = $count_object->userLikesUnlikesCount($cool_man->login);?>
                                <span>Likes: <?php echo $count_likes['likes'];?> </span>
                                <span>Unlikes:  <?php echo $count_likes['unlikes'];?> </span>
                            </div>
                            <div class="clear"></div>
                        </div>
                    <?php endforeach;?>
                <?php else:?>
                    <h3>This project hasn't any contributors...</h3><br>

                <?php endif;?>
                <input type="hidden" value="<?php if($_SERVER['REMOTE_ADDR'] == "::1") echo "127.0.0.1"; else echo $_SERVER['REMOTE_ADDR'] ?>">
            <?php endif;//End Project details page?>



            <?php if(isset($current_page) && $current_page == 'User details'): //Project details page?>
            <div class="user_details">
                <div class="left_part">
                    <?php if(isset($name)):?><h2><?php echo $name;?></h2><?php endif;?>
                    <div class="proj_logo"><div class="logo_wrapper"><div class="user_logo" style='background-image: url("<?php echo $avatar_url;?>")'></div></div></div><br>
                    <div class="like_btn">
                        <?php $opinion = $count_object->isUserLiked($user_login, $_SERVER['REMOTE_ADDR']);
                        if($opinion == 0 || $opinion === FALSE):?>
                            <div onclick="likeUser('<?php echo $user_login;?>', 1)" class="likes like">Like</div><br>
                        <?php else:?>
                            <div onclick="likeUser('<?php echo $user_login;?>', 0)" class="likes unlike">Unlike</div><br>
                        <?php endif;?>

                        <?php $count_likes = $count_object->userLikesUnlikesCount($user_login);?>
                        <span>Likes: <?php echo $count_likes['likes'];?> </span><br>
                        <span>Unlikes:  <?php echo $count_likes['unlikes'];?> </span>
                    </div>
                </div>
                <div class="right_part">
                    <span class="info_title">Login: </span><?php echo $user_login;?><br>
                    <span class="info_title">Github page: </span><a href="<?php if(isset($html_url)) echo  $html_url;?>" target="_blank"><?php if(isset($html_url)) echo $html_url;?></a><br>
                    <?php if(isset($location)):?><span class="info_title">Location: </span><?php echo $location."<br>";?><?php endif;?>
                    <?php if(isset($company)) echo '<span class="info_title">Company:</span> '.$company.'<br>';?>
                    <?php if(isset($email)) echo '<span class="info_title">Email: </span>'.$email."<br>";?>
                    <?php $date_time = explode('T', $created_at); $date = $date_time[0]; $time = str_replace('Z', '', $date_time[1]);
                    echo '<span class="info_title">Created: </span>'.$date.' '.$time;?><br>
                    <span class="info_title">Public repositories: </span><?php echo $public_repos;?><br>
                    <span class="info_title">Followers: </span><?php echo $followers;?><br>
                </div>
                <div class="clear"></div>
            </div>

                <input type="hidden" value="<?php if($_SERVER['REMOTE_ADDR'] == "::1") echo "127.0.0.1"; else echo $_SERVER['REMOTE_ADDR'] ?>">
            <?php endif;//End User details page?>

        </div> <!--end div #page_content-->