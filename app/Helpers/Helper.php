<?php
    namespace App\Helpers;
    use App\Admin;
    use App\Bidang;
    use App\Folder;

    class Helper {
        
        static function getBidangByPrefix($prefix){
            return Bidang::where('bidang_prefix', '=', $prefix)->first();
        }
        
        static function getAdminByUsername($username){
            return Admin::where('admin_username', '=', $username)->first();
        }

        static function getFolderByUrl($url, $bidangPrefix){
            if(is_null($url)) return Folder::where('parent_path', '=', 'public')->where('bidang_id', '=', \Helper::getBidangByPrefix($bidangPrefix)->id)->first();
            else return Folder::where('url_path', '=', $url)->first();
        }

    }
?>