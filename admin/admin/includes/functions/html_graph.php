<?php
/*
  $Id: html_graph.php,v 1.1 2001/07/02 10:56:11 hpdl Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License

  HTML_Graph by Phil Davis
  GraphResult by Tim Perdue

  Updated by Harald Ponce de Leon for TEP standards
*/

// *** HTML_Graph *** //

  function html_graph($names, $values, $bars, $vals, $dvalues = 0, $dbars = 0) {
    $er = error_reporting(1);

// Set the values that the user didn't
    $vals = hv_graph_defaults($vals);
    $graph_string = start_graph($vals, $names);

    if ($vals['type'] == 0) {
      $graph_string .= horizontal_graph($names, $values, $bars, $vals);
    } elseif ($vals['type'] == 1) {
      $graph_string .= vertical_graph($names, $values, $bars, $vals);
    } elseif ($vals['type'] == 2) {
      $graph_string .= double_horizontal_graph($names, $values, $bars, $vals, $dvalues, $dbars);
    } elseif ($vals['type'] == 3) {
      $graph_string .= double_vertical_graph($names, $values, $bars, $vals, $dvalues, $dbars);
    }

    $graph_string .= end_graph();

// Set the error level back to where it was.
    error_reporting($er);  

    return $graph_string;
  }

  function html_graph_init()  {
    $vals = array('vlabel' => '',
                  'hlabel' => '',
                  'type' => '',
                  'vfcolor' => '',
                  'hfcolor' => '',
                  'vfstyle' => '',
                  'hfstyle' => '',
                  'noshowvals' => '',
                  'scale' => '',
                  'namefcolor' => '',
                  'valuefcolor' => '',
                  'namefstyle' => '',
                  'valuefstyle' => '',
                  'doublefcolor' => '');

    return($vals);
  }

  function start_graph($vals, $names) {
    $string = '<table border="0" width="100%" cellspacing="0" cellpadding="2">';

    if ( ($vals['vlabel']) || ($vals['hlabel']) ) {
      if ( ($vals['type'] == 0) || ($vals['type'] == 2) ) {
// horizontal chart
        $rowspan = sizeof($names)+1;
        $colspan = 3;
      } elseif ( ($vals['type'] == 1) || ($vals['type'] == 3) ) {
// vertical chart
        $rowspan = 3;
        $colspan = sizeof($names)+1;
      }

      $string .= '  <tr>' . "\n" .
                 '    <td align="center" valign="center" colspan="' . $colspan . '"><font color="' . $vals['hfcolor'] . '" style="' . $vals['hfstyle'] . '"><b>' . $vals['hlabel'] . '</b></font></td>' . "\n" .
                 '  </tr>' . "\n" .
                 '  <tr>' . "\n" .
                 '    <td align="center" valign="center" rowspan="' . $rowspan . '"><font color="' . $vals['vfcolor'] . '" style="' . $vals['vfstyle']. '"><b>' . $vals['vlabel'] . '</b></font></td>' . "\n" .
                 '  </tr>' . "\n";
    }

    return $string;
  }

  function end_graph() {
    return '</table>' . "\n";
  }

  function hv_graph_defaults($vals) {
    if (!$vals['vfcolor']) $vals['vfcolor'] = '#000000';
    if (!$vals['hfcolor']) $vals['hfcolor'] = '#000000';
    if (!$vals['scale']) $vals['scale'] = 1;
    if (!$vals['namefcolor']) $vals['namefcolor'] = '#000000';
    if (!$vals['valuefcolor']) $vals['valuefcolor'] = '#000000';
    if (!$vals['doublefcolor']) $vals['doublefcolor'] = '#886666';

    return $vals;
  }

  function horizontal_graph($names, $values, $bars, $vals) {
    $string = '';
    for ($i=0; $i<sizeof($values); $i++) {
      $string .= '  <tr>' . "\n" .
                 '    <td align="right"><font size="-1" color="' . $vals['namefcolor'] . '" style="' . $vals['namefstyle'] . '">&nbsp;' . $names[$i] . '&nbsp;</font></td>' . "\n" .
                 '    <td>';

// Decide if the value in bar is a color code or image.
      if (ereg('^#', $bars[$i])) {
        $string .= '<table border="0" width="' . $values[$i]*$vals['scale'] . '" cellspacing="0" cellpadding="0" bgcolor="' . $bars[$i] . '">' . "\n" .
                   '      <tr>' . "\n" .
                   '        <td>&nbsp;</td>' . "\n" .
                   '      </tr>' . "\n" .
                   '    </table>';
      } else {
        $string .= '<img src="' . $bars[$i] . '" border="0" width="' . $values[$i]*$vals['scale'] . '" height="10">';
      }

      if (!$vals["noshowvals"]) {
        $string .= '<font size="-2" color="' . $vals['valuefcolor'] . '" style="' . $vals['valuefstyle'] . '"><i>(' . $values[$i] . ')</i></font>';
      }

      $string .= '</td>' . "\n" .
                 '  </tr>' . "\n";
    }

    return $string;
  }

  function vertical_graph($names, $values, $bars, $vals) {
    $string = '  <tr>' . "\n";

    for ($i=0; $i<sizeof($values); $i++) {
      $string .= '<td align="center" valign="bottom">';

      if (!$vals['noshowvals']) {
        $string .= '<font size="-2" color="' . $vals['valuefcolor'] . '" style="' . $vals['valuefstyle'] . '"><i>(' . $values[$i] . ')</i></font><br>';
      }

      $string .= '<img src="' . $bars[$i] . '" border="0" width="5" height="';
      if ($values[$i] != 0) {
        $string .= $values[$i] * $vals['scale'];
      } else {
        $string .= '1';
      }
      $string .= '"></td>' . "\n";
    }

    $string .= '  </tr>' . "\n" .
               '  <tr>' . "\n";

    for ($i=0; $i<sizeof($values); $i++) {
      $string .= '    <td align="center" valign="top"><font size="-1" color="' . $vals['namefcolor'] . '" style="' . $vals['namefstyle'] . '">' . $names[$i] . '</font></td>' . "\n";
    }

    return $string;
  }

  function double_horizontal_graph($names, $values, $bars, $vals, $dvalues, $dbars) {
    $string = '';
    for ($i=0; $i<sizeof($values); $i++) {
      $string .= '  <tr>' . "\n" .
                 '    <td align="right"><font size="-1" color="' . $vals['namefcolor'] . '" style="' . $vals['namefstyle'] . '">' . $names[$i] . '</font></td>' . "\n" .
                 '    <td><table border="0" width="' . $dvalues[$i]*$vals['scale'] . '" cellspacing="0" cellpadding="0">' . "\n" .
                 '      <tr>' . "\n" .
                 '        <td';
      if (ereg('^#', $dbars[$i])) {
        $string .= ' bgcolor="' . $dbars[$i] . '"';
      } else {
        $string .= ' background="' . $dbars[$i] . '"';
      }
      $string .= ' nowrap>';

      if (ereg('^#', $bars[$i])) {
        $string .= '<table border="0" width="' . $values[$i]*$vals['scale'] . '" cellspacing="0" cellpadding="0" bgcolor="' . $bars[$i] . '">' . "\n" .
                   '          <tr>' . "\n" .
                   '            <td>&nbsp</td>' . "\n" .
                   '          </tr>' . "\n" .
                   '        </table>';
      } else {
        $string .= '<img src="' . $bars[$i] . '" border="0" width="' . $values[$i]*$vals['scale'] . '" height="10">';
      }

      if (!$vals["noshowvals"]) {
        $string .= '<font size="-3" color="' . $vals["valuefcolor"] . '" style="' . $vals["valuefstyle"] . '"><i>(' . $values[$i] . ')</i></font>';
      }

      $string .= '</td>' . "\n" .
                 '      </tr>' . "\n" .
                 '    </table>';

      if (!$vals['noshowvals']) {
        $string .= '<font size="-3" color="' . $vals['doublefcolor'] . '" style="' . $vals['valuefstyle'] . '"><i>(' . $dvalues[$i] . ')</i></font>';
      }

      $string .= '</td>' . "\n" .
                 '  </tr>' . "\n";
    }

    return $string;
  }

  function double_vertical_graph($names, $values, $bars, $vals, $dvalues, $dbars) {
    $string = '';
    for ($i=0; $i<sizeof($values); $i++) {
      $string .= '    <td align="center" valign="bottom"><table border="0" cellspacing="0" cellpadding="0">' . "\n" .
                 '    <tr>' . "\n" .
                 '      <td align="center" valign="bottom">';

      if (!$vals['noshowvals']) {
        $string .= '<font size="-2" color="' . $vals['valuefcolor'] . '" style="' . $vals['valuefstyle'] . '"><i>(' . $values[$i] . ')</i></font><br>';
      }

      $string .= '<img src="' . $bars[$i] . '" border="0" width="10" height="';
      if ($values[$i] != 0) {
        $string .= $values[$i] * $vals['scale'];
      } else {
        $string .= '1';
      }
      $string .= '"></td>' . "\n" .
                 '      <td align="center" valign="bottom">';

      if (!$vals['noshowvals']) {
        $string .= '<font size="-2" color="' . $vals['douberfcolor'] . '" style="' . $vals['valuefstyle'] . '"><i>(' . $dvalues[$i] . ')</i></font><br>';
      }

      $string .= '<img src="' . $dbars[$i] . '" border="0" width="10" height="';
      if ($dvalues[$i] != 0) {
        $string .= $dvalues[$i] * $vals['scale'];
      } else {
        $string .= '1';
      }
      $string .= '"></td>' . "\n" .
                 '    </tr>' . "\n" .
                 '  </table></td>';
    }

    $string .= '</tr><tr>';

    for ($i=0; $i<sizeof($values); $i++) {
      $string .= '<TD ALIGN="CENTER" VALIGN="TOP"><FONT SIZE="-1" COLOR="' . $vals['namefcolor'] . '" STYLE="' . $vals['namefstyle'] . '">' . $names[$i] . '</FONT></TD>' . "\n";
    }

    return $string;
  }

// *** GraphResult *** //

  function GraphResult($result, $title) {
    $rows = tep_db_num_rows($result);

    if ( (!$result) || ($rows < 1) ) {
      return 'None Found.';
    } else {
      $names = array();
      $values = array();

      for ($j=0; $j<tep_db_num_rows($result); $j++) {
        if ( (tep_db_result($result, $j, 0) != '') && (tep_db_result($result, $j, 1) != '') ) {
          $names[$j] = tep_db_result($result, $j, 0);
          $values[$j]= tep_db_result($result, $j, 1);
        }
      }

      return GraphIt($names, $values, $title);
    }
  }

  function GraphIt($name_string, $value_string, $title) {
    $counter = count($name_string);

    for ($i=0; $i<$counter; $i++) {
      $bars[$i] = DIR_WS_IMAGES . 'graph_hbar_blue.gif';
    }

    $counter=count($value_string);

// Figure the max_value passed in, so scale can be determined
    $max_value=0;

    for ($i=0; $i<$counter; $i++) {
      if ($value_string[$i] > $max_value) {
        $max_value = $value_string[$i];
      }
    }

    if ($max_value < 1) {
      $max_value = 1;
    }

// I want my graphs all to be 800 pixels wide, so that is my divisor
    $scale = (100/$max_value);

// I create a wrapper table around the graph that holds the title
    $string = '<table border="0" cellspacing="0" cellpadding="2">' . "\n" .
              '  <tr>' . "\n" .
              '    <td><b>' . $title . '</font></td>' . "\n" .
              '  </tr>' . "\n" .
              '  <tr>' . "\n" .
              '    <td>';

// Create an associate array to pass in. I leave most of it blank
    $vals =  array('vlabel' => '',
                   'hlabel' => '',
                   'type' => '1',
                   'vfcolor' => '',
                   'hfcolor' => '',
                   'vfstyle' => '',
                   'hfstyle' => '',
                   'noshowvals' => '',
                   'scale' => $scale,
                   'namefcolor' => '',
                   'valuefcolor' => '',
                   'namefstyle' => '',
                   'valuefstyle' => '',
                   'doublefcolor' => '');

// This is the actual call to the HTML_Graphs class
    $string .= html_graph($name_string, $value_string, $bars,$vals);

    $string .= '</td>' . "\n" .
               '  </tr>' . "\n" .
               '</table>' . "\n";

    return $string;
  }
?>
