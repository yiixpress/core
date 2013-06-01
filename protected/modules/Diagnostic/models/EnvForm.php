<?php
class EnvForm extends CFormModel {
    public $site_owner;
    public $site_id;
    public $server_host = 'localhost';
    public $dbport = '3306';
    public $dbname;
    public $dbusername = 'root';
    public $dbpassword;

    public function rules() {
        return array(
            // site_dir, site_owner, stite_id, server_host, port, dbname, dbusername, dbpassword are required
            array('site_owner, site_id, server_host, dbport, dbname, dbusername, dbpassword', 'required'),
        );
    }
    public function attributeLabels() {
        return array(
            'site_owner'=>'Site Owner',
            'site_id'=>'Site ID',
            'server_host'=>'Server Host',
            'dbport'=>'Port',
            'dbname'=>'Database Name',
            'dbusername'=>'Database Username',
            'dbpassword'=>'Database Password',
        );
    }
}
?>
