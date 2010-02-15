<?php

include("puddy_library.php");

function generate_new_puddy_list($puddy_mat, $names_to_index) {
   $newlist = array();
   $inavailable = load_inavailable();
   $notassigned = array();
   
   foreach($names_to_index as $first => $idx1) {
    if(!$inavailable[$first]) {
      $notassigned[$first] = true;
    }
   }
   
   foreach($names_to_index as $first => $idx1) {
    if(array_key_exists($first, $notassigned) && $notassigned[$first]) {
      foreach($names_to_index as $second => $idx2) {
        if(!$notassigned[$first]) continue;
        if ($idx1 < $idx2 && $puddy_mat[$first][$second] != 1 && $notassigned[$second]) {
          $newlist[$first] = $second;
          $notassigned[$first] = false;
          $notassigned[$second] = false;
        }
      }
    }
  }
                      
  foreach($notassigned as $first => $notpaired) {
    if($notpaired) {
      $matchfound = false;
      foreach($names_to_index as $second => $idx2) {        
        if (strcmp($first, $second) != 0 && $puddy_mat[$first][$second] != 1 && array_key_exists($second, $notassigned)) {
          $newlist[$first] = $second;
        }
      }
    }
  }                  
   
  return $newlist;
}

$filename = $GLOBALS["puddy_base"]."_".$curdate["year"]."_".$curdate["mon"]."_".$curdate["mday"].".txt";

$puddy_mat = get_all_old_puddies();
$names_to_index = get_names_to_index();
$newlist = generate_new_puddy_list($puddy_mat, $names_to_index);

save_puddy_list($newlist, $curpuddy_filename);

?>
