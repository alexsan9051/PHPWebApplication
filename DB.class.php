<?php

class DB {

    private $conn;

    function __construct() {
       // var_dump($_SERVER);
        $this->conn = new mysqli($_SERVER['DB_HOST'], $_SERVER['DB_USER'], $_SERVER['DB_PASSWORD'], $_SERVER['DB']);

        if ($this->conn->connect_error) {
            //don't do this in real life
            echo "Connect failed: {mysqli_connect_error()}";
            die();
        }

    }

    function getAllVenues() {
        $data = array(); //or []
        if ($stmt = $this->conn->prepare("select * from venue") ) {
            
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($idvenue,$name,$capacity);

            if  ($stmt->num_rows > 0) {

                while($stmt->fetch()) {
                    $data[] = ['idvenue'=> $idvenue,
                               'name'=> $name,
                               'capacity'=> $capacity];
                }
            }

        } // if stmt

        return $data;
    } //getAllVenues

    function getAllVenuesAsTable() {
        $data = $this->getAllVenues();
        if (count($data) > 0) {
            $bigString = "<table border='1'>\n
                            <tr><th>Venue ID</th><th>Venue Name</th><th>Capacity</th></tr>\n";

            foreach ($data as $row) {
                $bigString .= "<tr><td>{$row['idvenue']}</td>
                <td>{$row['name']}</td>
                <td>{$row['capacity']}</td>
                </tr>\n";
            }

            $bigString .= "</table>\n";

        } //have rows
        else {
            $bigString = "<h2> No Events Exist.</h2>";
        }

        return $bigString;
    } //getAllVenuesAsTable

    function insertVenue($name, $cap) {
        $queryString = "insert into venue (name, capacity) values (?,?) ";
        $insertId = -1;

        if ($stmt = $this->conn->prepare($queryString)) {
            $stmt->bind_param("ss", $name, $cap);
            $stmt->execute();
            $stmt->store_result();
            $insertId=$stmt->insert_id;

            ?>
            <head>
            <title> Venue Created </title>
            </head>
            <body>
            <p><strong>Venue Creation Successful!</strong></p>
            </body>
            </html>
            <?php
        }
    } //insertVenue

    function getAllEvents() {
        $data = array(); //or []
        if ($stmt = $this->conn->prepare("select * from event") ) {
            
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($idevent,$name,$datestart,$dateend,$numallowed,$venue);

            if  ($stmt->num_rows > 0) {

                while($stmt->fetch()) {
                    $data[] = ['idevent'=> $idevent,
                               'name'=> $name,
                               'datestart'=> $datestart,
                               'dateend'=> $dateend,
                               'numallowed'=> $numallowed,
                               'venue'=> $venue];
                }
            }

        } // if stmt

        return $data;
    } //getAllEvents

    function getAllEventsAsTable() {
        $data = $this->getAllEvents();
        if (count($data) > 0) {
            $bigString = "<table border='1'>\n
                            <tr><th>Event ID</th><th>Event Name</th><th>Start Date</th><th>End Date</th><th>Number Allowed</th><th>Venue</th></tr>\n";

            foreach ($data as $row) {
                $bigString .= "<tr><td>{$row['idevent']}</td>
                <td>{$row['name']}</td>
                <td>{$row['datestart']}</td>
                <td>{$row['dateend']}</td>
                <td>{$row['numallowed']}</td>
                <td>{$row['venue']}</td>
                </tr>\n";
            }

            $bigString .= "</table>\n";

        } //have rows
        else {
            $bigString = "<h2> No Events Exist.</h2>";
        }

        return $bigString;
    } //getAllEventsAsTable

    function insertEvent($name, $eventStartDate, $eventEndDate, $numallowed, $venueID) {
        $queryString = "insert into event (name, datestart, dateend, numberallowed, venue) values (?,?,?,?,?) ";
        $insertId = -1;

        if ($stmt = $this->conn->prepare($queryString)) {
            $stmt->bind_param("sssss", $name, $eventStartDate, $eventEndDate, $numallowed, $venueID);
            $stmt->execute();
            $stmt->store_result();
            $insertId=$stmt->insert_id;

            ?>
            <head>
            <title> Event Created </title>
            </head>
            <body>
            <p><strong>Event Creation Successful!</strong></p>
            </body>
            </html>
            <?php
        }
    } //insertEvent

    function getAllAttendeeEvents() {
        $data = array(); //or []
        if ($stmt = $this->conn->prepare("select * from attendee_event") ) {
            
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($idevent,$attendeeID,$paid);

            if  ($stmt->num_rows > 0) {

                while($stmt->fetch()) {
                    $data[] = ['idevent'=> $idevent,
                               'attendeeID'=> $attendeeID,
                               'paid'=> $paid];
                }
            }

        } // if stmt

        return $data;
    } //getAllAttendeeEvents

    function getAllAttendeeEventsAsTable() {
        $data = $this->getAllAttendeeEvents();
        if (count($data) > 0) {
            $bigString = "<table border='1'>\n
                            <tr><th>Event ID</th><th>Attendee ID</th><th>Paid</th></tr>\n";

            foreach ($data as $row) {
                $bigString .= "<tr><td>{$row['idevent']}</td>
                <td>{$row['attendeeID']}</td>
                <td>{$row['paid']}</td>
                </tr>\n";
            }

            $bigString .= "</table>\n";

        } //have rows
        else {
            $bigString = "<h2> No Users Exist.</h2>";
        }

        return $bigString;
    } //getAllAttendeeEventsAsTable

    function getAllManagerEvents() {
        $data = array(); //or []
        if ($stmt = $this->conn->prepare("select * from manager_event") ) {
            
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($eventID,$managerID);

            if  ($stmt->num_rows > 0) {

                while($stmt->fetch()) {
                    $data[] = ['eventID'=> $eventID,
                               'managerID'=> $managerID];
                }
            }

        } // if stmt

        return $data;
    } //getAllManagerEvents

    function insertManagerEvent($eventId, $managerID) {
        $queryString = "insert into manager_event (event, manager) values (?,?) ";

        if ($stmt = $this->conn->prepare($queryString)) {
            $stmt->bind_param("ss", $eventId, $managerID);
            $stmt->execute();
            $stmt->store_result();
        }
    } //insertManagerEvent

    function getAllSessions() {
        $data = array(); //or []
        if ($stmt = $this->conn->prepare("select * from session") ) {
            
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id,$name,$numallowed,$event,$startdate,$enddate);

            if  ($stmt->num_rows > 0) {

                while($stmt->fetch()) {
                    $data[] = ['id'=> $id,
                               'name'=> $name,
                               'numallowed'=> $numallowed,
                               'event'=> $event,
                               'startdate'=> $startdate,
                               'enddate'=> $enddate];
                }
            }

        } // if stmt

        return $data;
    } //getAllSessions

    function getAllAttendeeSessions() {
        $data = array(); //or []
        if ($stmt = $this->conn->prepare("select * from attendee_session") ) {
            
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($sessionID,$attendeeID);

            if  ($stmt->num_rows > 0) {

                while($stmt->fetch()) {
                    $data[] = ['sessionID'=> $sessionID,
                               'attendeeID'=> $attendeeID];
                }
            }

        } // if stmt

        return $data;
    } //getAllAttendeeSessions

    function getAllAttendeeSessionsAsTable() {
        $data = $this->getAllAttendeeSessions();
        if (count($data) > 0) {
            $bigString = "<table border='1'>\n
                            <tr><th>Session ID</th><th>Attendee ID</th></tr>\n";

            foreach ($data as $row) {
                $bigString .= "<tr><td>{$row['sessionID']}</td>
                <td>{$row['attendeeID']}</td>
                </tr>\n";
            }

            $bigString .= "</table>\n";

        } //have rows
        else {
            $bigString = "<h2> No Users Exist.</h2>";
        }

        return $bigString;
    } //getAllAttendeeEventsAsTable

    function insertAttendeeEvent($eventID, $attendeeID, $paid=0) {
        $queryString = "insert into attendee_event (event, attendee, paid) values (?,?,?) ";

        if ($stmt = $this->conn->prepare($queryString)) {
            $stmt->bind_param("sss", $eventID, $attendeeID, $paid);
            $stmt->execute();
            $stmt->store_result();

            ?>
            <head>
            <title> Registered For Event </title>
            </head>
            <body>
            <p><strong>Event Registration Successful!</strong></p>
            </body>
            </html>
            <?php
        }
    } //insertSession

    function insertSession($name, $numAllowed, $eventID, $sessionStartDate, $sessionEndDate) {
        $queryString = "insert into session (name, numberallowed, event, startdate, enddate) values (?,?,?,?,?) ";
        $insertId = -1;

        if ($stmt = $this->conn->prepare($queryString)) {
            $stmt->bind_param("sssss", $name, $numAllowed, $eventID, $sessionStartDate, $sessionEndDate);
            $stmt->execute();
            $stmt->store_result();
            $insertId=$stmt->insert_id;

            ?>
            <head>
            <title> Session Created </title>
            </head>
            <body>
            <p><strong>Session Creation Successful!</strong></p>
            </body>
            </html>
            <?php
        }
    } //insertSession

    function getAllSessionsAsTable() {
        $data = $this->getAllSessions();
        if (count($data) > 0) {
            $bigString = "<table border='1'>\n
                            <tr><th>Session ID</th><th>Event Name</th><th>Number Allowed</th><th>Event ID</th><th>Start Date</th><th>End Date</th></tr>\n";

            foreach ($data as $row) {
                $bigString .= "<tr><td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['numallowed']}</td>
                <td>{$row['event']}</td>
                <td>{$row['startdate']}</td>
                <td>{$row['enddate']}</td>
                </tr>\n";
            }

            $bigString .= "</table>\n";

        } //have rows
        else {
            $bigString = "<h2> No Sessions Exist.</h2>";
        }

        return $bigString;
    } //getAllSessionsAsTable


    function getAllUsers() {
        $data = array(); //or []
        if ($stmt = $this->conn->prepare("select * from attendee") ) {
            
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id,$name,$password,$role);

            if  ($stmt->num_rows > 0) {

                while($stmt->fetch()) {
                    $data[] = ['id'=> $id,
                               'name'=> $name,
                               'password'=> $password,
                               'role'=> $role];
                }
            }

        } // if stmt

        return $data;
    } //getAllUsers

    function getAllUsersAsTable() {
        $data = $this->getAllUsers();
        if (count($data) > 0) {
            $bigString = "<table border='1'>\n
                            <tr><th>User ID</th><th>User Name</th><th>User Password</th><th>Role</th></tr>\n";

            foreach ($data as $row) {
                $bigString .= "<tr><td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['password']}</td>
                <td>{$row['role']}</td>
                </tr>\n";
            }

            $bigString .= "</table>\n";

        } //have rows
        else {
            $bigString = "<h2> No Users Exist.</h2>";
        }

        return $bigString;
    } //getAllUsersAsTable
    

    function insertUser($name, $password, $role=3) {
        $queryString = "insert into attendee (name, password, role) values (?,?,?) ";
        $insertId = -1;

        if ($stmt = $this->conn->prepare($queryString)) {
            $stmt->bind_param("sss", $name, $password, $role);
            $stmt->execute();
            $stmt->store_result();
            $insertId=$stmt->insert_id;

            ?>
            <head>
            <title> Registration Complete </title>
            <meta http-equiv="Content-Type"
            content="text/html; charset=iso-8859-1" />
            </head>
            <body>
            <p><strong>User registration successful!</strong></p>
            <p>To log in, click <a href='pages.php?page=login'>here</a> to return to the login
            , and enter your name and password.</p>
            </body>
            </html>
            <?php
        }
    } //insertUser

    function getUser($name, $pwd) {
        $data = array(); //or []
        if ($stmt = $this->conn->prepare("select * from attendee WHERE name = '$name' AND password = '$pwd'") ) {
            
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id,$name,$password,$role);

            if  ($stmt->num_rows > 0) {

                while($stmt->fetch()) {
                    $data = ['id'=> $id,
                               'name'=> $name,
                               'password'=> $password,
                               'role'=> $role];
                }
            }

        } // if stmt
        return $data;
    } //getUser

    function checkLogin($name, $pwd) {
        $result = $this->getUser($name, $pwd);
        if (count($result) == 0) {
            unset($_SESSION['name']);
            unset($_SESSION['pwd']);
            ?>
            <html>
            <head>
            <title> Access Denied </title>
            </head>
            <body>
            <h1> Access Denied </h1>
            <p>Your name or password is incorrect, or you are not a
            registered user on this site. To try logging in again, click
            <a href="pages.php?page=login">here</a>. To register for instant
            access, click <a href="pages.php?page=signup">here</a>.</p>
            </body>
            </html>
            <?php
            exit;
        } else {
            header('Location: pages.php?page=loggedin');
        }

    }

    function roleCheck($name, $pwd) {
        $data = $this->getUser($name, $pwd);
        return $data['role'];
    }

    function getMyID($name, $pwd) {
        $data = $this->getUser($name, $pwd);
        return $data['id'];
    }

    function update($fields) {
        //should have validation that there is an id

        $queryString = "update attendee set ";
        $insertId = $fields['id'];

        $items = []; //values we are inserting
        $types = ""; //for character types

        foreach ($fields as $k => $v) {
            switch ($v) {
                case 'name':
                    $queryString .= "name = ?,";
                    $items[] = &$fields['changing'];
                    $types .= "s";
                    break;
                case 'pwd':
                    $queryString .= "password = ?,";
                    $items[] = &$fields['changing'];
                    $types .= "s";
                    break;
                case 'role':
                    $queryString .= "role = ?,";
                    $items[] = &$fields['changing'];
                    $types .= "s";
                    break;
            }
        } //foreach

        $queryString = trim($queryString, ",");
        $queryString .= " where idattendee = ?";
        $types .= "i";
        $items[] = &$insertId;

        if ($stmt = $this->conn->prepare($queryString)) {

            $refArr = array_merge(array($types), $items);
            $ref = new ReflectionClass('mysqli_stmt');
            $method = $ref->getMethod("bind_param");
            $method->invokeArgs($stmt, $refArr);


            $stmt->execute();
            $stmt->store_result();

        }
    } //update

    function updateVenue($fields) {
        //should have validation that there is an id

        $queryString = "update venue set ";
        $insertId = $fields['id'];

        $items = []; //values we are inserting
        $types = ""; //for character types

        foreach ($fields as $k => $v) {
            switch ($v) {
                case 'name':
                    $queryString .= "name = ?,";
                    $items[] = &$fields['changing'];
                    $types .= "s";
                    break;
                case 'cap':
                    $queryString .= "capacity = ?,";
                    $items[] = &$fields['changing'];
                    $types .= "s";
                    break;
            }
        } //foreach

        $queryString = trim($queryString, ",");
        $queryString .= " where idvenue = ?";
        $types .= "i";
        $items[] = &$insertId;

        if ($stmt = $this->conn->prepare($queryString)) {

            $refArr = array_merge(array($types), $items);
            $ref = new ReflectionClass('mysqli_stmt');
            $method = $ref->getMethod("bind_param");
            $method->invokeArgs($stmt, $refArr);


            $stmt->execute();
            $stmt->store_result();

        }
    } //updateVenue

    function updateEvent($fields) {
        //should have validation that there is an id

        $queryString = "update event set ";
        $insertId = $fields['id'];

        $items = []; //values we are inserting
        $types = ""; //for character types

        foreach ($fields as $k => $v) {
            switch ($v) {
                case 'name':
                    $queryString .= "name = ?,";
                    $items[] = &$fields['changing'];
                    $types .= "s";
                    break;
                case 'startdate':
                    $queryString .= "datestart = ?,";
                    $items[] = &$fields['changing'];
                    $types .= "s";
                    break;
                case 'enddate':
                    $queryString .= "dateend = ?,";
                    $items[] = &$fields['changing'];
                    $types .= "s";
                    break;
                case 'numallowed':
                        $queryString .= "numberallowed = ?,";
                        $items[] = &$fields['changing'];
                        $types .= "s";
                        break;
                case 'venueID':
                        $queryString .= "venue = ?,";
                        $items[] = &$fields['changing'];
                        $types .= "s";
                        break;
            }
        } //foreach

        $queryString = trim($queryString, ",");
        $queryString .= " where idevent = ?";
        $types .= "i";
        $items[] = &$insertId;

        if ($stmt = $this->conn->prepare($queryString)) {
            $refArr = array_merge(array($types), $items);
            $ref = new ReflectionClass('mysqli_stmt');
            $method = $ref->getMethod("bind_param");
            $method->invokeArgs($stmt, $refArr);

            $stmt->execute();
            $stmt->store_result();

        }
    } //updateVenue

    function updateAttendeeEvent($fields) {
        //should have validation that there is an id

        $queryString = "update attendee_event set ";
        $insertId = $fields['id'];
        $inserattendee = $fields['myID'];

        $items = []; //values we are inserting
        $types = ""; //for character types

        foreach ($fields as $k => $v) {
            switch ($v) {
                case 'eventID':
                    $queryString .= "event = ?,";
                    $items[] = &$fields['changing'];
                    $types .= "s";
                    break;
            }
        } //foreach

        $queryString = trim($queryString, ",");
        $queryString .= " where event = ?";
        $types .= "i";
        $items[] = &$insertId;

        $queryString = trim($queryString, ",");
        $queryString .= " and attendee = ?";
        $types .= "i";
        $items[] = &$inserattendee;

        if ($stmt = $this->conn->prepare($queryString)) {
            $refArr = array_merge(array($types), $items);
            $ref = new ReflectionClass('mysqli_stmt');
            $method = $ref->getMethod("bind_param");
            $method->invokeArgs($stmt, $refArr);

            $stmt->execute();
            $stmt->store_result();

        }
    } //updateVenue

    function updateSession($fields) {
        //should have validation that there is an id

        $queryString = "update session set ";
        $insertId = $fields['id'];

        $items = []; //values we are inserting
        $types = ""; //for character types

        foreach ($fields as $k => $v) {
            switch ($v) {
                case 'name':
                    $queryString .= "name = ?,";
                    $items[] = &$fields['changing'];
                    $types .= "s";
                    break;
                case 'startdate':
                    $queryString .= "startdate = ?,";
                    $items[] = &$fields['changing'];
                    $types .= "s";
                    break;
                case 'enddate':
                    $queryString .= "enddate = ?,";
                    $items[] = &$fields['changing'];
                    $types .= "s";
                    break;
                case 'numallowed':
                        $queryString .= "numberallowed = ?,";
                        $items[] = &$fields['changing'];
                        $types .= "s";
                        break;
                case 'eventID':
                        $queryString .= "event = ?,";
                        $items[] = &$fields['changing'];
                        $types .= "s";
                        break;
            }
        } //foreach

        $queryString = trim($queryString, ",");
        $queryString .= " where idsession = ?";
        $types .= "i";
        $items[] = &$insertId;

        if ($stmt = $this->conn->prepare($queryString)) {
            $refArr = array_merge(array($types), $items);
            $ref = new ReflectionClass('mysqli_stmt');
            $method = $ref->getMethod("bind_param");
            $method->invokeArgs($stmt, $refArr);

            $stmt->execute();
            $stmt->store_result();

        }
    } //updateVenue

    function deleteVenue($id) {
        //error messages
        $errorMsg = false;
        $found = false;
        $errorText = "<strong>ERRORS:</strong><br />";

        $data = $this->getAllVenues();

        if (count($data) > 0 ) {
            foreach ($data as $row) {
                if ($row['idvenue'] == $id) {
                    $found = true;
                }
            }
            if (!$found) {
                $errorText = $errorText.'Venue ID not found in venue.<br />';
                $errorMsg = true;
            } else {
                $queryString = "delete from venue where idvenue = ?";

                if ($stmt = $this->conn->prepare($queryString)) {
        
                    $stmt -> bind_param("i", intval($id));
                    $stmt->execute();
                    $stmt->store_result();
                }
            }
        }

        // $data = $this->getAllAttendeeEvents();

        // if (count($data) > 0 ) {
        //     foreach ($data as $row) {
        //         if ($row['idevent'] == $id) {
        //             $found = true;
        //         }
        //     }
        //     if (!$found) {
        //         $errorText = $errorText.'Event ID not found in attendee_event.<br />';
        //         $errorMsg = true;
        //     } else {
        //         $queryString = "delete from attendee_event where event = ?";

        //         if ($stmt = $this->conn->prepare($queryString)) {
        
        //             $stmt -> bind_param("i", intval($id));
        //             $stmt->execute();
        //             $stmt->store_result();
        //         }
        //     }
        // }


        // $data = $this->getAllSessions();
        // if (count($data) > 0) {
        //     foreach ($data as $row) {
        //         if ($row['event'] == $id) {
        //             $found = true;
        //         }
        //     } 
        //     if (!$found) {
        //         $errorText = $errorText.'Event ID not found in session.<br />';
        //         $errorMsg = true;
        //     } else {
        //         $queryString = "delete from session where event = ?";

        //         if ($stmt = $this->conn->prepare($queryString)) {
        //             $stmt -> bind_param("i", intval($id));
        //             $stmt->execute();
        //             $stmt->store_result();
        //         }
        //     }
        // }

        return array($errorMsg, $errorText);


    } //delete

    function deleteEvent($id) {
        //error messages
        $errorMsg = false;
        $found = false;
        $errorText = "<strong>ERRORS:</strong><br />";

        $data = $this->getAllEvents();

        if (count($data) > 0 ) {
            foreach ($data as $row) {
                if ($row['idevent'] == $id) {
                    $found = true;
                }
            }
            if (!$found) {
                $errorText = $errorText.'Event ID not found in event.<br />';
                $errorMsg = true;
            } else {
                $queryString = "delete from event where idevent = ?";

                if ($stmt = $this->conn->prepare($queryString)) {
        
                    $stmt -> bind_param("i", intval($id));
                    $stmt->execute();
                    $stmt->store_result();
                }
            }
        }

        // $data = $this->getAllAttendeeEvents();

        // if (count($data) > 0 ) {
        //     foreach ($data as $row) {
        //         if ($row['idevent'] == $id) {
        //             $found = true;
        //         }
        //     }
        //     if (!$found) {
        //         $errorText = $errorText.'Event ID not found in attendee_event.<br />';
        //         $errorMsg = true;
        //     } else {
        //         $queryString = "delete from attendee_event where event = ?";

        //         if ($stmt = $this->conn->prepare($queryString)) {
        
        //             $stmt -> bind_param("i", intval($id));
        //             $stmt->execute();
        //             $stmt->store_result();
        //         }
        //     }
        // }


        // $data = $this->getAllSessions();
        // if (count($data) > 0) {
        //     foreach ($data as $row) {
        //         if ($row['event'] == $id) {
        //             $found = true;
        //         }
        //     } 
        //     if (!$found) {
        //         $errorText = $errorText.'Event ID not found in session.<br />';
        //         $errorMsg = true;
        //     } else {
        //         $queryString = "delete from session where event = ?";

        //         if ($stmt = $this->conn->prepare($queryString)) {
        //             $stmt -> bind_param("i", intval($id));
        //             $stmt->execute();
        //             $stmt->store_result();
        //         }
        //     }
        // }

        return array($errorMsg, $errorText);


    } //delete

    function deleteSession($id) {
        //error messages
        $errorMsg = false;
        $found = false;
        $errorText = "<strong>ERRORS:</strong><br />";

        $data = $this->getAllSessions();

        if (count($data) > 0 ) {
            foreach ($data as $row) {
                if ($row['id'] == $id) {
                    $found = true;
                }
            }
            if (!$found) {
                $errorText = $errorText.'Session ID not found in session.<br />';
                $errorMsg = true;
            } else {
                $queryString = "delete from session where idsession = ?";

                if ($stmt = $this->conn->prepare($queryString)) {
        
                    $stmt -> bind_param("i", intval($id));
                    $stmt->execute();
                    $stmt->store_result();
                }
            }
        }

        // $data = $this->getAllAttendeeEvents();

        // if (count($data) > 0 ) {
        //     foreach ($data as $row) {
        //         if ($row['idevent'] == $id) {
        //             $found = true;
        //         }
        //     }
        //     if (!$found) {
        //         $errorText = $errorText.'Event ID not found in attendee_event.<br />';
        //         $errorMsg = true;
        //     } else {
        //         $queryString = "delete from attendee_event where event = ?";

        //         if ($stmt = $this->conn->prepare($queryString)) {
        
        //             $stmt -> bind_param("i", intval($id));
        //             $stmt->execute();
        //             $stmt->store_result();
        //         }
        //     }
        // }


        // $data = $this->getAllSessions();
        // if (count($data) > 0) {
        //     foreach ($data as $row) {
        //         if ($row['event'] == $id) {
        //             $found = true;
        //         }
        //     } 
        //     if (!$found) {
        //         $errorText = $errorText.'Event ID not found in session.<br />';
        //         $errorMsg = true;
        //     } else {
        //         $queryString = "delete from session where event = ?";

        //         if ($stmt = $this->conn->prepare($queryString)) {
        //             $stmt -> bind_param("i", intval($id));
        //             $stmt->execute();
        //             $stmt->store_result();
        //         }
        //     }
        // }

        return array($errorMsg, $errorText);


    } //delete


    function deleteManagerEvent($eventId) {

        $queryString = "delete from manager_event where event = ?";

        if ($stmt = $this->conn->prepare($queryString)) {

            $stmt -> bind_param("i", intval($eventId));
            $stmt->execute();
            $stmt->store_result();
        }
    } //deleteManagerEvent

    function deleteAttendeeEvent($eventId, $myID) {

        $queryString = "delete from attendee_event where event = ? and attendee = ?";

        if ($stmt = $this->conn->prepare($queryString)) {

            $stmt -> bind_param("ii", intval($eventId), intval($myID));
            $stmt->execute();
            $stmt->store_result();
        }
    } //deleteAttendeeEvent

    function deleteAttendeeSession($sessionID) {

        $queryString = "delete from attendee_session where session = ?";

        if ($stmt = $this->conn->prepare($queryString)) {

            $stmt -> bind_param("i", intval($eventId));
            $stmt->execute();
            $stmt->store_result();
        }
    } //deleteAttendeeSession

    function delete($id) {
        //error messages
        $errorMsg = false;
        $found = false;
        $errorText = "<strong>ERRORS:</strong><br />";

        $data = $this->getAllUsers();

        if (count($data) > 0 ) {
            foreach ($data as $row) {
                if ($row['id'] == $id) {
                    $found = true;
                }
            }
            if (!$found) {
                $errorText = $errorText.'User ID not found in attendee.<br />';
                $errorMsg = true;
            } else {
                $queryString = "delete from attendee where idattendee = ?";

                if ($stmt = $this->conn->prepare($queryString)) {
        
                    $stmt -> bind_param("i", intval($id));
                    $stmt->execute();
                    $stmt->store_result();
                }
            }
        }

        $data = $this->getAllAttendeeEvents();

        if (count($data) > 0 ) {
            foreach ($data as $row) {
                if ($row['attendeeID'] == $id) {
                    $found = true;
                }
            }
            if (!$found) {
                $errorText = $errorText.'User ID not found in attendee_event.<br />';
                $errorMsg = true;
            } else {
                $queryString = "delete from attendee_event where attendee = ?";

                if ($stmt = $this->conn->prepare($queryString)) {
        
                    $stmt -> bind_param("i", intval($id));
                    $stmt->execute();
                    $stmt->store_result();
                }
            }
        }


        $data = $this->getAllAttendeeSessions();
        if (count($data) > 0) {
            foreach ($data as $row) {
                if ($row['attendeeID'] == $id) {
                    $found = true;
                }
            } 
            if (!$found) {
                $errorText = $errorText.'User ID not found in attendee_session.<br />';
                $errorMsg = true;
            } else {
                $queryString = "delete from attendee_session where attendee = ?";

                if ($stmt = $this->conn->prepare($queryString)) {
                    $stmt -> bind_param("i", intval($id));
                    $stmt->execute();
                    $stmt->store_result();
                }
            }
        }

        return array($errorMsg, $errorText);


    } //delete
} //class