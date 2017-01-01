<?php
/*
    后台文件上传类
*/
namespace Admin\Controller;

class FileController extends CommonController
{
    private $_file_model = null;

    public function __construct()
    {
        parent::__construct();
    }


    /*
        图片上传
    */
    public function upload()
    {
        if (!IS_AJAX)
            die('error：非法請求');
        //获取参数
        $param = I('post.param', 'param', 'trim');//如果没有传，默认为param
        $thumb_width = I('post.thumb_width');
        $thumb_height = I('post.thumb_height');
        if (!$thumb_width) {
            $thumb_width = C('THUMB_MAX_WIDTH');
        }
        if (!$thumb_height) {
            $thumb_height = C('THUMB_MAX_HEIGHT');
        }

        //引入上传类
        $upload = new \Think\Upload();
        $upload->maxSize = 3145728;
        $upload->rootPath = C('FILE_UPLOAD_PATH');
        $upload->allowExts = array('jpeg', 'png', 'gif', 'jpg');
        $upload->savePath =  C('FILE_IMG_DIRNAME');

        //自动创建目录失败，手动创建
        if (!is_dir($upload->rootPath.$upload->savePath))
            mkdir($upload->rootPath.$upload->savePath, 0777, true);

        $upload->saveRule = uniqid();
        //删除原图
        $upload->subName = date('Ymd', time());
        $info = $upload->upload();
        if (!$info)
            die('error: ' . $upload->getError());
        //加入拼接好的图片dir
        $info[$param]['img_dir'] = $upload->rootPath.$info[$param]['savepath'] . $info[$param]['savename'];
        $thumbname = 'thumb_'.$info[$param]['savename'].'.jpg';
        $bigname = 'big_'.$info[$param]['savename'].'.jpg';
        if(APP_MODE == 'sae'){//判断环境
            //$thumbImgUrl = $this->sae_thumb( $upload->rootPath .$info[$param]['img_dir'] , $upload->rootPath.$info[$param]['img_dir'],$thumb_width,$thumb_height);//sae_thumb方法在下边
        }elseif(APP_MODE == 'common'){
            $image = new \Think\Image();
            $thumb_dir = $upload->rootPath.$upload->savePath.date('Ymd', time()).'/';
            $thumbImgUrl = $thumb_dir.$thumbname;
            $bigImgUrl = $thumb_dir.$bigname;
            if (!is_dir($thumb_dir))
                mkdir($thumb_dir, 0777, true);

            $image->open($info[$param]['img_dir']);
            $image->thumb($thumb_width,$thumb_height,\Think\Image::IMAGE_THUMB_FILLED)->save($thumbImgUrl);
            //$image->thumb(800,800,\Think\Image::IMAGE_THUMB_FILLED)->save($bigImgUrl);
        }
        if(APP_MODE == 'sae'){
            $info[$param]['url'] =  C('SAE_UPLOAD').$thumbImgUrl;//返回数据库中要保存的值。
        }else{
            $info[$param]['url'] =  C('BASE_UPLOAD_URL').$thumbImgUrl;//返回数据库中要保存的值。
        }
        echo json_encode($info[$param]);
    }

    public function sae_thumb($field, $thumbname='',$max_width=200,$max_height=200) {
        //此函数3个参数，第一个是源文件的URL,包括文件名，第二个是缩略图要保存的URL，包括文件名，后边是缩略图的宽和长
        $s = new \SaeStorage();
        $arr = explode('/', ltrim($field, './'));
        $domain = array_shift($arr);
        $source = implode('/', $arr);
        $source_url = $s->getUrl($domain, $source);
        if (!$s->fileExists($domain, $source)){
            return false;
        }
        $src_info=getimagesize($source_url);
        //获得原图宽高
        $src_width = $src_info[0];//0元素是宽
        $src_height = $src_info[1];//1元素时高

        //比较宽之比与高之比的大小
        if( ($src_width/$max_width) > ($src_height/$max_height)) {
            //宽之比大
            $dst_width = $max_width;
            $dst_height = $dst_width * ($src_height/$src_width);
        } else {
            $dst_height = $max_height;
            $dst_width = $dst_height * ($src_width/$src_height);
        }

        $src_img = imagecreatefromjpeg($source_url);//原图
        $dst_img = imagecreatetruecolor($max_width, $max_height);//补白
        $red = imagecolorallocate($dst_img, 255, 251, 240);
        imagefill($dst_img, 0, 0, $red);

        imagecopyresampled($dst_img, $src_img, ($max_width-$dst_width)/2, ($max_height-$dst_height)/2, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
        ob_start();
        imagejpeg($dst_img);
        $imgstr= ob_get_contents();
        if (!$thumbname) {
            $toFile = $source;
        } else {
            $arr = explode('/', ltrim($thumbname, './'));
            $domain = array_shift($arr);
            $toFile = implode('/', $arr);
        }
        $s->write($domain,$toFile,$imgstr);
        imagedestroy($dst_img);
        imagedestroy($src_img);
        ob_end_clean();
        return $toFile;//返回缩略图的URL
    }
}