<?php
error_reporting(7);
session_start();
function view($filename){return ltrim(rtrim(preg_replace(array("/> *([^ ]*) *</","/<!--[^!]*-->/","'/\*[^*]*\*/'","/\r\n/","/\n/","/\t/",'/>[ ]+</'),array(">\\1<",'','','','','','><'),file_get_contents("temp/$filename.html"))));}


switch ($_POST['path']) {
	case 'islogin':
		if($_SESSION['ishou_username']){
			echo('success');exit;
		}else{
			echo('error');exit;
		}
	case 'login':
			if(preg_match("#[\d]{7}#",$_POST['username'])&&preg_match("#[\S]{0,30}#",$_POST['pwdishou'])&&preg_match("#[\S]{1,30}#",$_POST['pwdurp'])){
				$_SESSION['ishou_username']=$_POST['username'];
				$_SESSION['ishou_pwdishou']=$_POST['pwdishou'];
				$_SESSION['ishou_pwdurp']=$_POST['pwdurp'];
				echo('success');exit;
			}
			else{
				echo('error');exit;
			}
			break;
	case 'contact':
			if(preg_match("#[\S]{1,30}#",$_POST['word'])){
				include_once('../old/contact.php');
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
				include_once('../old/lib.php');
				$con=opac3('y@'.$_POST['word']);
			}
			echo json_encode($con);exit;
			break;
	case 'scoreall':
			// echo file_get_contents("json/scoreall.json");exit;
			include_once('../old/scoreall.php');
			$con=wrp_last("wrp@".$_SESSION['ishou_username']."@".$_SESSION['ishou_pwdurp']);
			echo json_encode($con);exit;
			break;
	case 'scorenow':
			// echo file_get_contents("json/scorenow.json");exit;
			include_once('../old/scorenow.php');
			$con=wrp_last("wrp@".$_SESSION['ishou_username']."@".$_SESSION['ishou_pwdurp']);
			echo json_encode($con);exit;
			break;
	case 'scoreeach':
			// echo file_get_contents("json/scoreeach.json");exit;
			include_once('../old/scoreeach.php');
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