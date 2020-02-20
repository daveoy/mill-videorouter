<?php
require_once "Config.php";
// ini_set("display_errors", true);
// error_reporting(E_ALL);

# DB Connection
# open database connection
$db = new PDO('mysql:host=' . $config['database']['mysql']['videorouter']['host'] . ';dbname=' . $config['database']['mysql']['videorouter']['dbname'], 
                $config['database']['mysql']['videorouter']['user'], 
                $config['database']['mysql']['videorouter']['password']);

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

# get all input
//$query = "SELECT vrl.*, vrg.name AS group_name FROM vr_label AS vrl INNER JOIN vr_group AS vrg ON vrl.group_uid = vrg.group_uid WHERE type = 'input' ORDER BY vrl.port_uid";
$query = "SELECT vrl.*, vrg.name AS group_name, CASE WHEN vril.uid IS NOT NULL THEN 1 ELSE 0 END AS locked FROM vr_label AS vrl INNER JOIN vr_group AS vrg ON vrl.group_uid = vrg.group_uid LEFT JOIN vr_input_lock AS vril ON vrl.port_uid = vril.port_uid WHERE type = 'input' ORDER BY vrl.port_uid";
$statement = $db->prepare($query);
$statement->execute();
$inputs = $statement->fetchAll(PDO::FETCH_OBJ);
// print_r($inputs);die();

# get all output
$query = "SELECT vrl.*, vro.floor_uid FROM vr_label AS vrl INNER JOIN vr_output AS vro ON vrl.port_uid = vro.port_uid WHERE vrl.type = 'output' ORDER BY vrl.port_uid";
$statement = $db->prepare($query);
$statement->execute();
$outputs = $statement->fetchAll(PDO::FETCH_OBJ);
// print_r($outputs);die();

# get groups
$query= "SELECT * FROM vr_group WHERE active = 1";
$statement = $db->prepare($query);
$statement->execute();
$groups = $statement->fetchAll(PDO::FETCH_OBJ);
// print_r($groups);die();

# get floors
$query= "SELECT * FROM vr_floor";
$statement = $db->prepare($query);
$statement->execute();
$floors = $statement->fetchAll(PDO::FETCH_OBJ);
// print_r($floors);die();

# get router labels and bundle with input and output
# input
$routerInputs = array();
$ch = curl_init($config['api']['endpoint_http_base'] . "/cms/?input");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
if(!is_null($result))
{
  $result = json_decode($result);
  $routerInputs = $result->input;
}

foreach($inputs as $input)
{
  $input->router_name = "Unavailable";
  foreach($routerInputs as $routerInput)
  {  
    if($routerInput->Id == $input->port_uid)
      $input->router_name = $routerInput->Label;
  }
}

# output
$routerOutputs = array();
$ch = curl_init($config['api']['endpoint_http_base'] . "/cms/?output");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
if(!is_null($result))
{
  $result = json_decode($result);
  $routerOutputs = $result->output;
}

foreach($outputs as $output)
{
  $output->router_name = "Unavailable";
  foreach($routerOutputs as $routerOutput)
  {  
    if($routerOutput->Id == $output->port_uid)
      $output->router_name = $routerOutput->Label;
  }
}

# render page
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Video Router I/O Dashboard</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Video Router I/O Dashboard</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Help</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li class="input-nav"><a href="#">Machines</a></li>
            <li class="output-nav"><a href="#">Destinations</a></li>
          </ul>
        </div> 
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Video Router Dashboard</h1>

          <!-- Inputs -->
          <div id="inputs">
          <h2 class="sub-header">Machine Labels</h2>
            <div class="table-responsive">
              <form action="process.php" method="POST">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>DB Uid</th>
                      <th>Router Port</th>
                      <th>Router Label</th>
                      <th>Group Name</th>
                      <th>Label</th>
                      <!-- <th>Short Label</th> -->
                      <th>Sublevel Label</th>
                      <th>Locked</th>
                      <th>Visible</th>
                      <th>Submit</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      foreach($inputs as $input)
                      {
                        # split label in level_1 and level_2
                        $explodedLabel = explode("|", $input->name);
                        $input->level_1 = $explodedLabel[0];
                        $input->level_2 = isset($explodedLabel[1]) ? $explodedLabel[1] : "";

                        # build group selection
                        $groupSelectHtml = "<select name=\"input_group_" . $input->port_uid . "\">";
                        foreach($groups as $group)
                        {
                          $groupSelectHtml .= $group->group_uid == $input->group_uid ? "<option value=\"" . $group->group_uid . "\" selected>" . $group->name . "</option>" : "<option value=\"" . $group->group_uid . "\">" . $group->name . "</option>";
                        }
                        $groupSelectHtml .= "</select>";

                        # check if port need to be visible
                        $checked = "";
                        if($input->active)
                          $checked = "checked";

                        $lockedChecked = "";
                        # check if port is locked
                        if($input->locked == 1)
                          $lockedChecked = "checked";
                        
                        $html = "
                        <tr>
                          <td>
                            " . $input->uid . "
                          </td>
                          <td>
                            " . $input->port_uid . "
                          </td>
                          <td>
                            " . $input->router_name . "
                          </td>
                          <td>
                          " . $groupSelectHtml . "
                          </td>
                          <td>";
                            // <input type =\"text\" name=\"input_label_" . $input->port_uid . "\" value=\"" . $input->name . "\"/>
                          $html .= "<input type =\"text\" name=\"input_label_level_1_" . $input->port_uid . "\" value=\"" . $input->level_1 . "\"/>
                          </td>";
                          // <td>
                          //   <input type =\"text\" name=\"input_short_label_" . $input->port_uid . "\" value=\"" . $input->short_label . "\"/>
                          // </td>
                          $html .= "<td>
                            <input type =\"text\" name=\"input_label_level_2_" . $input->port_uid . "\" value=\"" . $input->level_2 . "\"/>
                          </td>
                          <input type=\"hidden\" name=\"input_uid_" . $input->port_uid . "\" value=\"" . $input->uid . "\" />
                          <td>
                            <input type=\"checkbox\" name=\"input_locked_" . $input->port_uid . "\" " . $lockedChecked . "/>
                          </td>
                          <td>
                            <input type=\"checkbox\" name=\"input_active_" . $input->port_uid . "\" " . $checked . "/>
                          </td>
                          <td>
                            <input type=\"submit\"/>
                          </td>
                        </tr>";

                        echo $html;
                      }
                    ?>
                  </tbody>
                </table>
              </form>
            </div>
          </div>

          <!-- Outputs -->
          <div id="outputs">
            <h2 class="sub-header">Destination Labels</h2>
            <div class="table-responsive">
              <form action="process.php" method="POST">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>DB Uid</th>
                      <th>Router Port</th>
                      <th>Router Label</th>
                      <th>Floor Number</th>
                      <th>Label</th>
                      <!-- <th>Short Label</th> -->
                      <th>Sublevel Label</th>
                      <th>Visible</th>
                      <th>CETA ID</th>
                      <th>Submit</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      foreach($outputs as $output)
                      {
                        # split label in level_1 and level_2
                        $explodedLabel = explode("|", $output->name);
                        $output->level_1 = $explodedLabel[0];
                        $output->level_2 = isset($explodedLabel[1]) ? $explodedLabel[1] : "";

                        # build group selection
                        $floorSelectHtml = "<select name=\"output_floor_" . $output->port_uid . "\">";
                        foreach($floors as $floor)
                        {
                          $floorSelectHtml .= $floor->uid == $output->floor_uid ? "<option value=\"" . $floor->uid . "\" selected>" . $floor->name . "</option>" : "<option value=\"" . $floor->uid . "\">" . $floor->name . "</option>";
                        }
                        $floorSelectHtml .= "</select>";

                        # check if port need to be visible
                        $checked = "";
                        if($output->active)
                          $checked = "checked";
                        
                        $html = "
                        <tr>
                          <td>
                            " . $output->uid . "
                          </td>
                          <td>
                            " . $output->port_uid . "
                          </td>
                          <td>
                            " . $output->router_name . "
                          </td>
                          <td>
                          " . $floorSelectHtml . "
                          </td>
                          <td>";
                            // <input type =\"text\" name=\"output_label_" . $output->port_uid . "\" value=\"" . $output->name . "\"/>
                          $html .= "<input type =\"text\" name=\"output_label_level_1_" . $output->port_uid . "\" value=\"" . $output->level_1 . "\"/>
                          </td>";
                          // <td>
                          //   <input type =\"text\" name=\"output_short_label_" . $output->port_uid . "\" value=\"" . $output->short_label . "\"/>
                          // </td>
                          $html .= "<td>
                            <input type =\"text\" name=\"output_label_level_2_" . $output->port_uid . "\" value=\"" . $output->level_2 . "\"/>
                          </td>
                          <input type=\"hidden\" name=\"output_uid_" . $output->port_uid . "\" value=\"" . $output->uid . "\" />
                          <td>
                            <input type=\"checkbox\" name=\"output_active_" . $output->port_uid . "\" " . $checked . "/>
                          </td>
                          <td>
                            <span>" . $output->ceta_id . "<span/>
                          </td>
                          <td>
                            <input type=\"submit\"/>
                          </td>
                        </tr>";

                        echo $html;
                      }
                    ?>
                  </tbody>
                </table>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- <script src="js/docs.min.js"></script> -->
    <script src="js/dashboard.js"></script>
  </body>
</html>
