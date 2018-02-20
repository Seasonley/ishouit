<?php
error_reporting(7);
session_start();
function view($filename){return ltrim(rtrim(preg_replace(array("/> *([^ ]*) *</","/<!--[^!]*-->/","'/\*[^*]*\*/'","/\r\n/","/\n/","/\t/",'/>[ ]+</'),array(">\\1<",'','','','','','><'),file_get_contents("temp/$filename.html"))));}
if($_GET['s']!='login'&&$_SESSION['ishou_username']==null){
	header("Location: ?s=login");exit;
}

switch ($_POST['path']) {
	case 'login':
			if(preg_match("#[\d]{7}#",$_POST['username'])&&preg_match("#[\S]{0,30}#",$_POST['pwdishou'])&&preg_match("#[\S]{1,30}#",$_POST['pwdurp'])){
				$_SESSION['ishou_username']=$_POST['username'];
				$_SESSION['ishou_pwdishou']=$_POST['pwdishou'];
				$_SESSION['ishou_pwdurp']=$_POST['pwdurp'];
				header("Location: ?s=home"); 
			}
			else{
				echo view('login');
				echo "<script>document.getElementById('dialog1').style.display='block'</script>";

			}
			break;
	case 'contact':
			if(preg_match("#[\S]{1,30}#",$_POST['word'])){
				include_once('contact.php');
				for($i=0;$i<1457;$i++){
					if (preg_match('#'.$_POST['word'].'#',$t[$i],$m)){
						$con[]=$t[$i];
					}
				}
			}
			echo json_encode($con);exit;
			break;
	case 'lib':
			if(preg_match("#[\S]{1,30}#",$_POST['word'])){
				include_once('lib.php');
				$con=opac3('y@'.$_POST['word']);
			}
			echo json_encode($con);exit;
			break;
	case 'scoreall':
			include_once('scoreall.php');
			$con=wrp_last("wrp@".$_SESSION['ishou_username']."@".$_SESSION['ishou_pwdurp']);
			echo json_encode($con);exit;
			break;
	case 'scorenow':
			include_once('scorenow.php');
			$con=wrp_last("wrp@".$_SESSION['ishou_username']."@".$_SESSION['ishou_pwdurp']);
			echo json_encode($con);exit;
			break;
	case 'scoreeach':
			include_once('scoreeach.php');
			$con=wrp_last("wrp@".$_SESSION['ishou_username']."@".$_SESSION['ishou_pwdurp']);
			echo json_encode($con);exit;
			break;
	
	default:
			switch ($_GET['s']) {
				case 'login':		echo view('login');		break;
				case 'home':	echo view('home');		break;
				case 'about':	echo view('about');		break;
				case 'contact':	echo view('contact');	break;
				case 'lib':		echo view('lib');			break;
				case 'scorenow':echo view('score');		break;
				case 'scoreeach':echo view('score');		break;
				case 'scoreall':echo view('score');		break;
				case 'exm':		echo view('score');		break;
				default:header("Location: ?s=login"); 	break;
			}
	break;
}