<?php

  // Includes
  if (isset($sql_config) == false)
    $sql_config = include(__DIR__ . "/sql_config.php");

  // Start a session
  if (session_id() == "")
    session_start();

  // Create the SQL connection
  $mysqli = mysqli_connect($sql_config["sql_host"], $sql_config["sql_username"], $sql_config["sql_password"], $sql_config["sql_database"]);

  // Redirect with error message if connection to db failed
  if (mysqli_connect_errno())
  {
    echo "Error: Can't connect to the target SQL database.";
    return false;
  }

  // Check if username and password matches
  $q_username = mysqli_escape_string($mysqli, $_SESSION["username"]);
  if ($result = mysqli_query($mysqli, "SELECT id, username, gmlevel, email, joindate, last_ip, failed_logins, last_login, expansion, locale, locked FROM account WHERE username='$q_username'"))
  {
    if ($result->num_rows != 0)
    {
      $_SESSION["account"] = mysqli_fetch_array($result, MYSQLI_ASSOC);
    }

    mysqli_free_result($result);
  }
  else
  {
    echo "Error: Failed to execute an SQL statement! Please contact site administrators.";
    return false;
  }

  // Close the SQL connection
  mysqli_close($mysqli);

?>
