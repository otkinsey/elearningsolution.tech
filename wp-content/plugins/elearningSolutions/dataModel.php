<?
// require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
// global $els_makeTables;
// $els_makeTables_version = '1.0';
//
// function elsMakeTables(){
//   global $wpdb;
//   global $els_makeTables_version;
//
//   $charset_collate = $wpdb->get_charset_collate();
//   $tableName = $wpdb->prefix . 'els_scheduledExams';
//   $sql =  "CREATE TABLE " . $tableName . "(
//           examID int(10) NOT NULL AUTO_INCREMENT,
//           firstName varchar(20),
//           lastName varchar(20),
//           examDate varchar(10),
//           score int(3),
//           testName varchar(200),
//           PRIMARY KEY  (examID)
//         );";
//
//   dbDelta($sql);
// }
// register_activation_hook(__FILE__, 'elsMakeTables');
?>
