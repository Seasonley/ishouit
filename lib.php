<?php
error_reporting(0);
function turn_orglocal($orglocal){
    switch ($orglocal){
        case'LSJY': return'文史馆';break;
        case'DLYL':return'第六阅览';break;
        case'ZCKB':return'珍藏库B';break;
        case'WYYL':return'外语阅览';break;
        case'ZCKC':return'珍藏库C';break;
        case'GBGS':return'馆办公室';break;
        case'ZKSK':return'自科书库';break;
        case'SKJY':return'社科借阅';break;
        case'WHSK':return'维护库，不外借';break;
        case'SSGG':return'三史馆';break;
        case'ZKJY':return'自然科学馆';break;
        case'SSYL':return'十三阅览';break;
        case'WLGD':return'物流高地';break;
        case'999_ZWGJ':return'中文工具';break;
        case'DWYL':return'第五阅览';break;
        case'MJSK':return'密集书库';break;
        case'RWYL':return'日文阅览';break;
        case'XKYL':return'现刊阅览';break;
        case'999_XWSK':return'西文书库';break;
        case'QBBM':return'情报部';break;
        case'CBBM':return'采编部';break;
        case'DYYL':return'第一阅览';break;
        case'WYJY':return'文艺借阅';break;
        case'DEYL':return'第二阅览';break;
        case'JSBM':return'技术部';break;
        case'HDYL':return'海大阅览室';break;
        case'HDWK':return'海大人文库';break;
        case'DSYL':return'第三阅览';break;
        case'ZCKA':return'珍藏库A';break;
        case'SYYL':return'十一阅览';break;
        case'LSJY':return'历史借阅';break;
        case'JSYLS':return'教师阅览室';break;
        case'999_ZWSK':return'中文书库';break;
        case'SEYL':return'十二阅览';break;
        case'WWJY':return'外文借阅';break;
        case'TSYL':return'特色文献';break;
        case'LSJY':return'历史借阅';break;
        case'GXKJ':return'共享空间';break;
        case'SCYL':return'水产阅览';break;
        case'GZXY':return'高职院';break;
        case'GKYL':return'过刊阅览';break;
        case'999_ZWQK':return'期刊阅览';break;
        default:return '无馆藏信息';
    }
}
function strip_html_tags($tags,$str,$content=0){
        if($content){
            $html=array();
            foreach ($tags as $tag) {
                $html[]='/(<'.$tag.'.*?>[\s|\S]*?<\/'.$tag.'>)/';
            }
            $data=preg_replace($html,'',$str);
        }else{
            $html=array();
            foreach ($tags as $tag) {
                $html[]="/(<(?:\/".$tag."|".$tag.")[^>]*>)/i";
            }
            $data=preg_replace($html, '', $str);
        }
        return $data;
    }
function opac3($keyword){
    
    $type=explode('@',$keyword);
if(!empty( $type[2] )&&!empty( $type[1] )){
  
    switch ($type[2]){
        
        case '作者':
        $url='http://202.121.66.40:8080/opac/search?searchWay=author&q='.urlencode($type[1]).'&rows=5';
        
        break;  
        default:
        
        $url='http://202.121.66.40:8080/opac/search?searchWay=title&q='.urlencode($type[1]).'&searchSource=reader&booktype=&scWay=dim&marcformat=&sortWay=score&sortOrder=desc&startPubdate=&endPubdate=&rows=5&hasholding=1';
    }
}
else if(!empty( $type[1] )){
   $url='http://202.121.66.40:8080/opac/search?searchWay=title&q='.urlencode($type[1]).'&searchSource=reader&booktype=&scWay=dim&marcformat=&sortWay=score&sortOrder=desc&startPubdate=&endPubdate=&rows=5&hasholding=1';
}
    
$ch = curl_init();  
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1"); 
$response=curl_exec($ch);
curl_close($ch);  

$response = str_ireplace("\n",'',$response);
$response = str_ireplace("\r",'',$response);
$response = str_ireplace("\t",'',$response);
$response = str_ireplace(' ','',$response);
$ssearch = array ("'<script[^>]*?>.*?</script>'si",   // 去掉 javascript
                "'<style[^>]*?>.*?</style>'si",   // 去掉 css
                "'<!--[/!]*?[^<>]*?>'si",           // 去掉 注释 标记
                "'([rn])[s]+'",                 // 去掉空白字符
                "'&(quot|#34);'i",                 // 替换 HTML 实体
                "'&(amp|#38);'i",
                "'&(lt|#60);'i",
                "'&(gt|#62);'i",
                "'&(nbsp|#160);'i",
                "'&(iexcl|#161);'i",
                "'&(cent|#162);'i",
                "'&(pound|#163);'i",
                "'&(copy|#169);'i",
                "'&#(d+);'e");                    
$sreplace = array ("",
               "",
               "",
               "\1",
               "\"",
               "&",
               "<", 
               ">",
               " ",
               chr(161),
               chr(162),
               chr(163),
               chr(169),
               "chr(\1)");
$r= preg_replace($ssearch, $sreplace, $response);
// echo $response;
// preg_match("#1. </td>(.*)2. </td>(.*)3. </td>(.*)4. </td>(.*)5. </td>(.*)#", $response,$tmpbooklist);
// var_dump($tmpbooklist);
// echo $tmpbooklist[1];
// echo strip_html_tags(array('a','li','ul','p','div','select','option','span','img','td','font','tr','input','ol','iframe'),$response,0);
// echo($r); exit;

preg_match_all("#title_([0-9]+)\"target=\"_blank\">([\S]{1,100})</a><\/span><ahref=\"book#",$r,$d[0]);

preg_match_all("#searchWay=author&q=([\S]{0,100})\">[\S]{0,100}</a></div><div>出版社:#",$r,$d[1]);

    $sum=count($d[0][1]);
    for($i=0;$i<$sum;$i++){
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, 'http://202.121.66.40:8080/opac/api/holding/'.$d[0][1][$i]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1"); 
        $r=curl_exec($ch);
        curl_close($ch);  
       $r = str_ireplace("orglocal\":\"WHSK",'',$r);
        preg_match("#barcode\":\"([0-9]+)\",\"callno\":\"([\S]{1,30})\",\"orglib\":\"HY\",\"orglocal\":\"([\S]{4,8})\",#",$r,$d[2][$i]);
        
        
         // $book[]=array('《'.$d[0][2][$i].'》'."\n".'     '.$d[1][1][$i]."\n".'     '.turn_orglocal($d[2][$i][3]).' '.$d[2][$i][2],'http://202.121.66.40:8080/opac/book/'.$d[0][1][$i].'?globalSearchWay=title');
         $bklist[$i]->name='《'.$d[0][2][$i].'》';
         $bklist[$i]->auth=str_ireplace('"target="_blank','',$d[1][1][$i]);
         $bklist[$i]->location=turn_orglocal($d[2][$i][3]);
         $bklist[$i]->ssh=$d[2][$i][2];
         $bklist[$i]->url='http://202.121.66.40:8080/opac/book/'.$d[0][1][$i].'?globalSearchWay=title';
    }
    $final->bklist=$bklist;
    $final->more_url=$url;
    return $final;
    
}