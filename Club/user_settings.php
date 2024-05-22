<?php
	require_once('funs.php');
	session_start();
	check_session();
	$session_name = $_SESSION['username'];
	$row = array();
	$row = get_member_data($session_name);
	$id = $row['id'];
	$name = $row['name'];
	$role = $row['role'];
	$pic = $row['pic'];
	$user_id = $_GET['user_id'];
	$last_login = $row['last_login'];
	//$last_login = date('jS M Y H:i', strtotime($last_login));
	$timestamp = strtotime($last_login); // Преобразуем строку с датой в метку времени
	//$last_login = date('%e %B %Y %H:%M', strtotime($last_login));
// Форматируем дату и время вручную
	$day = date('j', $timestamp); // Получаем день месяца без ведущего нуля
	$month = date('n', $timestamp); // Получаем номер месяца без ведущего нуля
	$year = date('Y', $timestamp); // Получаем год
	$time = date('H:i', $timestamp); // Получаем время

// Месяцы на русском языке
	$months = [
		1 => 'Января',
		2 => 'Февраля',
		3 => 'Марта',
		4 => 'Апреля',
		5 => 'Мая',
		6 => 'Июня',
		7 => 'Июля',
		8 => 'Августа',
		9 => 'Сентября',
		10 => 'Октября',
		11 => 'Ноября',
		12 => 'Декабря',
	];

// Формируем отформатированную строку
	$last_login = $day . ' ' . $months[$month] . ' ' . $year . ' ' . $time;
	$total_members = get_all_status();
	$core_members = get_vip_status();
	$total_sessions = total_sessions();
	$completed_sessions = completed_sessions();
	
	starter($id,$name,$role,$pic,$last_login,$total_members,$core_members,$total_sessions,$completed_sessions);
?>
			
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="home.php"><i class="fa fa-home" aria-hidden="true"></i></a></li>
				<li class="active">Настройки</li>
			</ol>
		</div><!--/.row-->

		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Настройки аккаунта</h1>
			</div>
		</div><!--/.row-->
		
		<div class="row">
			<div class="error">
				<?php update_settings($id); ?>
			</div>
			<div class="col-lg-offset-2 col-lg-6">
				<div class="panel panel-primary">
					<div class="panel-heading">
						Профиль
					</div>
				<div class="panel-body">
				<form class="form-signin" method="post" action="">
					<label for="name">Имя пользователя</label>
					<input type="text" value="<?php echo $name; ?>" name="name" placeholder="Имя аккаунта" id="username" class="form-control" require>
				</div>
				<div class="panel panel-danger">
					<div class="panel-heading">
						Безопасность
					</div>
					<div class="panel-body">
					<label for="name">старый пароль</label>
					<input type="password" name="old_pwd" placeholder="старый пароль" id="password" class="form-control">
					<label for="name">новый пароль</label>
					<input type="password" name="new_pwd" placeholder="только не забудь его потом" id="password" class="form-control"><br/>
					</div>
					<div class="panel-footer">
					<button class="btn btn-primary" name="update_settings" type="submit" id="login">Сохранить</button>&nbsp;&nbsp;
					<a href="home.php" class="btn btn-default" id="login">Назад</a>
					</div>
				</form>
			</div>
		</div><!--/.row-->
<?php
	at_bottom();