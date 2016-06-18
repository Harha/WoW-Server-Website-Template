<?php

  // Includes
  $config = include("sql_config.php");
  $functions = include("functions.php");

  // Get form data
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $password_repeat = $_POST["password_repeat"];

  // Check if form input is valid
  if (check_for_symbols($username) == true || check_for_symbols($password) == true || check_for_symbols($password_repeat) == true)
  {
    redirect("../register.php", "Error: Your username or password contains invalid characters.");
    exit();
  }

  // Check if passwords match
  if (strcmp($password, $password_repeat) !== 0)
  {
    redirect("../register.php", "Error: Passwords did not match.");
    exit();
  }

  // Check if password length is in set bounds
  $password_length = strlen($password);
  if ($password_length < 6 || $password_length > 16)
  {
    redirect("../register.php", "Error: Password length must be in between 6 to 16 characters.");
  }

  // Create the SQL connection
  $mysqli = mysqli_connect($config["sql_host"], $config["sql_username"], $config["sql_password"], $config["sql_database"]);

  // Redirect with error message if connection to db failed
  if (mysqli_connect_errno())
  {
    redirect("../register.php", "Error: Can't connect to the target SQL database.");
    exit();
  }

  // Check if username exists
  $q_username = mysqli_escape_string($mysqli, $username);
  if ($result = mysqli_query($mysqli, "SELECT username FROM account WHERE username='$q_username'"))
  {
    if ($result->num_rows != 0)
    {
      redirect("../register.php", "Error: an Account with that name already exists.");
      exit();
    }

    mysqli_free_result($result);
  }

  // Register the new account
  $q_password = mysqli_escape_string($mysqli, sha1_password($username, $password));
  $q_email = mysqli_escape_string($mysqli, $email);
  if ($result = mysqli_query($mysqli, "INSERT INTO account (username, sha_pass_hash, email) VALUES ('$q_username', '$q_password', '$q_email')"))
  {
    redirect("../register.php", "Registration successful!");
    mysqli_free_result($result);
  }
  else
  {
    redirect("../register.php", "Error: Couldn't insert the new account into target database! Please contact site administrators.");
  }

  // Close the SQL connection
  mysqli_close($mysqli);

?>
