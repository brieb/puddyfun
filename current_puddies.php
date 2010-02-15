<?php
 
include("puddy_library.php");
//include("generate_new_puddies.php");

$curpuddies = array();
$names_to_index = get_names_to_index();
get_one_puddy_list($curpuddy_filename, $curpuddies, $names_to_index);

echo "<table>";
foreach($names_to_index as $first => $idx1) {
  foreach($names_to_index as $second => $idx2) {
    if ($idx1 < $idx2 && $curpuddies[$first][$second]) {
    	echo "<tr>";
      echo "<td>".$first."</td><td>".$second."</td>";
    	echo "</tr>";
    }
  }
}
echo "</table>";

?>
