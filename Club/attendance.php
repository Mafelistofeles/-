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
?>
	<div class="row">
			<ol class="breadcrumb">
				<li><a href="home.php"><i class="fa fa-home" aria-hidden="true"></i></a></li>
				<li class="active">Посещения</li>
			</ol>
		</div><!--/.row-->

	<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Секция посещений</h1>
			</div>
	</div><!--/.row-->

	<div class="row">
    <div class="panel">
        <div class="panel-body tabs">
            <div class="col-lg-3">
                <div class="panel-header"><h3 class="text-center">Выберите дату</h3><br>
                    <ul class="nav nav-pills nav-stacked">
                        <?php
                        $months = array(
                            "Января", "Февраля", "Марта", "Апреля", "Мая", "Июня",
                            "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря"
                        );

                        global $con;
                        $query = "SELECT * FROM sessions";
                        $result = mysqli_query($con, $query);
                        $rows = mysqli_affected_rows($con);

                        while ($row = mysqli_fetch_assoc($result)) {
                            $session_date = strtotime($row['session_date']);
                            $formatted_date = date('j', $session_date) . ' ' . $months[(int)date('n', $session_date) - 1] . ' ' . date('Y H:i', $session_date);
                            echo '<li><a href="#' . $row['session_id'] . '" data-toggle="pill">' . $formatted_date . '</a></li>';
                        }
                        ?>
                    </ul>
                </div>


			</div>
			<div class="panel-body">
			<div class="col-lg-9">
				<div class="tab-content">
					<div id="tab-start" class="tab-pane fade in active">
						<div class="col-lg-6">
							<div class="panel panel-teal">
								<div class="panel-heading">
									Сессии
								</div>
								<div class="panel-body">
									<p><i>Выберите дату сеанса слева, чтобы увидеть посещаемость этого конкретного сеанса!</i></p>
								</div>
							</div>
						</div>
					</div>
					<?php
						$months = array(
							"Января", "Февраля", "Марта", "Апреля", "Мая", "Июня",
							"Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря"
						);

						global $con;
						$query = "SELECT * FROM sessions";
						$result = mysqli_query($con, $query);
						$rows = mysqli_affected_rows($con);

						while ($row = mysqli_fetch_assoc($result)) {
							$session_date = strtotime($row['session_date']);
							$formatted_date = date('j', $session_date) . ' ' . $months[(int)date('n', $session_date) - 1] . ' ' . date('Y H:i', $session_date);

							echo '<div id="' . $row['session_id'] . '" class="tab-pane fade">
										<h3 class="text-center">Посещения ' . $formatted_date . '</h3><br>';
							attendance($row['session_id'], $role);
							echo '</div>';
						}
						?>

					</div>
				</div>
				</div>
			</div>
			</div>
			</div>
</div><!--/.row-->
<?php
	at_bottom();