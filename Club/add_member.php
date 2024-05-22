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

	if($role != 'President')
	{
		echo '<div class="text-center alert bg-warning col-md-offset-4 col-md-4"><p><b>Access Forbidden</b></p></div>';
		echo '<script>setTimeout(function () { window.location.href = "home.php";}, 1000);</script>';
		exit();
	}

	

?>
			
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="home.php"><i class="fa fa-home" aria-hidden="true"></i></a></li>
				<li><a href="manage_members.php">Участники</a></li>
				<li class="active">Добавить нового участника</li>
			</ol>
		</div><!--/.row-->

		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Добавление нового участника</h1>
			</div>
		</div><!--/.row-->

		<div class="row">
			<div class="error">
				<?php add_member($role); ?>
			</div>
			<div class="col-lg-offset-2 col-lg-6">
			<form class="form-signin" method="post" action="">
			<label for="name">Имя</label>
			<input type="text" name="name" placeholder="имя" id="name" class="form-control" required><br>
			<label for="email">Почта</label>
			<input type="email" name="email" placeholder="почта" id="email" class="form-control" required><br>
			<label for="username">Имя пользователя</label>
			<input type="text" name="username" placeholder="никнейм" id="username" class="form-control" required><br>
			<label for="password">Пароль</label>
			<input type="password" name="password" placeholder="пароль" id="password" class="form-control" required><br><br>
			<?php if($role == 'President') {
				echo '<label for="role">Роль</label>
				<select class="form-control" name="role" required>
					<option value="">Выбрать</option>
					<option value="Member">Участник</option>
					<option value="Media Marketing">Медиа-маркетинг</option>
					<option value="Admin Logistics">Административная логистика</option>
					<option value="Member Management">Управление членами клуба</option>
					<option value="Technical">Технический</option>
					<option value="President">Президент</option>
				</select><br>';
			} ?>
			<button class="btn btn-primary" name="add_member" type="submit" id="login">Добавить</button>&nbsp;&nbsp;
			<a href="manage_members.php" class="btn btn-default" id="login">Отмена</a>
		</form>

			</div>
		</div><!--/.row-->
		<script>
		flatpickr('#dob', {
			dateFormat: 'Y-m-d', // Формат даты
		});
		</script>
		<script>
		$(document).ready(function()
		{
		     $("#dtBox").DateTimePicker();
		});
 	</script>
<link rel="stylesheet" type="text/css" href="css/DateTimePicker.min.css" />
<script type="text/javascript" src="js/DateTimePicker.min.js"></script>
<?php
	at_bottom();