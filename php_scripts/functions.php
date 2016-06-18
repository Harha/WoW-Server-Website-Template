<?php

  // Function: Returns username + password SHA1 hash
  function sha1_password($username, $password)
  {
    $username = strtoupper($username);
    $password = strtoupper($password); // Because WoW was like this. It's stupid, I know.

    return SHA1($username . ':' . $password);
  }

  // Function: Checks for invalid symbols in input string
  function check_for_symbols($str)
  {
    return preg_match("/[^A-Za-z0-9\ä\Ä\ö\Ö\å\Å\!\?\#]/", $str);
  }

  // Function: Redirect user with a message or without
  function redirect($location, $message)
  {
    if (is_null($message) === false)
    {
      header("Location: " . $location . "?message=" . $message);
    }
    else
    {
      header("Location: " . $location);
    }
  }

?>
