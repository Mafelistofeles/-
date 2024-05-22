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
	$post_id = $_GET['id'];
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

<?php
	$query = "SELECT * FROM blog_posts WHERE id = '$post_id'";
	$result = mysqli_query($con,$query);

	if (mysqli_num_rows($result) > 0)
	{
		while($row = mysqli_fetch_assoc($result))
		{
			
            $postTitle = $row['postTitle'];
            $postDate = date('jS M Y H:i:s', strtotime($row['post_date']));
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
	//$postDate = $timestamp;
// Формируем отформатированную строку
	$last_login = $day . ' ' . $months[$month] . ' ' . $year . ' ' . $time;
            $auther = $row['auther'];
            $description = $row['description'];
            $content = $row['content'];
            $catinfo = $row['catinfo'];
			                             
			}
	}
	else
	{
		echo '<div class="alert alert-warning text-center"><h3>error while retriving post!</h3></div>';
	}

?>
	<div class="row">
		<ol class="breadcrumb">
			<li><a href="home.php"><i class="fa fa-home" aria-hidden="true"></i></a></li>
			<li><a href="blog-home.php">Блог</a></li>
			<li class="active"><?php echo $postTitle; ?></li>
		</ol>
	</div><!--/.row-->

	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?php echo $postTitle; ?></h1>
			<p>Автор <b><?php echo $auther; ?></b> Дата <b>
<?php
// Функция date() для форматирования даты на русском языке
$month = date('n', strtotime($postDate));
$day = date('j', strtotime($postDate));
$year = date('Y', strtotime($postDate));
$time = date('H:i:s', strtotime($postDate));

// Определяем месяц на русском
if ($month == 1) {
    $month_ru = "января";
} elseif ($month == 2) {
    $month_ru = "февраля";
} elseif ($month == 3) {
    $month_ru = "марта";
} elseif ($month == 4) {
    $month_ru = "апреля";
} elseif ($month == 5) {
    $month_ru = "мая";
} elseif ($month == 6) {
    $month_ru = "июня";
} elseif ($month == 7) {
    $month_ru = "июля";
} elseif ($month == 8) {
    $month_ru = "августа";
} elseif ($month == 9) {
    $month_ru = "сентября";
} elseif ($month == 10) {
    $month_ru = "октября";
} elseif ($month == 11) {
    $month_ru = "ноября";
} elseif ($month == 12) {
    $month_ru = "декабря";
} else {
    $month_ru = "";
}

// Выводим дату на экран
echo $day . ' ' . $month_ru . ' ' . $year . ' ' . $time;
?>
</b> категория 
<a href="viewbycat.php?cat=<?php echo $catinfo; ?>">
<?php
// Преобразование латинских названий категорий в русские
switch ($catinfo) {
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
        echo $catinfo; // Если категория не известна, оставляем как есть
}
?>
</a>


</a>

		</div>
	</div><!--/.row-->

	<div class="row">
		<div class="col-lg-12">
			<h3><i><?php echo $description; ?></i></h3><br>
			<p><h3><?php echo $content; ?></h3></p>
		</div>
	</div>
<?php
	at_bottom();