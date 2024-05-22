<?php
require_once('dbconfig.php');
global $con;

/*******************************
 * function for login into panel.
 *******************************/

 function login()
 {
	 global $con;
	 if (isset($_POST['submit'])) 
	 {
		 $username = $_POST['username'];
		 $username = stripslashes($username);
		 $password = $_POST['password'];
		 $password = stripslashes($password);
 
		 // Подготавливаем SQL-запрос для поиска пользователя по имени
		 $query = "SELECT * FROM userinfo WHERE username = '$username'";
		 $result = mysqli_query($con, $query);
 
		 // Проверяем, нашелся ли пользователь с введенным именем
		 if (mysqli_num_rows($result) == 1)
		 {
			 $user = mysqli_fetch_assoc($result);
			 
			 // Проверяем пароль
			 if (password_verify($password, $user['password']))
			 {
				 $_SESSION['username'] = $username;
				 $last_login = $user['currunt_login'];
 
				 // Обновляем время последнего входа
				 $update_query = "UPDATE userinfo SET last_login = '$last_login', currunt_login = NOW() WHERE username = '$username'";
				 mysqli_query($con, $update_query);
 
				 echo '<div class="text-center alert bg-success col-md-offset-4 col-md-4" role="alert"><span>С возвращением!, <b>' . $_SESSION['username'] . '</b>!</span></div>';
				 echo '<script>setTimeout(function () { window.location.href = "home.php";}, 1000);</script>';
			 }
			 else
			 {
				 // Пароль неверный
				 echo '<div class="text-center alert bg-danger col-md-offset-4 col-md-4" role="alert"><span>Извините <b>' . $username . '</b>, Попробуйте снова!</span></div>';
			 }
		 }
		 else
		 {
			 // Пользователь не найден
			 echo '<div class="text-center alert bg-danger col-md-offset-4 col-md-4" role="alert"><span>Извините <b>' . $username . '</b>, Попробуйте снова!</span></div>';
		 }   
	 }
 
	 return false;
 }
 


/*******************************
 * to check for authorized user.
 *******************************/

function check_session()
{
	if( !isset($_SESSION["username"]) )
	{
    	header("location:index.php");
    	exit();
	}	
    return false;
}

/*******************************
 * load all data of the session user.
 *******************************/

function get_member_data($session_name)
{
	global $con;
	$query = "SELECT * FROM userinfo WHERE username='$session_name'";
	$result = mysqli_query($con,$query);
	$rows = mysqli_affected_rows($con);
	
	if($rows == 1)
	{
		$row = mysqli_fetch_assoc($result);
	}
	else
		echo 'ошибка при извлечении данных';
	return $row;
}

/*******************************
 * to load all required user data for user settings.
 *******************************/

function user_setting($user_id)
{
	global $con;
	$user_id = $user_id;
	$query = "SELECT * FROM userinfo where id='$user_id'";
	$result = mysqli_query($con,$query);
	$rows = mysqli_affected_rows($con);
	
	if($rows == 1)
	{
		$row = mysqli_fetch_assoc($result);
	}
	else
		echo 'ошибка при извлечении данных';
	return $row;	
}

/*******************************
 * updates settings panel of user.
 *******************************/

 function update_settings($id)
 {
	 global $con;
 
	 // Получение текущего пароля из базы данных
	 $query = "SELECT * FROM userinfo WHERE id='$id'";
	 $result = mysqli_query($con, $query);
	 $rows = mysqli_affected_rows($con);
 
	 if ($rows == 1)
	 {
		 $row = mysqli_fetch_assoc($result);
		 $table_pwd = $row['password'];
	 }
	 else
	 {
		 echo 'ошибка при извлечении данных table_pwd';
		 return false;
	 }
 
	 if (isset($_POST['update_settings']))
	 {
		 $name = $_POST['name'];
		 $name = stripslashes($name);
		 $old_pwd = $_POST['old_pwd'];
		 $old_pwd = stripslashes($old_pwd);
		 $new_pwd = $_POST['new_pwd'];
		 $new_pwd = stripslashes($new_pwd);
 
		 // Если пользователь ввел старый и новый пароли
		 if (!empty($old_pwd) && !empty($new_pwd))
		 {
			 // Проверяем старый пароль
			 if (password_verify($old_pwd, $table_pwd))
			 {
				 // Хешируем новый пароль
				 $new_pwdh = password_hash($new_pwd, PASSWORD_DEFAULT);
				 $query = "UPDATE userinfo SET name='$name', password='$new_pwdh' WHERE id='$id'";
				 mysqli_query($con, $query);
				 $rows = mysqli_affected_rows($con);
				 
				 if ($rows == 1)
				 {
					 echo '<div class="text-center alert bg-success col-md-offset-4 col-md-4"><span>Подробности обновлены!</span></div>';
					 echo '<script>setTimeout(function () { window.location.href = "home.php";}, 1000);</script>';
				 }
				 else
				 {
					 echo '<div class="text-center alert bg-danger col-md-offset-4 col-md-4"><span>Проблема при обновлении имени и пароля</span></div>';
				 }
			 }
			 else
			 {
				 echo '<div class="text-center alert bg-danger col-md-offset-4 col-md-4"><span>Проверьте свой старый пароль и повторите попытку</span></div>';
			 }
		 }
		 else
		 {
			 // Если пароли не введены, обновляем только имя
			 $query = "UPDATE userinfo SET name='$name' WHERE id='$id'";
			 mysqli_query($con, $query);
			 $rows = mysqli_affected_rows($con);
			 
			 if ($rows == 1)
			 {
				 echo '<div class="text-center alert bg-success col-md-offset-4 col-md-4"><span>Детали обновлены</span></div>';
				 echo '<script>setTimeout(function () { window.location.href = "home.php";}, 1000);</script>';
			 }
			 else
			 {
				 echo '<div class="text-center alert bg-danger col-md-offset-4 col-md-4"><span>Проблема с изменением</span></div>';
			 }
		 }
	 }
 
	 return false;
 }
 

/*******************************
 * calculate count of all members.
 *******************************/

function get_all_status()
{
	global $con;
	$query = "SELECT * FROM userinfo";
	$result = mysqli_query($con,$query);
	$rows = mysqli_affected_rows($con);
	return $rows;
}

/*******************************
 * calculate count of all members.
 *******************************/

function get_all_posts()
{
	global $con;
	$query = "SELECT * FROM blog_posts";
	$result = mysqli_query($con,$query);
	$rows = mysqli_affected_rows($con);
	return $rows;
}

/*******************************
 * calculate count of CORE members
 *******************************/

function get_vip_status()
{
	global $con;
	$query = "SELECT * FROM userinfo where role NOT LIKE 'Member'";
	$result = mysqli_query($con,$query);
	$rows = mysqli_affected_rows($con);
	return $rows;
}

/*******************************
 * calculate total sessions.
 *******************************/

function total_sessions()
{
	global $con;
	$query = "SELECT * FROM sessions";
	$result = mysqli_query($con,$query);
	$rows = mysqli_affected_rows($con);
	return $rows;
}

/*******************************
 * calculate complete sessions
 *******************************/

function completed_sessions()
{
	global $con;
	$query = "SELECT * FROM sessions";
	$result = mysqli_query($con,$query);
	$rows = mysqli_affected_rows($con);
	$completed_sessions = 0;
	if($rows == 0)
	{
		$completed_sessions = 0;
	}
	else
	{
		while($row = mysqli_fetch_assoc($result))
		{
			if(time() >= strtotime($row['session_date']))
			{
				$completed_sessions++;
			}
		}
	}
	return $completed_sessions;
}

/*******************************
 * retrive all member data in table format.
 *******************************/

function all_member_table($role)
{
	global $con;
	$role = $role;
	$query = "SELECT * FROM userinfo";
	$result = mysqli_query($con,$query);
	$rows = mysqli_affected_rows($con);
	?>
	<table class="table table-hover table-responsive">
			<tr class="alert-info">
				<th><h4>Id</h4></th>
				<th><h4>Имя</h4></th>
				<th><h4>Имя профиля</h4></th>
				<th><h4></h4></th>
				<th><h4>Почта</h4></th>
				<th><h4>Роль</h4></th>
				<th><h4>Действие</h4></th>
			</tr>
	<?php
	while ($row = mysqli_fetch_assoc($result))
		{
			if(empty($row['email']))
			{
				$row['email'] = '-';
			}

			if(empty($row['dob']))
			{
				$row['dob'] = '';
			}
			echo '<tr>
				<td>'.$row['id'].'</td>
				<td>'.$row['name'].'</td>
				<td>'.$row['username'].'</td>
				<td>'.$row['dob'].'</td>
				<td>'.$row['email'].'</td>
				<td>'.$row['role'].'</td>
				<td>';
				
				if($role == "President")
				{
					echo '<a href="edit_member.php?mem_id='.$row['id'].'">Редактировать</a> | <a href="delete_member.php?mem_id='.$row['id'].'">Удалить</a>';
				}
				else
				{
					echo '-';
				}
			
			echo '</td></tr>';
		}
	echo '</table>';
	return false;
}

/*******************************
 * Add new member.
 *******************************/

function add_member($role)
{
	global $con;
	$role = $role;

	if (isset($_POST['add_member'])) 
	{
		$name = $_POST['name'];
		$name = stripslashes($name);
		$email = $_POST['email'];
		$email = stripslashes($email);
		$username = $_POST['username'];
		$username = stripslashes($username);
		$password = $_POST['password'];
		$password = stripslashes($password);
		$pic = 'imgs/user.png';

		if($role == 'President')
		{
			$select_role = $_POST["role"];

		}
		else
		{
			$select_role = "-";
		}
		$hashed_password = password_hash($password, PASSWORD_DEFAULT);
		$query = "INSERT into userinfo (name,  email, username, password, role, pic) VALUES ('$name',  '$email', '$username', '$hashed_password', '$select_role', '$pic')";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		if($rows == 1)
		{
			echo '<div class="text-center alert alert-success col-md-offset-4 col-md-4"><p><b>Участник добавлен </b></p></div>';
			echo '<script>setTimeout(function () { window.location.href = "manage_members.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert alert-danger col-md-offset-4 col-md-4"><p><b>ошибка при добавлении участника, повторите попытку</b></p></div>';
		}
	}

	return false;
}

/*******************************
 * edit member infrmation.
 *******************************/

function edit_member($role,$mem_id)
{
	global $con;
	$role = $role;
	$mem_id = $mem_id;

	if (isset($_POST['edit_member']))
	{
		$edit_name = $_POST['name'];
		$edit_name = stripslashes($edit_name);
		$edit_email = $_POST['email'];
		$edit_email = stripslashes($edit_email);
		$edit_username = $_POST['username'];
		$edit_username = stripslashes($edit_username);
		
		if($role = 'President')
		{
			$edit_select_role = $_POST['role'];
		}
		else
		{
			$edit_select_role = "";
		}

		if(empty($edit_select_role))
		{
			$query = "UPDATE userinfo SET name='$edit_name', email='$edit_email', username='$edit_username' WHERE id='$mem_id'";
		}
		else
		{
			$query = "UPDATE userinfo SET name='$edit_name', email='$edit_email', username='$edit_username', role='$edit_select_role' WHERE id='$mem_id'";
		}
		
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		if($rows == 1)
		{
			echo '<div class="text-center alert alert-success col-md-offset-4 col-md-4"><p><b>Информация обновленна</b></p></div>';
			echo '<script>setTimeout(function () { window.location.href = "manage_members.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert alert-danger col-md-offset-4 col-md-4"><p><b>ошибка при обновлении информации, повторите попытку</b></p></div>';
		}
	}

	return false;
}

/*******************************
 * delete member record.
 *******************************/

function delete_member($mem_id,$role)
{
	global $con;
	$mem_id = $mem_id;
	$role = $role;

	if(isset($_POST['yes']))
	{
		$query = "DELETE from userinfo where id='$mem_id'";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		echo mysqli_error($con);
		if($rows == 1)
		{
			echo '<div class="text-center alert alert-success col-md-offset-4 col-md-4"><p><b>Участник удален</b></p></div>';
			echo '<script>setTimeout(function () { window.location.href = "manage_members.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert alert-danger col-md-offset-4 col-md-4"><p><b>ошибка при удалении участника, повторите попытку</b></p></div>';
		}
	}
	
	return false;
}

/*******************************
 * forgot password function.
 *******************************/

function forgot()
{
	global $con;
	$otp = mt_rand(111111, 999999);
	if(isset($_POST['send_code']))
	{
		$email = $_POST['email'];
		$query = "SELECT * from userinfo where email='$email'";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		if($rows == 1)
		{
			$query = "UPDATE userinfo SET otp='$otp' where email='$email'";
			$result = mysqli_query($con,$query);
			$rows = mysqli_affected_rows($con);
			if($rows == 1)
			{
				// Pear Mail Library
				require_once "Mail.php";
				$from = '<shindesharad71@gmail.com>';
				$subject = 'Club - Password Reset Code';
				$body = "Code is: ".$otp;
				$headers = array(
				    'From' => $from,
				    'To' => $email,
				    'Subject' => $subject
				);
				$smtp = Mail::factory('smtp', array(
				        'host' => 'ssl://smtp.gmail.com',
				        'port' => '465',
				        'auth' => true,
				        'username' => 'shindesharad71@gmail.com',
				        'password' => 'password'
				    ));
				$mail = $smtp->send($to, $headers, $body);
				if (PEAR::isError($mail)) 
				{
				    echo('<div class="text-center alert alert-danger col-md-offset-4 col-md-4"><p>' . $mail->getMessage() . '</p></div>');
				} 
				else 
				{
				    echo('<div class="text-center alert alert-success col-md-offset-4 col-md-4"><p><b>Код для сброса пароля, отправленный на '.$email.' загляни в почту</b></p></div>');
				}
			}
			else
			{
				echo '<div class="text-center alert alert-danger col-md-offset-4 col-md-4"><p><b>ошибка при генерации параметров</b></p></div>';
			}
		
		}
		else
		{
			echo '<div class="text-center alert alert-danger col-md-offset-4 col-md-4"><p><b>неверный адрес электронной почты! пробовать снова</b></p></div>';
		}
	
	}
	
	return false;
}

/*******************************
 * show all session and events.
 *******************************/

 function show_events($role)
 {
	 global $con;
	 $query = "SELECT * FROM sessions ORDER by session_date ASC";
	 $result = mysqli_query($con,$query);
	 $rows = mysqli_affected_rows($con);
 
	 if($rows == 0)
	 {
		 echo '<div class="text-center alert alert-info col-md-offset-4 col-md-4"><p><b>пока никаких мероприятий не запланировано!</b></p></div>';
	 }
	 
	 $months = array(
		"Января", "Февраля", "Марта", "Апреля", "Мая", "Июня",
		"Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря"
	);
	
	while($row = mysqli_fetch_assoc($result)) {
		if(time() >= strtotime($row['session_date'])) {
			$choose_css = "panel-red";
		} else {
			$choose_css = "panel-teal";
		}
	?>
	 
	<div class="col-md-4">
		<div class="panel <?php echo $choose_css; ?>">
			<div class="panel-heading dark-overlay"><?php echo $row['session_name']; ?></div>
			<div class="panel-body">
				<p>
					<b>Дата:</b> <small><?php echo date('j', strtotime($row['session_date'])); ?> <?php echo $months[(int)date('n', strtotime($row['session_date'])) - 1]; ?> <?php echo date('Y H:i', strtotime($row['session_date'])); ?></small><br>
					<?php echo $row['session_details']; ?>
				</p>
			</div>
			<?php
				if($role == 'President') {
					echo '<div class="panel-footer"><a class="btn btn-primary btn-sm" href="edit_event.php?event_id='.$row['session_id'].'">Редактировать</a> <a class="btn btn-danger btn-sm pull-right" href="delete_event.php?event_id='.$row['session_id'].'">Удалить</a></div>';
				}
			?>
		</div>
	</div>
	<?php
	}
	return false;
 }

/*******************************
 * events in table format.
 *******************************/

function all_events_table($role)
{
	$role = $role;

	if($role == "President" || $role == "Technical")
	{
		global $con;
		$query = "SELECT * FROM sessions";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		if($rows == 0)
		{
			echo '<div class="col-md-offset-3 col-md-5 alert alert-warning text-center"><b>если мероприятие не запланировано, сначала запланируйте его!</b></div>';
			exit();
		}
		?>
		<table class="table manage-member-panel table-hover table-responsive">
				<tr class="alert-info">
					<th><h4>Id</h4></th>
					<th><h4>Event Title</h4></th>
					<th><h4>Description</h4></th>
					<th><h4>Date</h4></th>
					<th><h4>Action</h4></th>
				</tr>
		<?php
		while ($row = mysqli_fetch_assoc($result))
			{
				echo '<tr>
					<td>'.$row['session_id'].'</td>
					<td>'.$row['session_name'].'</td>
					<td>'.$row['session_details'].'</td>
					<td>'.$row['session_date'].'</td>
					<td><a href="edit_event.php?event_id='.$row['session_id'].'">Редактировать</a>';
					echo ' | <a href="delete_event.php?event_id='.$row['session_id'].'">Удалить</a></td></tr>';
			}
		echo '</table>';
		}
	return false;
}

/*******************************
 * add new event.
 *******************************/

function add_event()
{
	global $con;
	if (isset($_POST['add_event'])) 
	{
		$name = $_POST['name'];
		$name = stripslashes($name);
		$description = $_POST['description'];
		$description = stripslashes($description);
		$date = $_POST['date'];

		$query = "INSERT into sessions (session_name,  session_details, session_date) VALUES ('$name',  '$description', '$date')";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		if($rows == 1)
		{
			echo '<div class="text-center alert alert-success col-md-offset-4 col-md-4"><p><b>Добавлено событие</b></p></div>';
			echo '<script>setTimeout(function () { window.location.href = "schedule.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert alert-danger col-md-offset-4 col-md-4"><p><b>ошибка при добавлении события, повторите попытку</b></p></div>';
		}
	}

	return false;
}

/*******************************
 * delete event.
 *******************************/

function delete_event($event_id,$role)
{
	global $con;
	$event_id = $event_id;
	$role = $role;

	if(isset($_POST['yes']))
	{
		$query = "DELETE from sessions where session_id='$event_id'";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		echo mysqli_error($con);
		if($rows == 1)
		{
			echo '<div class="text-center alert alert-success col-md-offset-4 col-md-4"><p><b>Событие удалено</b></p></div>';
			echo '<script>setTimeout(function () { window.location.href = "schedule.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert alert-danger col-md-offset-4 col-md-4"><p><b>ошибка при удалении сеанса, повторите попытку</b></p></div>';
		}
	}
	
	return false;
}

/*******************************
 * edit event information.
 *******************************/

function edit_event($event_id,$role)
{
	global $con;
	$role = $role;
	$event_id = $event_id;

	if (isset($_POST['edit_event']))
	{
		$name = $_POST['name'];
		$name = stripslashes($name);
		$description = $_POST['description'];
		$description = stripslashes($description);
		$date = $_POST['date'];
		
		$query = "UPDATE sessions SET session_name='$name', session_details='$description', session_date='$date' WHERE session_id='$event_id'";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		if($rows == 1)
		{
			echo '<div class="text-center alert alert-success col-md-offset-4 col-md-4"><p><b>Информация обновленна</b></p></div>';
			echo '<script>setTimeout(function () { window.location.href = "schedule.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert alert-danger col-md-offset-4 col-md-4"><p><b>ошибка при обновлении информации, повторите попытку</b></p></div>';
		}
	}

	return false;
}

/*******************************
 * show present and absent members attendance
 *******************************/

function attendance($session_id,$role)
{
	global $con;
	$session_id = $session_id;

	$query = "SELECT * from attendance where session_id='$session_id'";
	$result = mysqli_query($con,$query);
	$rows = mysqli_affected_rows($con);

	$key = str_rot13($session_id);

	if($rows == 1)
	{
		$query = "SELECT * from attendance where session_id='$session_id'";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);

		?>
		<div class="row">
			<div class="col-md-5">
				<table class="table table-responsive">
				<tr class="success"><th>ID</th><th>Present Members Name</th></tr>
		<?php

		// Present Code from here
		while ($row = mysqli_fetch_assoc($result))
		{
			$string_ids = unserialize($row['id_array']);
			foreach($string_ids as $key => $value)
			{
			    $query = "SELECT * FROM userinfo where id='$value'";
				$result = mysqli_query($con,$query);
				$rows = mysqli_affected_rows($con);
				if($rows == 0)
				{
					echo '<tr class="success"><td>no one is present, error!</td>';
				}
				while ($row = mysqli_fetch_assoc($result))
				{
					echo '<tr class="success"><td>'.$row['id'].'</td>
					<td>'.$row['name'].'</td></tr>';
				}
			}			
			?>
				</table>
				</div>
				<div class="col-md-5">
					<table class="table table-responsive">
						<tr class="danger"><th>ID</th><th>Absent Members Name</th></tr>
					
						<?php
						// Absent Code from here

						$query = "SELECT id FROM userinfo";
						$result = mysqli_query($con,$query);
						$rows = mysqli_affected_rows($con);
						$all_id_array = array();
						while ($row = mysqli_fetch_assoc($result))
						{
							array_push($all_id_array, $row['id']);
						}

						$absent_array = array('0' => '');
						$absent_array = array_diff($all_id_array,$string_ids);
						foreach($absent_array as $key => $value)
						{
						  	$query = "SELECT * FROM userinfo where id='$value'";
							$result = mysqli_query($con,$query);
							$rows = mysqli_affected_rows($con);

							if($rows == 0)
							{
								echo '<tr class="danger"><td>everyone is present, nice guys!</td>';
							}

							while ($row = mysqli_fetch_assoc($result))
							{
								echo '<tr class="danger"><td>'.$row['id'].'</td>
								<td>'.$row['name'].'</td></tr>';
							}
						}
						?>
					</table>
				</div>
			</div>
		<?php
		}
	}
	else
	{
		if($role == "President" || $role == "Technical")
		{
			echo '<br><div class="text-center"><a href="manage_attendance.php?key='.$key.'" class="btn btn-primary">Заполните заявку на участие в этой сессии</a></div>';
		}
		else
		{
			echo '<div class="text-center alert alert-info col-md-offset-4 col-md-4"><p><b>Информация о посещаемости этой сессии не обновляется, пожалуйста, свяжитесь с вашим техническим руководителем или президентом по вопросам участия!</b></p></div>';
		}
		
	}

	return false;
}

/*******************************
 * submit attendace in database.
 *******************************/

function do_attendance($key)
{
	global $con;

	if(isset($_POST['submit_attendance']))
	{
		
		$query = "SELECT session_id FROM attendance WHERE session_id='$key'";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		if($rows == 1)
		{
			echo '<div class="text-center alert alert-warning col-md-offset-4 col-md-4"><p><b>Attendance Already added!</b></p></div>';
			echo '<script>setTimeout(function () { window.location.href = "attendance.php";}, 1000);</script>';
			exit();
		}
		
		$string_ids = serialize($_POST['checkbx']);

		$query = "INSERT into attendance (session_id, id_array) VALUES ('$key', '$string_ids')";
		$result = mysqli_query($con,$query);
		echo mysqli_error($con);
		$rows = mysqli_affected_rows($con);

		if($rows == 1)
		{
			echo '<div class="text-center alert alert-success col-md-offset-4 col-md-4"><p><b>Посещаемость обновлена!</b></p></div>';
			echo '<script>setTimeout(function () { window.location.href = "attendance.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert alert-danger col-md-offset-4 col-md-4"><p><b>error while updating attendance, try again</b></p></div>';
		}

	}
	return false;
}

/*******************************
 * Display Notice board.
 *******************************/

function show_notice($role)
{
	global $con;
	$query = "SELECT * FROM notice ORDER by date DESC";
	$result = mysqli_query($con,$query);
	$rows = mysqli_affected_rows($con);

	if($rows == 0)
	{
		echo '<div class="text-center alert alert-info col-md-offset-4 col-md-4"><p><b>no notice posted yet!</b></p></div>';
		exit();
	}
	
	$select = 1;
	$months = array(
		"Января", "Февраля", "Марта", "Апреля", "Мая", "Июня",
		"Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря"
	);
	
	while($row = mysqli_fetch_assoc($result)) {
		if($select % 2 == 1) {
			$css = 'panel-teal';
		} else {
			$css = 'panel-orange';
		}
	?>
	
	<div class="col-md-4">
		<div class="panel <?php echo $css; ?>">
			<div class="panel-heading dark-overlay"><?php echo $row['title']; ?></div>
			<div class="panel-body">
				<p>
					<b>Дата:</b> <small><?php echo date('j', strtotime($row['date'])); ?> <?php echo $months[(int)date('n', strtotime($row['date'])) - 1]; ?> <?php echo date('Y H:i', strtotime($row['date'])); ?></small><br>
					<?php echo $row['description']; ?>
				</p>
			</div>
			<?php
				if($role == 'President') {
					echo '<div class="panel-footer"><a class="btn btn-primary btn-sm" href="edit_notice.php?notice_id='.$row['notice_id'].'">Редактировать</a> <a class="btn btn-danger btn-sm pull-right" href="delete_notice.php?notice_id='.$row['notice_id'].'">Удалить</a></div>';
				}
			?>
		</div>
	</div>
	<?php
		$select++;
	}
	
	return false;
}

/*******************************
 * Add new Notice.
 *******************************/

function add_notice()
{
	global $con;
	if (isset($_POST['add_notice'])) 
	{
		$name = $_POST['name'];
		$name = stripslashes($name);
		$description = $_POST['description'];
		$description = stripslashes($description);
		$date = $_POST['date'];

		$query = "INSERT into notice (title,  description, date) VALUES ('$name',  '$description', '$date')";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		if($rows == 1)
		{
			echo '<div class="text-center alert alert-success bg-success col-md-offset-4 col-md-4" role="alert" style="color: #fff;"></b>Добавлено событие</b></div>';
			echo '<script>setTimeout(function () { window.location.href = "notice.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert alert-success bg-success col-md-offset-4 col-md-4" role="alert" style="color: #fff;"><b>Ошибка</b></div>';
		}
	}

	return false;
}

/*******************************
 * delete notice.
 *******************************/

function delete_notice($notice_id,$role)
{
	global $con;
	$notice_id = $notice_id;
	$role = $role;

	if(isset($_POST['yes']))
	{
		$query = "DELETE from notice where notice_id='$notice_id'";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		echo mysqli_error($con);
		if($rows == 1)
		{
			echo '<div class="text-center alert alert-success col-md-offset-4 col-md-4"><p><b>Событие удалено</b></p></div>';
			echo '<script>setTimeout(function () { window.location.href = "notice.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert alert-danger col-md-offset-4 col-md-4"><p><b>error while removing notice, try again</b></p></div>';
		}
	}
	
	return false;
}

/*******************************
 * edit notice information.
 *******************************/

function edit_notice($notice_id,$role)
{
	global $con;
	$role = $role;

	if (isset($_POST['edit_notice']))
	{
		$name = $_POST['name'];
		$name = stripslashes($name);
		$description = $_POST['description'];
		$description = stripslashes($description);
		$date = $_POST['date'];
		
		$query = "UPDATE notice SET title='$name', description='$description', date='$date' WHERE notice_id='$notice_id'";
		$result = mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		if($rows == 1)
		{
			echo '<div class="text-center alert alert-success bg-success col-md-offset-4 col-md-4" role="alert" style="color: #fff;"></b>Событие отредактировано</b></div>';
			echo '<script>setTimeout(function () { window.location.href = "notice.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert alert-danger bg-danger col-md-offset-4 col-md-4" role="alert" style="color: #fff;"></b>error while editing notice</b></div>';
		}
	}

	return false;
}

/*******************************
 * starter for every page.
 *******************************/

function starter($id,$name,$role,$pic,$last_login,$total_members,$core_members,$total_sessions,$completed_sessions)
{
	?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Club Manager - Центральная страница</title>
<link rel='shortcut icon' href='favicon.ico' type='image/x-icon'/ >
<link href="css/pace-theme-corner-indicator.css" rel="stylesheet">
<script src="js/pace.min.js"></script>
<script>pace.start();</script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link href="css/styles.css" rel="stylesheet">
<script src="https://use.fontawesome.com/c250a4b18e.js"></script>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<b><a class="navbar-brand" href="home.php"><span>Club</span>Manager</a></b>
				<ul class="user-menu">
					<li class="dropdown pull-right">
					<a class="dropdown-toggle" data-toggle="dropdown">
    <img src="<?php echo $pic; ?>" class="img-responsive img-circle img-thumbnail" height="35px" width="35px">
    <b id="mobhide"><?php echo $name; ?></b>
    <div class="btn btn-xs btn-info" style="color:#ff7700; background-color: #570000; border-color: #00bb0a;" id="mobhide">
        <?php
            // Замена английских ролей на русские
            switch ($role) {
                case 'Member':
                    echo 'Участник';
                    break;
                case 'Media Marketing':
                    echo 'Медиа-маркетинг';
                    break;
                case 'Admin Logistics':
                    echo 'Административная логистика';
                    break;
                case 'Member Management':
                    echo 'Управление членами клуба';
                    break;
                case 'Technical':
                    echo 'Техник';
                    break;
                case 'President':
                    echo 'Админ';
                    break;
                default:
                    echo $role; // Если роль не известна, выводим как есть
            }
        ?>
    </div>
    <span class="caret"></span>
</a>

						<ul class="dropdown-menu" role="menu">
							<li><a href="update_pic.php"><i class="fa fa-user" aria-hidden="true"></i> Изображение профиля</a></li>
							<li><a href="user_settings.php?user_id=<?php echo $id; ?>"><i class="fa fa-cog" aria-hidden="true"></i> Настройки</a></li>
							<li><a href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Выход</a></li>
						</ul>
					</li>
				</ul>
			</div>			
		</div><!-- /.container-fluid -->
	</nav><br>
		<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
		<form role="search" action="search.php" method="post">
			<div class="form-group">
				<input type="text" name="term" class="form-control" placeholder="Поиск" required>
			</div>
		</form>
		<ul class="nav menu">
			<li><a href="home.php"><i class="fa fa-tachometer" aria-hidden="true"></i>
 <b>Центральная страница</b></a></li>

			<li><a href="blog-home.php"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <b>Блог</b></a></li>

			<li><a href="notice.php"><i class="fa fa-sticky-note-o" aria-hidden="true"></i> <b>События</b></a></li>

			<li><a href="attendance.php"><i class="fa fa-line-chart" aria-hidden="true"></i> <b>Посещения</b></a></li>

			<?php if($role == 'President'){
				echo '<li><a href="manage_members.php"><i class="fa fa-users" aria-hidden="true"></i> <b>Участники</b></a></li>';
			} ?>
			
			<li><a href="schedule.php"><i class="fa fa-calendar" aria-hidden="true"></i> <b>Мероприятия</b></a></li>

			<li role="presentation" class="divider"></li>
			<li><a style="color: #000;"><i class="fa fa-clock-o" aria-hidden="true"></i> <b>последнее посещение</b><br><?php echo $last_login; ?></a></li>
			<li role="presentation" class="divider"></li>
		</ul>
	</div><!--/.sidebar-->
	
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
	<?php
	return false;
}

function at_bottom()
{
	?>
	</div>	<!--/.main-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="css/DateTimePicker.min.css" />
<script type="text/javascript" src="js/DateTimePicker.min.js"></script>
<!-- include summernote css/js-->
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.2/summernote.js"></script>
	<script>
		$(document).ready(function()
		{
		    $("#dtBox").DateTimePicker();
			$('.menu').on("click",".menu",function(e){ 
  			e.preventDefault(); // cancel click
  			var page = $(this).attr('href');   
  			$('.menu').load(page);
			});
			$('#content').summernote({
    			height: 350,
   			 });
		});
	</script>
	<script>
		
		!function ($) {
		    $(document).on("click","ul.nav li.parent > a > span.icon", function(){          
		        $(this).find('em:first').toggleClass("glyphicon-minus");      
		    }); 
		    $(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
		}(window.jQuery);

		$(window).on('resize', function () {
		  if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
		})
		$(window).on('resize', function () {
		  if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
		})
	</script>
</body>
</html>
	<?php
	return false;
}

/**********************************************************************************
*****************************   Blog functions    *********************************
**********************************************************************************/

function show_posts($role,$session_name)
{
	global $con;
	$query = "SELECT * FROM blog_posts ORDER BY id DESC";
	$result = mysqli_query($con,$query);

	if(mysqli_num_rows($result) > 0) {
		$select = 1;
		$months = array(
			1 => "января",
			2 => "февраля",
			3 => "марта",
			4 => "апреля",
			5 => "мая",
			6 => "июня",
			7 => "июля",
			8 => "августа",
			9 => "сентября",
			10 => "октября",
			11 => "ноября",
			12 => "декабря"
		);
		while($row = mysqli_fetch_assoc($result)) {
			if($select % 2 == 1) {
				$css = 'panel-primary';
			} else {
				$css = 'panel-info';
			}
	?>
			<div class="col-lg-5">
				<div class="panel <?php echo $css; ?>">
					<div class="panel-heading">
						<?php echo $row['postTitle']; ?>
					</div>
					<div class="panel-body">
                    <p>Автор <b><?php echo $row['auther']; ?></b> дата <b><?php echo date('j', strtotime($row['post_date'])); ?> <?php echo $months[(int)date('n', strtotime($row['post_date']))]; ?> <?php echo date('Y H:i:s', strtotime($row['post_date'])); ?></b> категория 
                    <a href="viewbycat.php?cat=<?php echo $row['catinfo']; ?>">
                    <?php
                    // Преобразование латинских названий категорий в русские
                    switch ($row['catinfo']) {
                        case "Uncategorised":
                            echo "Другое";
                            break;
                        case "Technology":
                            echo "Технология";
                            break;
                        case "Lifestyle":
                            echo "Образ жизни";
                            break;
                        case "News":
                            echo "Новости";
                            break;
                        case "Education":
                            echo "Образование";
                            break;
                        case "Nature":
                            echo "Природа";
                            break;
                        case "Health":
                            echo "Здоровье";
                            break;
                        case "Programming":
                            echo "Программирование";
                            break;
                        default:
                            echo $row['catinfo']; // Если категория не известна, оставляем как есть
                    }
                    ?>
                    </a>
                    <br><br>
                    <p><?php echo $row['description']; ?></p>
                </div>               
                <div class="panel-footer">
                    <?php
                    if ($session_name == $row['auther'] || $role == 'President') {
                    ?>
                        <a class="btn btn-warning" href="edit-post.php?id=<?php echo $row['id']; ?>&title=<?php echo $row['postTitle']; ?>">Редактировать</a>
                        <a class="btn btn-danger" href="delete-post.php?id=<?php echo $row['id']; ?>&title=<?php echo $row['postTitle']; ?>">Удалить</a> 
                    <?php } ?>
                    <a class="btn btn-primary" href="viewpost.php?id=<?php echo $row['id']; ?>&title=<?php echo $row['postTitle']; ?>">Читать</a>      
                </div>
            </div>
        </div>
<?php
        $select++;
    } // Post list while closed.        
} // Post list if closed.
else {
    echo '<div class="alert bg-warning text-center col-md-offset-4 col-md-4 col-sm-12"><span><h4>Пока постов нет.</h4></span></div>';
}
return false;
}

function new_post()
{
	global $con;

	$auther = $_SESSION['username'];

	if(isset($_POST['publish'])) 
	{

		$postTitle = $_POST['postTitle'];
		$postTitle = stripslashes($postTitle);
		$postTitle = mysqli_real_escape_string($con,$postTitle);

		$description = $_POST['description'];
		$description = stripslashes($description);
		$description = mysqli_real_escape_string($con,$description);

		$content = $_POST['content'];
		$content = stripslashes($content);
		$content = mysqli_real_escape_string($con,$content);

		$catvalue = $_POST['cats'];
		$catvalue = stripslashes($catvalue);

		$query = "INSERT INTO blog_posts (id, postTitle, description, content, post_date, auther, catinfo) VALUES (NULL, '$postTitle', '$description', '$content', NOW(), '$auther','$catvalue')";
		mysqli_query($con,$query);
		
		$rows = mysqli_affected_rows($con);

		if($rows == 1)
		{
			echo '<div class="text-center alert bg-success col-md-offset-4 col-md-4" role="alert"><span>Опубликованное сообщение</span></div>';
			echo '<script>setTimeout(function () { window.location.href = "blog-home.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert bg-danger col-md-offset-4 col-md-4" role="alert"><span>Sorry, error while publishing post, try again</span></div>';	
		}

	}

	return false;
}

function edit_post($post_id)
{
	global $con;
	if (isset($_POST['update'])) 
	{
		$postTitle = $_POST['postTitle'];
		$postTitle = stripslashes($postTitle);
		$postTitle = mysqli_real_escape_string($con,$postTitle);

		$description = $_POST['description'];
		$description = stripslashes($description);
		$description = mysqli_real_escape_string($con,$description);

		$content = $_POST['content'];
		$content = stripslashes($content);
		$content = mysqli_real_escape_string($con,$content);

		$catvalue = $_POST['cats'];
		$catvalue = stripslashes($catvalue);

		$query = "UPDATE blog_posts SET postTitle='$postTitle',description='$description',content='$content',post_date=NOW() ,catinfo='$catvalue' WHERE id='$post_id'";

		mysqli_query($con,$query);

		$rows = mysqli_affected_rows($con);

			if($rows == 1)
			{
				echo '<div class="text-center alert bg-success col-md-offset-4 col-md-4" role="alert"><span>Сообщение обновлено</span></div>';
				echo '<script>setTimeout(function () { window.location.href = "blog-home.php";}, 1000);</script>';
			}
			else
			{
				echo '<div class="text-center alert bg-danger col-md-offset-4 col-md-4" role="alert"><span>Error, post updating failed, try again</span></div>';
				
			}
	}
	return false;
}

function delete_post($post_id)
{
	global $con;

	if(isset($_POST['yes']))
	{
		$query = "DELETE FROM blog_posts WHERE id='$post_id'";
		mysqli_query($con,$query);
		$rows = mysqli_affected_rows($con);
		if($rows == 1)
		{
			echo '<div class="text-center alert bg-success col-md-offset-4 col-md-4" role="alert"><span>Сообщение удалено</span></div>';
				echo '<script>setTimeout(function () { window.location.href = "blog-home.php";}, 1000);</script>';
		}
		else
		{
			echo '<div class="text-center alert bg-danger col-md-offset-4 col-md-4" role="alert"><span>Error, post updating failed, try again</span></div>';
		}
	}
	return false;
}

function show_home_posts()
{
	global $con;

	$query = "SELECT * FROM blog_posts ORDER BY id DESC LIMIT 0,5";
	$result = mysqli_query($con,$query);

	$months = array(
		"Января", "Февраля", "Марта", "Апреля", "Мая", "Июня",
		"Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря"
	);
	
	if(mysqli_num_rows($result) > 0) {
		$select = 1;
		while($row = mysqli_fetch_assoc($result)) {
			if($select % 2 == 1) {
				$css = 'panel-teal';
			} else {
				$css = 'panel-orange';
			}
	?>
			<div class="col-lg-4">
				<div class="panel <?php echo $css; ?>">
					<div class="panel-body">
						<a href="viewpost.php?id=<?php echo $row['id']; ?>&title=<?php echo $row['postTitle']; ?>" style="color: #fff;">
							<h3 style="color: #fff;"><?php echo $row['postTitle']; ?></h3>
							<a href="viewpost.php?id=<?php echo $row['id']; ?>&title=<?php echo $row['postTitle']; ?>" style="color: #fff;">
								<p>Автор <b><?php echo $row['auther']; ?></b> дата <b><?php echo date('j', strtotime($row['post_date'])); ?> <?php echo $months[(int)date('n', strtotime($row['post_date'])) - 1]; ?> <?php echo date('Y H:i:s', strtotime($row['post_date'])); ?></b> категория 
									<b><a style="color: #fff;" href="viewbycat.php?cat=<?php echo $row['catinfo']; ?>">
									<?php
									// Преобразование латинских названий категорий в русские
									switch ($row['catinfo']) {
										case "Uncategorised":
											echo "Другое";
											break;
										case "Technology":
											echo "Технология";
											break;
										case "Lifestyle":
											echo "Образ жизни";
											break;
										case "News":
											echo "Новости";
											break;
										case "Education":
											echo "Образование";
											break;
										case "Nature":
											echo "Природа";
											break;
										case "Health":
											echo "Здоровье";
											break;
										case "Programming":
											echo "Программирование";
											break;
										default:
											echo $row['catinfo']; // Если категория не известна, оставляем как есть
									}
									?>
									</a>
								</b>
								</p>
								<p><a style="color: #fff;" href="viewbycat.php?cat=<?php echo $row['catinfo']; ?>"><?php echo $row['description']; ?></a></p>
							</a>
						</a>
					</div>               
				</div>
			</div>
	<?php
			$select++;
		} // Post list while closed.        
	} // Post list if closed.
	else {
		echo '<div class="alert bg-warning text-center col-md-offset-4 col-md-4 col-sm-12"><span><h4>no posts found, visit after sometime!</h4></span></div>';
	}
	return false;	
}