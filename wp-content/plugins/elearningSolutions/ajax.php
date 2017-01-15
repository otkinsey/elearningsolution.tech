<?
require '../../../wp-load.php';
require_once 'admin-functions.php';

wp_enqueue_script('jquery');
$function = $_POST['function'];

if($function == 'deleteExam'){
  function processAjax(){
    global $wpdb;
      $id = $_POST['id'];
          // var_dump($id);
      if(isset($id)){
          $id = isset($id) ? $id : '';
          $query = $wpdb->query($wpdb->prepare("DELETE FROM e_els_scheduledExams WHERE examID = '$id'"));
          // var_dump($id);
      }
      return $id;
      die();
  }
  processAjax();
  add_action('wp_ajax_processAjax', 'processAjax');
  add_action('wp_ajax_nopriv_processAjax', 'processAjax');
}

if($function == 'deleteExamQuestion'){
  function processAjax(){
    global $wpdb;
      $id = $_POST['id'];
          // var_dump($id);
      if(isset($id)){
          $id = isset($id) ? $id : '';
          $query = $wpdb->query($wpdb->prepare("DELETE FROM e_postmeta WHERE meta_id = '$id'"));
          // var_dump($id);
      }
      return $id;
      die();
  }
  processAjax();
  add_action('wp_ajax_processAjax', 'processAjax');
  add_action('wp_ajax_nopriv_processAjax', 'processAjax');
}

if($function == 'multipleChoiceOptions'){
  function processAjax(){
    global $wpdb;
      $options = $_POST['options'];
      $test = get_multiple_choice_options($options[0]['post_id']);
      if(isset($options) && !$test){
          for( $o=0;$o<count($options);$o++){
            $query = $wpdb->insert('e_postmeta',array('meta_id'=>'','post_id'=>$options[$o]['post_id'],'meta_key'=>$options[$o]['meta_key'],'meta_value'=>$options[$o]['meta_value']));
          }
      }
      else{
          for( $o=0;$o<count($options);$o++){
            $query = $wpdb->update('e_postmeta',array('meta_id'=>'','post_id'=>$options[$o]['post_id'],'meta_key'=>$options[$o]['meta_key'],'meta_value'=>$options[$o]['meta_value']), array('post_id'=>$options[$o]['post_id']));
          }
      }
      return $id;
      die();
  }
  processAjax();
  add_action('wp_ajax_processAjax', 'processAjax');
  add_action('wp_ajax_nopriv_processAjax', 'processAjax');
}


if($function == 'update'){
  function processUpdate(){
    global $wpdb;
      $id = $_POST['id'];
      $col_name = $_POST['col_name'];
      $value = $_POST['value'];
          // var_dump($id);
      if(isset($id)){
          $id = isset($id) ? $id : '';
          $query = $wpdb->query($wpdb->prepare("UPDATE e_els_scheduledExams SET $col_name = '$value' WHERE examID = '$id'"));
          // var_dump($id);
      }
      return $id;
      die();
  }
  processUpdate();
  add_action('wp_ajax_processUpdate', 'processUpdate');
  add_action('wp_ajax_nopriv_processUpdate', 'processUpdate');
}
?>
