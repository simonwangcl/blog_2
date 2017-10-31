<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\UserModel;
use App\Model\ArticleModel;

class CleanUpPictures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:pictures';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up avatar and cover pictures';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userImages = UserModel::pluck('image','type');
        $imageType = array();
        $qq = UserModel::$typeQq;
        $wx = UserModel::$typeWx;
        if($userImages->toArray()){
            foreach ($userImages as $key => $image){
                switch($key){
                    case $qq:
                        $imageType[$qq][] = substr($image, -24);
                        break;
                    case $wx:
                        $imageType[$wx][] = substr($image, -24);
                        break;
                    default:
                        $imageType['default'][] = substr($image, -24);
                }
            }
            unset($userImages);
            foreach($imageType as $type => $images){
                $type = $type == 'default' ? '' : $type . '/';
                $dir = base_path('public/storage/avatars/') . $type;
                if(is_dir($dir)){
                    $fileNames = scandir($dir);
                    foreach ($fileNames as $file){
                        $path = $dir.$file;
                        if($file != '.' && $file != '..' && $file != '.svn' && is_file($path) && !in_array($file, $images)){
                            chmod($path, 0777);
                            if(unlink($path)){
                                echo '头像 '.$file.' 删除成功！'.PHP_EOL;
                            }else{
                                echo '头像 '.$file.' 删除失败！'.PHP_EOL;
                            }
                        }
                    }
                }
            }
            unset($imageType);
        }


        $covers = ArticleModel::pluck('cover');
        if($covers->toArray()){
            foreach ($covers as $key => $cover){
                $covers[$key] = substr($cover, -24);
            }
            $covers = array_filter($covers->toArray());
            $dir = base_path('public/storage/covers/');
            if(is_dir($dir)){
                $fileNames = scandir($dir);
                foreach ($fileNames as $file){
                    $path = $dir.$file;
                    if($file != '.' && $file != '..' && $file != '.svn' && is_file($path) && !in_array($file, $covers)){
                        chmod($path, 0777);
                        if(unlink($path)){
                            echo '封面 '.$file.' 删除成功！'.PHP_EOL;
                        }else{
                            echo '封面 '.$file.' 删除失败！'.PHP_EOL;
                        }
                    }
                }
            }
        }
        unset($covers);
    }
}
