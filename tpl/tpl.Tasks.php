<div id="afmng_content">

<div id="afmng_header">
Anime Fansub Manager
</div>

<div id="afmng_body">

<form id="dummyForm" method="post" action=""></form>

<div id="task_current">

<div id="task_current_header">Meine aktuellen Aufgaben (<?php echo $this->user; ?>)</div>

<table class="afmng_table">
	<thead>
		<tr>
		<th>Anime</th>
		<th>#</th>
		<th>Aufgabe</th>
		<th>Status</th>
		<th>Notizen</th>
		<th></th>
		</tr>
	</thead>
	
	
	<?php foreach($this->tasks as $task): ?>
	<tr>
		<td><?php echo $task->anime_name; ?></td>
		<td><?php echo $task->episode_no, ' / ', $task->episode_title; ?></td>
		<td><?php echo $task->name; ?></td>
		<td>
			<form>
			<select id="state_no:<?php echo $task->task_id; ?>" name="state_no">
				<?php foreach(afmngdb::$step_state as $key => $value): ?>
					<option value="<?php echo $key; ?>" <?php echo $key == $task->state_no ? 'selected' : '' ?>><?php echo $value; ?></option>
				<?php endforeach; ?>
			</select>
			</form>
		</td>
		<td><input id="description:<?php echo $task->task_id; ?>" type="text" name="description" value="<?php echo $task->description; ?>" /></td>
		<td>
			<a href="#" title="Speichern" onclick="afmng_tasks_update(<?php echo $task->task_id;?>); return false;"><div class="button_save"></div></a>
			<a href="#" title="Freigeben"onclick="afmng_tasks_free(<?php echo $task->task_id;?>); return false;"><div class="button_free"></div></a>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
</div>

<div id="task_available">
			
<div id="task_available_header">Verfügbare Aufgaben</div>
<!-- available releases and follow releases when the step before is done -->

<!-- if admin add option to add any tasks -->

<table class="afmng_table">
	<thead>
		<tr>
			<th>Anime</th>
			<th>Episode</th>
			<th>Step</th>
			<th><!--Actions--></th>
		</tr>
	</thead>
	
	<?php foreach($this->tasks_available as $task): ?>
		<tr>
			<td><?php echo $task->anime_name; ?></td>
			<td><?php echo $task->episode_no, ' / ', $task->episode_title; ?></td>
			<td><?php echo $task->name; ?></td>
			<td>
				
				<?php if($task->task_id): ?>
				
					<a href="#" onclick="afmng_tasks_accept(<?php echo $task->task_id; ?>); return false;">Annehmen</a>
					<?php if($this->is_admin):  ?>
						<a href="#" onclick="afmng_tasks_delete(<?php echo $task->task_id; ?>); return false;">Löschen</a>
					<?php endif;  ?>
				<?php else: ?>
				
					<a href="#" title="Anlegen und annehmen" onclick="afmng_tasks_create_assign(<?php echo $task->release_id; ?>, <?php echo $task->step_id; ?>); return false;"><div class="button_create"></div></a>
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	

</table>
</div>

<!--
<div id="task_done">

<h2>Abgeschlossene Aufgaben</h2>
</div>
-->
<div id="task_random">
<?php if($this->is_admin): ?>
	<div id="task_random_header">Beliebige Aufgabe hinzufügen</div>
	
	<form id="admin_task_add" method="post" action="">
	<input type="hidden" name="action" value="admin_task_add" />
	<table>
		<thead>
			<tr>
				<th>Anime</th>
				<th>Episode</th>
				<th>Step</th>
				<th>Benutzer</th>
			</tr>
		</thead>
		<tr>
			<td>
				<select id="cmb_anime" name="anime">
					<?php foreach(afmng_db_project_list() as $project): ?>
						<option value="<?php echo $project->project_id; ?>"><?php echo $project->anime_name; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
			<td><select id="cmb_episode" name="episode"></select></td>
			<td>
				<select id="cmb_step" name="step">
					<?php foreach(afmng_db_steps() as $step): ?>
						<option value="<?php echo $step->step_id; ?>"><?php echo $step->name; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
			<td>
				<select id="cmb_user" name="user">
					<option></option>
					<?php foreach(get_users() as $user): ?>
						<option><?php echo $user->user_login; ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Hinzufügen') ?>" />
	
	</form>
<?php endif; ?>
</div>
</div>
</div>