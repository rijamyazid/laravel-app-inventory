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

    }
?>