<?php

include("puddy_library.php");

function generate_new_puddy_list($puddy_mat, $names_to_index) {
   $PAIR_FOUND = -1;
   $newlist = array();
   $inavailable = load_inavailable();
   $notassigned = array();
   
   foreach($names_to_index as $first => $idx1) {
    if(!$inavailable[$first]) {
      $notassigned[$first] = $idx1;
      echo $first." ".$idx1."<br>";
    }
   }
   
   // Compute initial assignement
   foreach($notassigned as $first => $idx1) {
    if($notassigned[$first] > $PAIR_FOUND) {
      foreach($notassigned as $second => $idx2) {
        echo $first." ".$second."<br>";
        if($idx1 < $idx2 && $puddy_mat[$first][$second] != 1 && $notassigned[$second] > $PAIR_FOUND) {
          $newlist[$first] = $second;
          $notassigned[$first] = $PAIR_FOUND;
          $notassigned[$second] = $PAIR_FOUND;
          break;
        }                       
      }
    }
  }
    
  // Create triples to include everyone           
  foreach($notassigned as $first => $idx) {
    echo $first."<br>";
      
    if($idx > $PAIR_FOUND) {
      echo $first."<br>";
      
      foreach($names_to_index as $second => $idx2) {        
        if ($idx2 != $idx && $puddy_mat[$first][$second] != 1 && array_key_exists($second, $notassigned)) {
          $newlist[$first] = $second;
          break;
        }
      }
    }
  }                                           
  
  $closure = transitive_closure_list($newlist); 
  return $closure;
}

function deprecate_current_list() {
  $curdate = getdate();
  $filename = $GLOBALS["puddy_base"]."_".$curdate["year"]."_".$curdate["mon"]."_".$curdate["mday"].".txt";

  $curpuddies = array();
  $names_to_index = get_names_to_index();
  get_one_puddy_list($curpuddy_filename, $curpuddies, $names_to_index);
  
  echo $filename;
  save_puddy_list($closure, $filename);
}

//deprecate_current_list();
  
$puddy_mat = get_all_old_puddies();
$names_to_index = get_names_to_index();
$newlist = generate_new_puddy_list($puddy_mat, $names_to_index);

save_puddy_list($newlist, $curpuddy_filename);

?>
