<!-- categories //-->
          <tr>
            <td>
<?
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_CATEGORIES
                              );
  new infoBoxHeading($info_box_contents);

  $categories_string = '';
  /**
  * @version 0.1 22/02/00
  * @author Tobias Nix <t.nix@paupau.de>
  */

  $result = tep_db_query("SELECT * FROM categories WHERE parent_id = '0' ORDER BY sort_order, categories_name");
  while( $row = mysql_fetch_array( $result ) )  {
    $foo[ $row[ categories_id ] ] = array('name'    => $row[ categories_name ],
                                          'parent'  => $row[ parent_id ],
                                          'level'   => 0,
                                          'path'    => $row[ categories_id ],
                                          'next_id' => false
                                         );
    if( isset( $prev_id ) )  
      $foo[ $prev_id ][ next_id ] = $row[ categories_id ];
    $prev_id = $row[ categories_id ];
    if( !isset( $first_element) )  $first_element = $row[ categories_id ] ;
  }
  
  if( $cPath )  {
    $id = split ("_", $cPath);
    foreach( $id as $key => $value )  {
      $new_path .= $value;
      unset( $prev_id );
      unset( $first_id );
      $result = tep_db_query("SELECT * FROM categories WHERE parent_id = '$value' ORDER BY sort_order, categories_name");
      $category_check = mysql_num_rows( $result ) . "<br>";
      while( $row = mysql_fetch_array( $result ) ) {
        $foo[ $row[ categories_id ] ] = array('name'    => $row[ categories_name ],
                                              'parent'  => $row[ parent_id ],
                                              'level'   => $key+1,
                                              'path'    => $new_path . "_" .$row[ categories_id ],
                                              'next_id' => false
                                             );
        if( isset( $prev_id ) )  
          $foo[ $prev_id ][ next_id ] = $row[ categories_id ];
        $prev_id = $row[ categories_id ];
		
        if( !isset( $first_id ) )  $first_id = $row[ categories_id ];	
        $last_id = $row[ categories_id ];
      }
      if( $category_check != 0 )  {
        $foo[ $last_id ][ next_id ] = $foo[ $value ][ next_id ];
        $foo[ $value ][ next_id ] = $first_id;	
      }
      $new_path .= "_";
    }
  }
  
  function show_category( $counter )  {
    global $foo;
    global $categories_string;

    for( $a = 0; $a < $foo[ $counter ][ level ] ; $a++ )  $categories_string .= "&nbsp;";
    $categories_string .= "<a href=\"";
	
    if( $foo[ $counter ][ parent ] == 0 )  
      $cPath_new = "cPath=".$counter;
    else
      $cPath_new = "cPath=".$foo[ $counter ][ path ] ;
  
    $categories_string .= tep_href_link(FILENAME_DEFAULT, $cPath_new );

    $categories_string .= "\">" . $foo[ $counter ][ name ] . "</a><br>";	
    if( $foo[ $counter ][ next_id ] )  show_category($foo[ $counter ][ next_id ]);
  }
  show_category( $first_element ); 
  
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $categories_string
                              );
  new infoBox($info_box_contents);
?>
            </td>
          </tr>
<!-- categories_eof //-->