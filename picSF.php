<?php
//公共函数库

/*函数说明
 * 
 *等比缩放的函数（以保存的方式实现）
 *@param string $picname 被缩放处理的图像源
 *@param int  $maxx 缩放后图片的最大宽度
 *@param int  $maxy 缩放后图片的最大高度
 *@param string $pre 缩放后的图片名的前缀名
 *@return  string 返回缩放后的图片名称(带路径) ，如：a.jpg=>s_a.jpg
 * 
 * 图片的处理函数在手册 GD and Image中，很多，掌握一些常见的就好了。
 */

function imageUpdateSize($picname,$maxx=100,$maxy=100,$pre="s_"){
  //1、 getimagesize();获取图片大小
  $info = getimagesize($picname);//获取图片的基本信息
  /*
   *array(7) { [0]=> int(1200)   //获取宽度
   * 	 [1]=> int(1193)         //获取高度
   *     [2]=> int(2)  //图像类型的标记 1=GIF 2=jpg 3=png ……
   *     [3]=> string(26) "width="1200" height="1193"" 
   *     ["bits"]=> int(8) 
   *     ["channels"]=> int(3) 
   *     ["mime"]=> string(10) "image/jpeg" }
   * 
   * */
  $w = $info[0];//获取宽度
  $h = $info[1];//获取高度
  // var_dump($info);
   //imagecopyresampled——重采样拷贝部分图像并调整大小，使用这个函数进行图片的缩放
   //imagecreatetruecolor——创建一个真彩色图像
   
   //获取图片的类型并为此创建对应的图片资源
   switch($info[2]){
   	case 1://gif格式
   	$im =imagecreatefromgif($picname);
   	break;
   	case 2://jpg格式
   	 $im =imagecreatefromjpeg($picname);
   	 	break;
   	case 3://png 格式
   	$im =imagecreatefrompng($picname);
   		break;
   	default:
   	 die("图片类型错误"	);
   }
   //计算缩放比例
   //比如：原图600x300,缩放后宽高不能超过100，所以让600 缩放，最后是 100x50
   if(($maxx/$w)>($maxy/$h)){//缩放后的图像宽高除以原图像对应的宽高，谁的比例小，就用谁的
   	$b = $maxy/$h;
   }else{
   	$b = $maxx/$w;
   }
   //计算出缩放后的尺寸
   $nw= floor($w*$b);//舍去求整法
   $nh =floor($h*$b);
   //创建一个新的图像源
   $nim = imagecreatetruecolor($nw,$nh);
   //执行等比缩放
   imagecopyresampled($nim,$im,0,0,0,0,$nw,$nh,$w,$h);
   //输出图像 （根据原图像的类型输出为对应的类型）输到浏览器上，而是输到一个资源中去
   //输出的路径：./img/s_1.jpg
   //pathinfo()函数 返回文件路径的信息
   $picinfo = pathinfo($picname);//解析原图像的路径，名字
   $newpicname= $picinfo["dirname"]."/".$pre.$picinfo["basename"];
   switch($info[2]){
   	case 1://gif格式
   	 imagegif($nim,$newpicname);
   	break;
   	case 2://jpg格式
   	  	 imagejpeg($nim,$newpicname);
   	 	break;
   	case 3://png 格式
     	 imagepng($nim,$newpicname);
   		break;
   
  }
  //关闭（释放）图片资源
  imagedestroy($im);
  imagedestroy($nim);
  //返回结果
  return $newpicname;
}

//测试  PHP中函数调用不区分大小写
//echo imageUpdateSize("./img/1.jpg");
echo imageupdatesize("./img/1.jpg",200,200,"ss_");
?>