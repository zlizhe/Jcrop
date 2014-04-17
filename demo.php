<?php
/*
*   Jcrop 原作者 http://github.com/tapmodo/Jcrop
*   
*	
* 	对 Jcrop 的JS 做了修改 增加显示容器的缩放
*/
if ($_SERVER['REQUEST_METHOD'] == 'POST'){

	$x = intval($_POST('x'));
	$y = intval($_POST('y'));
	$w = intval($_POST('w'));
	$h = intval($_POST('h'));
	$pwidth = intval($_POST('pwidth'));
	$pheight = intval($_POST('pheight'));

	//图片路径
	$filename = trim($_POST('filename'));

	//当图片大于图片容器 DIV 大小时 按比例缩放 宽、高、X、Y
	//如果您没有约束显示图片容器的大小无需此操作
	$imagesres   = getimagesize($filename);
	$wp = 1;
	$hp = 1;
    if ($imagesres[0] > $pwidth) {
    	$wp = $imagesres[0] / $pwidth;
    }
    if ($imagesres[1] > $pheight) {
    	$hp = $imagesres[1] / $pheight;
    }

    //新图片路径
    $newfile = '{图片放置位置}';

    //用户划定区域
    if ($w && $h) {

		$targ_w = $targ_h = $w; //保存图片的大小 如果要限定大小如200*200 直接赋值200
		$jpeg_quality = 90; //JPEG质量


		$img_r = imagecreatefromjpeg($filename);
		$dst_r = ImageCreateTrueColor($targ_w, $targ_h);

		//*wp 和 *hp 如果在容器无限制大小 （不需要缩放图片或图片已经处理过）则无需 *wp 和 *hp
		imagecopyresampled($dst_r, $img_r, 0, 0, $x * $wp, $y * $hp,
						$targ_w, $targ_h, $w * $wp, $h * $hp);


		imagejpeg($dst_r, $newfile, $jpeg_quality);
		//如果需要更多大小的图片 对 已处理过的图片 在进行缩放
		//$core->cutphoto($newfile, $small_file, 100, 100);
		//销毁图像
		imagedestroy($dst_r);
	}else{
		//用户未选区域 自动缩放
		//如果允许不划定区域即对图片进行整体缩放，在这里进行操作，否则 在这里报错！
	}


	exit;
}
?>

<form name="form" action="" class="form-horizontal" method="post" enctype="multipart/form-data">

<div class="modal-body text-center">

    <div class="zxx_main_con">
        <div class="zxx_test_list">
            <div class="rel mb20">
                <img id="cutimg" src="{上传的图片地址}" />
            </div>
            <input type="hidden" id="x" name="x" />
            <input type="hidden" id="y" name="y" />
            <input type="hidden" id="w" name="w" />
            <input type="hidden" id="h" name="h" />
            <input type="hidden" id="pwidth" name="pwidth" />
            <input type="hidden" id="pheight" name="pheight" />
        </div>
    </div>
	<input type="hidden" name="filename" value="{上传的图片地址}" />
    
</div>
<div class="modal-footer">
    <button class="btn btn-primary">提交</button>
</div>

</form>
<link rel="stylesheet" href="/css/jquery.Jcrop.css" type="text/css" />
<script type="text/javascript" src="/js/jquery.Jcrop.js"></script>
<style>
#dialog{
    max-height: 500px;
    overflow: auto;
    width: 575px;
}
</style>
<script type="text/javascript">
    $(document).ready(function(){
    	//初始化
        $("#cutimg").Jcrop({
            aspectRatio:1,
            onChange:showCoords,
            onSelect:showCoords
        }); 
        //显示 选择区域
        function showCoords(obj){
            $("#x").val(obj.x);
            $("#y").val(obj.y);
            $("#w").val(obj.w);
            $("#h").val(obj.h);
        }
    });
</script>