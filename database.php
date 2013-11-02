<?php

class afmngdb
{
	public static $tbl_projects;
	public static $tbl_releases;
	public static $tbl_release_steps;
	public static $tbl_release_steps_map;
	
	
	public static $step_state = array(0 => 'Offen', 1 => 'In Bearbeitung', 2 => 'Erledigt');

    public static function setup() 
    {
		global $wpdb;
		self::$tbl_projects = $wpdb->prefix . "afmng_projects";
		self::$tbl_releases = $wpdb->prefix . "afmng_releases";
		self::$tbl_release_steps  = $wpdb->prefix . "afmng_release_steps";
		self::$tbl_release_steps_map = $wpdb->prefix . "afmng_release_steps_map";
    }

}

afmngdb::setup();
$afmngdb = 'afmngdb';

/**
* Return a project list
*/
function afmng_db_project_list()
{
	global $wpdb;
	
	
	return $wpdb->get_results( 
		"
		SELECT p.project_id, 
			   p.anime_name
		FROM ".afmngdb::$tbl_projects." as p
		WHERE p.completed = false
		"
	);
}

/**
* Get releases for a project
*/
function afmng_db_project_releases($projectid)
{
	global $wpdb;
	
	$sql = $wpdb->prepare( 
		"
		SELECT r.release_id,
			   r.episode_no,
			   r.episode_title
		FROM ".afmngdb::$tbl_releases." as r
		WHERE r.project_id=%d
		",
		$projectid
        );
        
	return $wpdb->get_results($sql);
}

/**
* get steps of a release
*/
function afmng_db_release_steps($releaseid)
{
	global $wpdb;
	
	$sql = $wpdb->prepare( 
		"
		SELECT sm.step_id,
			   s.name as step_name,
			   sm.user,
			   sm.state_no,
			   sm.description
		FROM ".afmngdb::$tbl_release_steps_map." as sm
		INNER JOIN ".afmngdb::$tbl_release_steps." as s
			ON s.step_id = sm.step_id	
		WHERE sm.release_id=%d
		ORDER BY sm.step_id ASC
		",
		$releaseid
        );
        
	return $wpdb->get_results($sql);
}

/**
* Get strings for state
*/
function afmng_db_steps_state($state_no)
{
	return afmngdb::$step_state[$state_no];
}

/**
* Check caps for current user
*/
function afmng_db_user_getcaps($user_id)
{
	$caps = array('afmng_rawprovider',
	              'afmng_translator',
	              'afmng_edit',
	              'afmng_typeset',
	              'afmng_karaoke',
	              'afmng_qc',
	              'afmng_mux',
	              'afmng_hardsub');
	
	$has = array();
	
	foreach($caps as $cap)
		if(user_can($user_id, $cap))
			array_push($has, $cap);	
			
	return $has;
}



function afmng_projects_lastreleases()
{
	global $wpdb;
	
	return $wpdb->get_results( 
		"
		SELECT p.project_id, p.anime_name, r.release_id, MAX(r.creation_date) as creation_date, r.translation_status
		FROM ".afmngdb::$tbl_projects." as p
		LEFT JOIN ".afmngdb::$tbl_releases." as r 
			ON p.project_id = r.project_id
		ORDER BY p.creation_date DESC
		"
	);
}

/**
* Add a new anime project
*/
function afmng_project_add($name)
{
	global $wpdb;

	$wpdb->insert( 
		afmngdb::$tbl_projects, 
		array( 
			'anime_name' => $name, 
		), 
		array( 
			'%s'
		) 
	);
	
}

/**
* Update a project
*/
function afmng_project_update($project_id, $name, $completed, $licensed)
{
	global $wpdb;
	
	$wpdb->update( 
	afmngdb::$tbl_projects, 
	array( 
		'anime_name' => $name,
		'completed' => $completed,
		'licensed' => $licensed
	), 
	array( 'project_id' => $project_id ), 
	array( '%s','%d', '%d'), 
	array( '%d' ) 
    );
}

/**
* Add a new anime release (episode)
*/
function afmng_db_release_add($project_id, $episode_no, $episode_title)
{
	global $wpdb;

	$wpdb->insert( 
		afmngdb::$tbl_releases, 
		array( 
			'project_id' => $project_id,
			'episode_no' => $episode_no,
			'episode_title' => $episode_title
		), 
		array( 
			'%d',
			'%s',
			'%s'
		) 
	);
}


/**
* Get active tasks/steps for username
*/
function afmng_db_gettasks($user)
{
	global $wpdb;	
	
	return $wpdb->get_results( 
		"
		SELECT
			p.anime_name,
			r.episode_no,
			r.episode_title,
			s.name,
			sm.state_no,
			sm.description
		FROM ".afmngdb::$tbl_release_steps_map." as sm
		INNER JOIN ".afmngdb::$tbl_release_steps." as s
			ON s.step_id = sm.step_id
		INNER JOIN ".afmngdb::$tbl_releases." as r 
			ON sm.release_id = r.release_id
		INNER JOIN ".afmngdb::$tbl_projects." as p
			ON p.project_id = r.project_id
		WHERE
			sm.user = '$user'
		"
	);
}


function afmng_db_releases_available($user_id)
{
	
	//afmng_db_user_getcaps($user_id)
	// get_userdata( $userid );
	//usr is admin
	//afmng_db_user_getcaps for user
	
	/*
	 SELECT 
			anime,
			episode
			step_id, 
			name 
	 FROM tbl_release_steps as s
	 * 
	 LEFT JOIN tbl_anime as p
	 LEFT JOIN tbl_releases as r
	 LEFT JOIN tbl_release_map as sm
		//null
		//
	*/
	
	//anime_name
	//episode
	//step
	
	//if admin each step is available
	//display only steps when prev_id exists and state_no == 2
	
	//assign / create step button
}

/**
* Return all available steps
*/
function afmng_db_steps()
{
	global $wpdb;	
	
	return $wpdb->get_results( 
		"
		SELECT
			s.step_id,
			s.name
		FROM ".afmngdb::$tbl_release_steps." as s
		"
	);
}


?>
