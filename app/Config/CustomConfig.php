<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class CustomConfig extends BaseConfig {

    public $common_assets_path = '';
    
    public $upload_path_logo_thumb = '/public/uploads/logo/thumb/';
    public $upload_path_logo = '/public/uploads/logo/';
    public $upload_logo_allowed_types = 'image/png,image/jpg,image/jpeg,image/gif,image/bmp';
    public $logo_thumb_width = '150';
    public $logo_thumb_height = '150';
    
    public $upload_path_admin_thumb = '/public/uploads/admin/thumb/';
    public $upload_path_admin = '/public/uploads/admin/';
    public $upload_admin_allowed_types = 'image/png,image/jpg,image/jpeg,image/gif,image/bmp';
    public $admin_thumb_width = '150';
    public $admin_thumb_height = '150';
    
    public $upload_path_user = '/public/uploads/user/';
    public $upload_path_user_thumb = '/public/uploads/user/thumb/';
    public $upload_user_allowed_types = 'image/png,image/jpg,image/jpeg,image/gif,image/bmp';
    public $user_thumb_width = '150';
    public $user_thumb_height = '150';

}
