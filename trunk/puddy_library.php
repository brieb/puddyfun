<?php
$puddy_base = "old_puddies"; 
$curpuddy_filename = "current_puddies.txt";
$inavailable_filename = "inavailable_members.txt";

function list_files($dir) {
  $all_files = array();
  if(is_dir($dir)) {
    if($handle = opendir($dir)) {
      while(($file = readdir($handle)) !== false) {
        array_push($all_files, $file);
      }
      closedir($handle);
    }
  }
  return $all_files;
}


function get_names_to_index() {
  $handle = fopen("all_members.txt", "r");
  $name_to_index = array();
  $num_members = 0;
  while(($nextline = fgets($handle)) != false) {
    $name_to_index[trim($nextline)] = $num_members;
    $num_members = $num_members + 1;
  }
  return $name_to_index;
}

function get_one_puddy_list($filename, &$curmat, $names_to_index) {
  $handle = fopen($filename, "r");
  while(($nextline = fgets($handle)) != false) {
    $tabpos = strpos($nextline, "\t");    
    $first = trim(substr($nextline, 0, $tabpos));
    $second = trim(substr($nextline, $tabpos+1));
    
    if(!array_key_exists($first, $names_to_index))  {
      echo "Error: member ".$first." does not exist <br>";
    }
    if(!array_key_exists($second, $names_to_index)) {
      echo "Error: member ".$second." does not exist <br>";
    }
    
    $curmat[$first][$second] = 1;
    $curmat[$second][$first] = 1;
  }
  fclose($handle);
}


function get_all_old_puddies() {
  $all_files = list_files("./"); 
  $names_to_index = get_names_to_index(); 
  $puddy_mat = array();
  foreach($all_files as $idx => $name) {
    if(strpos($name, $GLOBALS['puddy_base']) === 0) {
      get_one_puddy_list($name, $puddy_mat, $names_to_index);
    }
  } 
  return $puddy_mat;
}





function save_puddy_list($trans_list, $filename) {  
  $handle = fopen($filename, "w");
    
  foreach($trans_list as $first_of_list => $list) {
    $listlength = count($list);
    
    for($idx1 = 0; $idx1 < $listlength; $idx1++) {
      for($idx2 =  $idx1 + 1; $idx2 < $listlength; $idx2++) {
        $first = $list[$idx1];
        $second = $list[$idx2];
        fwrite($handle, $first."\t".$second."\n");
      }
    }
  }
  
  /*
  foreach($list as $first => $second) {
    fwrite($handle, $first."\t".$second."\n");
  }*/
    
  fclose($handle);
}


function load_inavailable() {
  $handle = fopen($GLOBALS["inavailable_filename"], 'r');
  $result = array();
  
  while(($newline = fgets($handle)) != false ) {
   $result[trim($newline)] = 1;
  }
  
  fclose($handle);
  return $result;
}


function transitive_closure_list($puddylist) {
  $newlist = array();
  
  foreach($puddylist as $first =>$second) {
    if(!array_key_exists($first, $newlist)) {
      $newlist[$first] = array();
      array_push($newlist[$first], $first);      
    }
    if(!in_array($second, $newlist[$first])) {
      array_push($newlist[$first], $second);
    }
  }  
  
  return $newlist;
}

?>
