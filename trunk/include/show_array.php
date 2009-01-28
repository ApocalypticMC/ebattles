<?php
function do_offset($level){
    $offset = "";             // offset for subarry 
    for ($i=1; $i<$level;$i++){
    $offset = $offset . "<td></td>";
    }
    return $offset;
}

function show_array($array, $level, $sub){
    $output = '';
    if (is_array($array) == 1){          // check if input is an array
       foreach($array as $key_val => $value) {
           $offset = "";
           if (is_array($value) == 1){   // array is multidimensional
           $output .= "<tr>";
           $offset = do_offset($level);
           $output .= $offset . "<td>" . $key_val . "</td>";
           $output .= show_array($value, $level+1, 1);
           }
           else{                        // (sub)array is not multidim
           if ($sub != 1){          // first entry for subarray
               $output .= "<tr nosub>";
               $offset = do_offset($level);
           }
           $sub = 0;
           $output .= $offset . "<td main ".$sub." width=\"120\">" . $key_val . 
               "</td><td width=\"120\">" . $value . "</td>"; 
           $output .= "</tr>\n";
           }
       } //foreach $array
       return $output;
    }  
    else{ // argument $array is not an array
        return;
    }
}

function html_show_array($array){
  $output = '';
  $output .= "<table cellspacing=\"0\" border=\"2\">\n";
  $output .= show_array($array, 1, 0);
  $output .= "</table>\n";
  return $output;
}

function html_show_table($array, $rows, $columns)
{
   $output = "<table class=\"fborder\" style=\"width:95%\"><tbody>";
      
   for ($i=0; $i<$rows; $i++)
   {
     $output .= "<tr>\n";
     for($j=1; $j<=$columns; $j++)
     {
       if (strcasecmp($array[$i][0],"header")==0)
       {
            $output .= "<td class=\"forumheader\">".$array[$i][$j]."</td>";
       }
       elseif (strcasecmp($array[$i][0],"row_highlight")==0)
       {
            $output .= "<td class=\"forumheader3\">".$array[$i][$j]."</td>";
       }
       elseif ( $i % 2 == 1 )
       {
            $output .= "<td class=\"forumheader3\">".$array[$i][$j]."</td>";
       }
       else
       {
            $output .= "<td class=\"forumheader3\">".$array[$i][$j]."</td>";
       }
     }
     $output .= "</tr>\n";
   }
   $output .= "</tbody></table>";
   return $output;
}
?>
