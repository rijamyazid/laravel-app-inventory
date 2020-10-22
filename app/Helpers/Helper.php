<?php
    namespace App\Helpers;
    use App\User;
    use App\Bidang;
    use App\Folder;
    use App\File;

    class Helper {
        
        static function getFlags($flags){
            return explode(',', $flags);
        }

        static function folderLocation($urlPath){
            $split = explode('/', $urlPath);
            $arr = array();
            $newUrlPath = '';
            foreach($split as $path){
                $newUrlPath = $newUrlPath . $path .'/';
                array_push($arr, array('path' => $path, 'urlPath' => $newUrlPath));            
            }
            return $arr;
        }

        static function deleteUrlPathLast($url_path){
            if(count((explode('/', $url_path))) > 1){
                $split = explode('/', $url_path, -1);
                $merge = implode('/', $split);
                return $merge;
            } else {
                return null;
            }
        }

        static function getBidangByPrefix($prefix){
            return Bidang::where('bidang_prefix', '=', $prefix)->first();
        }
        
        static function getUserByUsername($username){
            return User::where('user_username', '=', $username)->first();
        }

        static function getFolderByUrl($url, $bidangPrefix){
            if(is_null($url)) return Folder::where('parent_path', '=', 'public')->where('bidang_id', '=', \Helper::getBidangByPrefix($bidangPrefix)->id)->first();
            else return Folder::where('url_path', '=', $url)->first();
        }

        /**
         * Mendapatkan URL dari parent path folder, dengan cara menghapus bagian tertentu didalam parent path
         */
        static function getUrlFromParentPath($bidangPrefix, $folderName, $parentPath){
            if(count(explode('/', $parentPath)) > 2) {
                $pos = strpos($parentPath, "public/$bidangPrefix/");
                if ($pos !== false) {
                    $newStr = substr_replace($parentPath, '', $pos, strlen("public/$bidangPrefix/")) . '/';
                }
                // $newStr = str_replace("public/$bidangPrefix/", '', $parentPath) . '/';
            }else {
                $pos = strpos($parentPath, "public/$bidangPrefix");
                if ($pos !== false) {
                    $newStr = substr_replace($parentPath, '', $pos, strlen("public/$bidangPrefix"));
                }
                // $newStr = str_replace("public/$bidangPrefix", '', $parentPath);
            }
            return "$newStr$folderName";
        }

        static function removeFolderTrasedName($foldername){
            return explode('_', $foldername)[0];
        }

        static function getFolderById($id){
            return Folder::find($id);
        }

        static function getFileByUUID($uuid){
            return File::where('file_uuid', '=', $uuid)->first();
        }

        /** Helper untuk Bidang */
        static function getBidangPrefix($bidangName){
            $split = explode(' ', strtolower($bidangName));
            return implode('_', $split);
        }
    }
?>