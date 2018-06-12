<?php
$title=$_POST['title'];
$filename=$title.".md";
$filename = iconv('UTF-8', 'GB18030', $filename);

$category=$_POST['category'];
$tags=$_POST['tag'];
$content=$_POST['content'];

$filestream="---\n";
$filestream=$filestream."title: ".$title."\n";
date_default_timezone_set("Asia/Shanghai");
$filestream=$filestream."date: ".date("Y-m-d")." ".date("h:i:s")."\n";

if($category!='')
    $filestream=$filestream."categories: ".$category."\n";
if($tags!='')
    $filestream=$filestream."tags: [".$tags."]\n";
$filestream=$filestream."---\n";
$filestream=$filestream.$content;

$newfile = fopen("./source/_posts/".$filename, "w");
fwrite($newfile, $filestream);
fclose($newfile);
echo ("文章创建成功\n");
exec("push.bat >> log.txt &");
;?>