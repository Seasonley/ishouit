<?php
error_reporting(0);
function wrp_last($keyword){
$type=explode('@',$keyword);
$post_data =array( 'zjh'=>$type[1], 'mm'=>$type[2]);
$url='http://202.121.66.80/loginAction.do';
$ch = curl_init();  
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);  
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch,CURLOPT_HEADER,1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1"); 
$r=curl_exec($ch);
curl_close($ch);        //使用上面保存的cookies再次访问
preg_match('/Set-Cookie:(.*);/iU',$r,$str);
$cookie = $str[1];
$url='http://202.121.66.80/bxqcjcxAction.do';
// $url='http://202.121.66.80/gradeLnAllAction.do?type=ln&oper=sxinfo&lnsxdm=001';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1"); 
curl_setopt($ch,CURLOPT_COOKIE,$cookie);
$response =  curl_exec($ch);
curl_close($ch);
$response =mb_convert_encoding($response,'utf-8','gb2312');

$response = str_ireplace('良好','84',$response);
$response= str_ireplace('优秀','100',$response);
$response= str_ireplace('良好','84',$response);
$response= str_ireplace('通过','84',$response);
$response= str_ireplace('中等','74',$response);
$response= str_ireplace('不及','0',$response);
$response= str_ireplace('合格','60',$response);
$response= str_ireplace('.0','',$response);
$response = str_ireplace("\n",'',$response);
$response = str_ireplace("\r",'',$response);
$response = str_ireplace("\t",'',$response);
$response = str_ireplace(' ','',$response);
$response = str_ireplace('</td><tdalign="center">选修</td><tdalign="center">','AAA',$response);
$response = str_ireplace('</td><tdalign="center">必修</td><tdalign="center">','AAA',$response);
$response = str_ireplace('</td><tdalign="center">任选</td><tdalign="center">','AAA',$response);

preg_match_all("#[0-9]{3}</td><tdalign=\"center\">[\S]{1,3}</td><tdalign=\"center\">([\S]{2,100})</td><tdalign=\"center\">[\S]{1,100}</td><tdalign=\"center\">([\S]{1,5})AAA([0-9]{1,3})#",$response,$d);
$sum=count($d[0]);
for($i=0;$i<$sum;$i++){
preg_match("#[0-9]{3}</td><tdalign=\"center\">[\S]{1,3}</td><tdalign=\"center\">([\S]{2,100})</td><tdalign=\"center\">[\S]{1,100}</td><tdalign=\"center\">([\S]{1,5})AAA([0-9]{1,3})#",$d[0][$i],$e[$i]);
}
for($i=0;$i<$sum;$i++){
	$score=$e[$i][3];
	if($score>=90){$e[$i][3]=4;}
	else if($score>=85){$e[$i][3]=3.7;}
	else if($score>=82){$e[$i][3]=3.3;}
	else if($score>=78){$e[$i][3]=3;}
	else if($score>=75){$e[$i][3]=2.7;}
	else if($score>=72){$e[$i][3]=2.3;}
	else if($score>=68){$e[$i][3]=2.0;}
	else if($score>=66){$e[$i][3]=1.7;}
	else if($score>=64){$e[$i][3]=1.5;}
	else if($score>=60){$e[$i][3]=1.0;}
	else {$e[$i][3]=0;}
            $clist[$i]->score=$score;
            $clist[$i]->per=number_format($e[$i][2],1,'.','');
            $clist[$i]->cname=$e[$i][1];

            $up=$up+$e[$i][3]*$e[$i][2];
	$down=$down+$e[$i][2];
}
if($down!=0){
    $gpa=number_format($up/$down,3,'.','');
    $obj->clist=$clist;
    $obj->sum=$sum;
    $obj->down=$down;
    $obj->gpa=$gpa;
}
return $obj;
}
