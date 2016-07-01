<?php
// Enable debug
error_reporting(E_ALL);
ini_set('display_errors','On');
// Include the motor's class
include('poetry_metre.class.php');


if(isset($_POST['generate_verse'])){
	$_POST['versitik']="وَعْ وَعْ وَعْ مازلت رضيعا أمهلني بعض الوقت حتى يستقيم لساني.";
}

if(isset($_POST['button_save'])){

	$input = array(1=>"-",2=>"-");
	
	if(isset($_POST['chest']) and $_POST['chest']!="" and $_POST['chest']!="تَبَدّت لَنا أَحرُفٌ عَن حَبيبٍ"){
		$input[1] = $_POST['chest'];
	}
	if(isset($_POST['behind']) and $_POST['behind']!="" and $_POST['behind']!="تَبَدّت لَنا أَحرُفٌ عَن حَبيبٍ"){
		$input[2] = $_POST['behind'];
	}

	$log = fopen("log.html", 'a');

	fwrite($log, "<b>الوقت : </b>".date("c")."<br/>");
	
	fwrite($log, "<b>البيت : </b>".$input[1]." *** ".$input[2]."<br/>");
	if(isset($_SERVER['REMOTE_ADDR'])) fwrite($log, "<b>المعرف : </b>".$_SERVER['REMOTE_ADDR']."<br/>");
	if(isset($_SERVER['HTTP_REFERER'])) fwrite($log, "<b>القدوم : </b><a href=".$_SERVER['HTTP_REFERER'].">".$_SERVER['HTTP_REFERER']."</a><br/>");
	if(isset($_SERVER['HTTP_USER_AGENT'])) fwrite($log, "<b>المتصفح : </b>". $_SERVER['HTTP_USER_AGENT']."<hr/>");
	fclose($log);
}

if(isset($_POST['button_brush'])){

	$input = array();

	if(isset($_POST['chest']) and $_POST['chest']!=""){
		$input[1] = $_POST['chest'];
	}

	if(isset($_POST['behind']) and $_POST['behind']!=""){
		$input[2] = $_POST['behind'];
	}

	$motor = new PoetryMetre();
	$output = $motor->output($input);
	$motor->debug();

}

?>

<html dir="rtl" lang="ar">
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="description" content="مقوم الشعر العربي وزنا وقافية" />
	<meta name="keywords" content="الشعر, العروض, الوزن, رقمي"/>
	
	<title>المشط - مقوم الشعر العربي - الإصدار التجريبي</title>
	<style>
        body{
            background:#EEE;
            font-size:18px;
        }
		h1{
			text-align:center;
			margin: 4% 0 0;
			text-shadow:1px 1px 1px #fff;
		}
        #primary{
			width:595px; margin:8% auto 1%; border:1px solid #555;
		}
		.box{
			width:595px; margin:0% auto; border:1px solid #555;
			padding:20px 30px; 
			background:white;
			border-radius:10px;
		}
		input[type="text"]{
			width:300px;
			height:50px;
			font-size:20px;
			border:1px solid #444;
			padding: 0 5px;
		}
		input[type="text"].long{
			width:480px;	
		}
		input[type="submit"], input[type="reset"]{
			width:100px;
			height:50px;
			font-size:20px;
		}
		.button-green{
			-moz-box-shadow:inset 0px 1px 0px 0px #d9fbbe;
			-webkit-box-shadow:inset 0px 1px 0px 0px #d9fbbe;
			box-shadow:inset 0px 1px 0px 0px #d9fbbe;
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #b8e356), color-stop(1, #a5cc52) );
			background:-moz-linear-gradient( center top, #b8e356 5%, #a5cc52 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#b8e356', endColorstr='#a5cc52');
			background-color:#b8e356;
			-moz-border-radius:6px;
			-webkit-border-radius:6px;
			border-radius:6px;
			border:1px solid #83c41a;
			display:inline-block;
			color:#ffffff;
			font-family:arial;
			font-size:15px;
			font-weight:bold;
			padding:6px 24px;
			text-decoration:none;
			text-shadow:1px 1px 0px #86ae47;
			margin-left:5px;
		}.button-green:hover {
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #a5cc52), color-stop(1, #b8e356) );
			background:-moz-linear-gradient( center top, #a5cc52 5%, #b8e356 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#a5cc52', endColorstr='#b8e356');
			background-color:#a5cc52;
		}.button-green:active {
			position:relative;
			top:1px;
		}
		.error{
			color:red;
			font-size:20px;
		}
		.foot{
			font-family:arial;
			position:absolute;
			text-align:center;
			right:5px;
			font-weight: 700;
			text-shadow: 1px 1px 1px #FFF;
			bottom:1px;
			left:5px;
		}
    </style>
</head>

<body>
	
	<h1>برنامج مشط لتقويم الشعر العربي (v0.1)</h1>
	<div id="primary" class="box">
		<div id="input">
			<h3>لوحة إدراج البيت - تجريبي</h3>
			<form method="POST" action="">
				<table border="0">
					<tr>
						<td><input name="chest" type="text" required placeholder="يرجى إدخال صدر البيت" value="<?php if(isset($_POST['chest'])) echo $_POST['chest']; else echo "تَبَدّت لَنا أَحرُفٌ عَن حَبيبٍ"; ?>" /></td>
						<td><input name="behind" type="text" placeholder="يرجى إدخال عجز البيت" value="<?php if(isset($_POST['behind'])) echo $_POST['behind']; ?>" /></td>
					</tr>
					<?php 
					$colors = array('', 'rgb(82, 82, 0)', 'green', 'blue', 'black', 'rouge', 'orange');	
					for($i=1; $i<5; $i++){ ?> 
					<tr>
						<td style="font-size: 18px;color:<?php echo $colors[$i]; ?>;"><?php if(isset($output[1]["analyse"][$i])) echo $output[1]["analyse"][$i]; ?></td>
						<td style="font-size: 18px;color:<?php echo $colors[$i]; ?>;"><?php if(isset($output[2]["analyse"][$i])) echo $output[2]["analyse"][$i]; ?></td>
					</tr>
					<?php } ?>
					<tr>
						<td>لا تنسى تشكيل المتحرك الذي لا تليه علة وتشديد الحروف وتنوينها.</td>
						<td><br />
							<input class="button-green" name="button_brush" type="submit" value="تأكد" style="float:left;" />
							
							<input name="button_save" type="submit" value="حفظ" style="float:left;" />
						</td>
					</tr>			
				</table>
			</form>
		</div>
		
		<div id="output">
			<p class="error"><?php if(isset($output["توجيه"])) echo $output["توجيه"]['رسالة']; ?></p>
		</div>
	</div>
	
	<div class="box">
		<h3>مولد الأشعار الآلي - جديد</h3>
		<form method="POST" action="">
			<table border="0">
				<input  disabled class="long" name="versitik" type="text" placeholder="هل يمكن للآلة أن تصل إلى شاعرية البشر ؟" value="<?php if(isset($_POST['versitik'])) echo $_POST['versitik']; ?>" />
				<input name="generate_verse" type="submit" value="لِتبدع الآلة" style="float:left;" />
		</form>
	</div>
	
	<div class="foot">
            <p></p>
        </div>
</body>
</html>
