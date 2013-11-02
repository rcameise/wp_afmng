<?php
/* Main Menu Hook*/
add_action( 'admin_menu', 'afmng_menu_setup' );

/* Setup AFMNG Menus*/
function afmng_menu_setup() 
{
	//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	add_menu_page('Aufgaben', 'Aufgaben', 'publish_posts', 'afmng_menu_tasks', 'afmng_menu_tasks', null, 3);
	
	//only if caps are ok?:
	if(is_admin())
	{
		//User Manager
		add_submenu_page('afmng_menu_tasks', 'User Manager', 'User Manager', 'publish_posts', 'afmng_menu_usermng', 'afmng_menu_usermng');

		//Project Manager
		add_submenu_page('afmng_menu_tasks', 'Projekt Manager', 'Projekt Manager', 'publish_posts', 'afmng_menu_projectmng', 'afmng_menu_projectmng');
	
		//Completed Projects
		
		//Manage Steps? Show Steps?
	}
}

/**
* Renders the AFMNG main menu
*/
function afmng_menu_tasks()
{
	//get_current_user_id();
	$current_user = wp_get_current_user();
	
	//check for required stuff?
	// * a parent "Projekte" page
	
	if(afmng_check_post())
		afmng_menu_tasks_postback();
	
	$ltpl = new LTemplate();
	$ltpl->tasks = afmng_db_gettasks($current_user->user_login);
	$ltpl->user = $current_user->user_login;
	
	//own tasks
	
	$ltpl->is_admin = is_admin();
	
	//render template
	$ltpl->render(afmng_get_tplfile('tpl.MainMenu.php'));
}

/**
* Postback Handler for MainMenu
*/
function afmng_menu_tasks_postback()
{
	//check if user has the rights
	//if( !current_user_can( 'manage_options' )
	
	//admin_task_add
	
	switch($_POST["action"])
	{
		case 'admin_task_add':
			afmng_db_task_add($_POST['episode'], $_POST['step'], $_POST['user']);
			break;
	}
}

/**
* Tasks Scripts
*/
add_action( 'admin_enqueue_scripts', 'afmng_menu_tasks_scripts');
function afmng_menu_tasks_scripts($hook) 
{
	//page=afmng_menu_tasks
    wp_enqueue_script('afmng_menu_tasks_scripts', plugins_url('afmng/js/tasks.js') );
}


/**
* Render the Project Manager Page
*/
function afmng_menu_projectmng()
{
	//right check
	
	if(afmng_check_post())
		afmng_menu_projectmng_postback();
	
	$ltpl = new LTemplate();
	$ltpl->project_list = afmng_db_project_list();
	$ltpl->is_admin = is_admin();
	
	//render page
	$ltpl->render(afmng_get_tplfile('tpl.ProjectMng.php'));
}

/**
* Postback handling for ProjectManager
*/
function afmng_menu_projectmng_postback()
{
	switch($_POST["action"])
	{
		case 'add_project':
			afmng_project_add($_POST["anime_name"]);
			break;
			
		case 'update_project':
			afmng_project_update($_POST["project_id"], $_POST["anime_name"], 0, 0 );
			break;

		case 'add_release':
			afmng_db_release_add($_POST["project_id"], $_POST["episode_no"], $_POST["episode_title"]);
			break;
	}
}

/**
* Render the User Manager Page
*/
function afmng_menu_usermng()
{
	$ltpl = new LTemplate();
	//render page
	$ltpl->render(afmng_get_tplfile('tpl.UserMng.php'));
}



?>
