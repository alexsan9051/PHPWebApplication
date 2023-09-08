
<?php
    require_once("DB.class.php");
    require_once("validations.php");
    session_start();

function navigation() {
    $db = new DB();
    $name = $_SESSION['name'];
    $pwd = $_SESSION['pwd'];
    $role = $db->roleCheck($name, $pwd);
    $myID = $db->getMyID($name, $pwd);
    $_SESSION['role'] = $role;
    $_SESSION['myID'] = $myID;

    $bigString = "<div class='navbar'>";

    if ($role <= 3) {
        $bigString .= "<a href='?page=events'>Events</a> <a href='?page=registration'>Registration</a>";
    }
    if ($role <= 2) {
        $bigString .= "<a href='?page=admin'>Admin</a>";
    }
    $bigString .= "</div>";
    return $bigString;
}

function sanitizeString($var){
  $var = trim($var);
  $var = stripslashes($var);
  $var = htmlentities($var);
  $var = strip_tags($var);
  return $var;
}

function putPage($page) {
    // put a list of allowed pages here
    $allowed = array('signup', 'login');

    $page = trim($page);
    $page = (in_array($page, $allowed)) ? $page : 'home';

    echo @file_get_contents($page . '.html');

}


function checkPage($page) {
    switch ($page) {
		case "signup":
            ?>
            <div class='navbar'>
        <a href='?page=login'>Login!</a>
        </div>
            <?php
			// $CURRENT_PAGE = "About"; 
			// $PAGE_TITLE = "About Us";
			break;
		case "login":
            ?>
            <div class='navbar'>
            <a href='?page=signup'>Sign Up!</a>
            </div>
            <?php
            break;
		case "loggedin":
            $PAGE_TITLE = "Logged In";
            echo navigation();
			// $CURRENT_PAGE = "Contact"; 
			break;
		case "events":
            $PAGE_TITLE = "Events";
            echo navigation();
            $db = new DB();
    
            $string = $db->getAllEventsAsTable();
            echo $string;
            $string = $db->getAllSessionsAsTable();
            echo $string;
			break;
		case "registration":
            $PAGE_TITLE = "Registration";
            echo navigation();
            ?>
            <h2>Manage registration for Events:</h2>
            <div id='eventTable'></div>
            <input type="button" name="add" value="Add" onclick="toggle_visibility('registerEventForm');"/>
            <div id="registerEventForm" style="visibility:hidden; display: none;">
                    <form name="frmRegisterEvent" method="POST" action="">
                        <div id=frmRE>
                        <p>Event ID you wish to register for:<p>
                        <textarea name="eventIDRE" id="eventIDRE"
                        class="eventIDRE" rows="1"></textarea>
                        <button type="submit" name="addRESubmit" id="addRESubmit"
                    class="btn-submit">Register for event</button>
                        </div> 
                    </form>
                </div>
            <input type="button" name="update" value="Update" onclick="toggle_visibility('editEventREForm');"/>
            <div id="editEventREForm" style="visibility:hidden; display: none;">
                    <form name="editREEvent" method="POST" action="">
                        <div id=frmEditREEvent>
                        <p>Event ID:<p>
                        <textarea name="eventIDRE" id="eventIDRE"
                        class="eventIDRE" rows="1"></textarea>
                        <label for="editEventRE">Select what to edit:</label>
                        <select id='editEventRE' name="editEventRE">
                          <option name= "eventID" value="eventID">Event ID</option>
                        </select>
                        <div id=editEventID style="visibility:visible; display: block;"><p>Editing Event ID You are Registered for:<p><textarea name="neweventID"></textarea></div>
                        <button type="submit" name="editRESubmit" id="editRESubmit"
                    class="btn-submit">Edit Registration</button>
                        </div> 
                    </form>
                </div>
            <input type="button" name="delete" value="Delete" onclick="toggle_visibility('dltREEventForm');" />
            <div id="dltREEventForm" style="visibility:hidden; display: none;">
                    <form name="dltREEvent" method="POST" action="">
                        <div id=frmDltREEvent>
                        <p>Event ID you are registered to that you wish to delete:<p>
                        <textarea name="eventID" id="eventID"
                        class="eventID" rows="1"></textarea>
                        <button type="submit" name="dltREEventSubmit" id="dltREEventSubmit"
                    class="btn-submit">Delete Event Registration</button>
                        </div> 
                    </form>
                </div>
            <input type="button" name="view" value="View" onclick="viewEvent(); viewAttendeeEvents();" />
            <h2>Manage registration for Sessions:</h2>
            <div id='sessionTable'></div>
            <input type="button" name="add" value="Add" />
            <input type="button" name="update" value="Update" />
            <input type="button" name="delete" value="Delete" />
            <input type="button" name="view" value="View" onclick="viewSessions(); viewAttendeeSessions();" />
            <?php
            break;
        case "admin":
            $PAGE_TITLE = "Admin";
            echo navigation();
            $db = new DB();
            $name = $_SESSION['name'];
            $pwd = $_SESSION['pwd'];
            $role = $db->roleCheck($name, $pwd);
            if ($role <= 2) {
                ?>
                <h2>Manage Users:</h2>
                <div id='userTable'></div>
                <input type="button" name="add" value="Add" onclick="toggle_visibility('addUserForm');" />
                <div id="addUserForm" style="visibility:hidden; display: none;">
                    <form name="frmAddUser" method="POST" action="">
                        <div id=frmAdd>
                        <p>User name:<p>
                        <textarea name="userName" id="userName"
                        class="userName" rows="1"></textarea>
                        <p>User password:<p>
                        <textarea name="userPwd" id="userPwd"
                        class="userPwd" rows="1"></textarea>
                        <p>User role:<p>
                        <textarea name="userRole" id="userRole"
                        class="userRole" rows="1"></textarea>
                        <button type="submit" name="addSubmit" id="addSubmit"
                    class="btn-submit">Add User</button>
                        </div> 
                    </form>
                </div>
                <input type="button" name="edit" value="Edit" onclick="toggle_visibility('editUserForm'); getSelect('editing');" />
                <div id="editUserForm" style="visibility:hidden; display: none;">
                    <form name="editUser" method="POST" action="">
                        <div id=frmEdit>
                        <p>User ID:<p>
                        <textarea name="userID" id="userID"
                        class="userID" rows="1"></textarea>
                        <label for="editing">Select what to edit:</label>
                        <select id='editing' name="editing">
                          <option name= "name" value="name">Name</option>
                          <option name= "pwd" value="pwd">Password</option>
                          <option name="role" value="role">Role</option>
                        </select>
                        <div id=editname style="visibility:visible; display: block;"><p>New User name:<p><textarea name="userName"></textarea></div>
                        <div id=editpass style="visibility:hidden; display: none;"><p>New User Password:<p><textarea name="userPwd"></textarea></div>
                        <div id=editrole style="visibility:hidden; display: none;"><p>New User Role:<p><textarea name="userRole"></textarea></div>
                        <button type="submit" name="editSubmit" id="editSubmit"
                    class="btn-submit">Edit User</button>
                        </div> 
                    </form>
                </div>
                <input type="button" name="delete" value="Delete" onclick="toggle_visibility('dltUserForm');"/>
                <div id="dltUserForm" style="visibility:hidden; display: none;">
                    <form name="dltUser" method="POST" action="">
                        <div id=frmDlt>
                        <p>User ID:<p>
                        <textarea name="userID" id="userID"
                        class="userID" rows="1"></textarea>
                        <button type="submit" name="dltSubmit" id="dltSubmit"
                    class="btn-submit">Delete User</button>
                        </div> 
                    </form>
                </div>
                <input type="button" name="view" value="View" onclick="viewUsers()" />
            <?php
                  if ($_SESSION['role'] <= 1) {
                    echo '<div id="ManageVenues" style="visibility:visible; display: block;">';
                  } else {
                    echo '<div id="ManageVenues" style="visibility:hidden; display: none;">';
                  }
            ?>
                <h2>Manage Venues:</h2>
                <div id='venueTable'></div>
                <input type="button" name="add" value="Add" onclick="toggle_visibility('addVenueForm');" />
                <div id="addVenueForm" style="visibility:hidden; display: none;">
                    <form name="frmAddVenue" method="POST" action="">
                        <div id=frmVenueAdd>
                        <p>Venue name:<p>
                        <textarea name="venueName" id="venueName"
                        class="venueName" rows="1"></textarea>
                        <p>Capacity:<p>
                        <textarea name="venueCap" id="venueCap"
                        class="venueCap" rows="1"></textarea>
                        <button type="submit" name="addVenueSubmit" id="addVenueSubmit"
                    class="btn-submit">Add Venue</button>
                        </div> 
                    </form>
                </div>
                <input type="button" name="edit" value="Edit" onclick="toggle_visibility('editVenueForm');getSelect('editVenue');" />
                <div id="editVenueForm" style="visibility:hidden; display: none;">
                    <form name="editVenue" method="POST" action="">
                        <div id=frmEditVenue>
                        <p>Venue ID:<p>
                        <textarea name="venueID" id="venueID"
                        class="venueID" rows="1"></textarea>
                        <label for="editVenue">Select what to edit:</label>
                        <select id='editVenue' name="editVenue">
                          <option name= "name" value="name">Name</option>
                          <option name= "cap" value="cap">Capacity</option>
                        </select>
                        <div id=editVenueName style="visibility:visible; display: block;"><p>New Venues Name:<p><textarea name="venueName"></textarea></div>
                        <div id=editcap style="visibility:hidden; display: none;"><p>New Venue Capacity:<p><textarea name="venueCap"></textarea></div>
                        <button type="submit" name="editVenueSubmit" id="editVenueSubmit"
                    class="btn-submit">Edit User</button>
                        </div> 
                    </form>
                </div>
                <input type="button" name="delete" value="Delete" onclick="toggle_visibility('dltVenueForm');"/>
                <div id="dltVenueForm" style="visibility:hidden; display: none;">
                    <form name="dltVenue" method="POST" action="">
                        <div id=frmDltVenue>
                        <p>Venue ID:<p>
                        <textarea name="venueID" id="venueID"
                        class="venueID" rows="1"></textarea>
                        <button type="submit" name="dltVenueSubmit" id="dltVenueSubmit"
                    class="btn-submit">Delete Venue</button>
                        </div> 
                    </form>
                </div>
                <input type="button" name="view" value="View" onclick="viewVenue()"/>
                </div>

                <h2>Manage Events:</h2>
                <div id='eventTable'></div>
                <input type="button" name="add" value="Add" onclick="toggle_visibility('addEventForm');" />
                <div id="addEventForm" style="visibility:hidden; display: none;">
                    <form name="frmEventUser" method="POST" action="">
                        <div id=frmAddEvent>
                        <p>Event Name:<p>
                        <textarea name="eventName" id="eventName"
                        class="eventName" rows="1"></textarea>
                        <br>
                        <br>
                        <label for="eventStartDate">Start Date & Time</label>
                        <br>
                        <input name="eventStartDate" id="eventStartDate" type="datetime-local"/>
                        <br>
                        <br>
                        <label for="eventEndDate">End Date & Time</label>
                        <br>
                        <input name="eventEndDate" id="eventEndDate" type="datetime-local"/>
                        <br>
                        <p>Number Allowed:<p>
                        <textarea name="numAllowed" id="numAllowed"
                        class="numAllowed" rows="1"></textarea>
                        <p>Venue ID:<p>
                        <textarea name="venueID" id="venueID"
                        class="venueID" rows="1"></textarea>
                        <button type="submit" name="addEventSubmit" id="addEventSubmit"
                    class="btn-submit">Add Event</button>
                        </div> 
                    </form>
                </div>
                <input type="button" name="edit" value="Edit" onclick="toggle_visibility('editEventForm');getSelect('editEvent');"/>
                <div id="editEventForm" style="visibility:hidden; display: none;">
                    <form name="editEvent" method="POST" action="">
                        <div id=frmEditEvent>
                        <p>Event ID:<p>
                        <textarea name="eventID" id="eventID"
                        class="eventID" rows="1"></textarea>
                        <label for="editEvent">Select what to edit:</label>
                        <select id='editEvent' name="editEvent">
                          <option name= "name" value="name">Name</option>
                          <option name= "startdate" value="startdate">Start Date</option>
                          <option name= "enddate" value="enddate">End Date</option>
                          <option name= "numallowed" value="numallowed">Number Allowed</option>
                          <option name= "venueID" value="venueID">Venue ID</option>
                        </select>
                        <div id=editEventName style="visibility:visible; display: block;"><p>Editing Event Name:<p><textarea name="eventName"></textarea></div>
                        <div id=editEventStartDate style="visibility:hidden; display: none;"><label for="eventStartDate">Editing Start Date & Time</label><br><input name="eventStartDate" id="eventStartDate" type="datetime-local"/></div>
                        <div id=editEventEndDate style="visibility:hidden; display: none;"><label for="eventEndDate">Editing End Date & Time</label><br><input name="eventEndDate" id="eventEndDate" type="datetime-local"/></div>
                        <div id=editnumallowed style="visibility:hidden; display: none;"><p>Editing Event Capacity:<p><textarea name="eventNumAllowed"></textarea></div>
                        <div id=editVenueID style="visibility:hidden; display: none;"><p>Editing Venue, enter Venue ID:<p><textarea name="eventVenueID"></textarea></div>
                        <button type="submit" name="editEventSubmit" id="editEventSubmit"
                    class="btn-submit">Edit Event</button>
                        </div> 
                    </form>
                </div>
                <input type="button" name="delete" value="Delete" onclick="toggle_visibility('dltEventForm');"/>
                <div id="dltEventForm" style="visibility:hidden; display: none;">
                    <form name="dltEvent" method="POST" action="">
                        <div id=frmDltEvent>
                        <p>Event ID:<p>
                        <textarea name="eventID" id="eventID"
                        class="eventID" rows="1"></textarea>
                        <button type="submit" name="dltEventSubmit" id="dltEventSubmit"
                    class="btn-submit">Delete Event</button>
                        </div> 
                    </form>
                </div>
                <input type="button" name="view" value="View" onclick="viewEvent()"/>
                <h2>Manage Sessions:</h2>
                <div id='sessionTable'></div>
                <input type="button" name="add" value="Add" onclick="toggle_visibility('addSessionForm');" />
                <div id="addSessionForm" style="visibility:hidden; display: none;">
                    <form name="frmSession" method="POST" action="">
                        <div id=frmAddSession>
                        <p>Session Name:<p>
                        <textarea name="sessionName" id="sessionName"
                        class="sessionName" rows="1"></textarea>
                        <p>Number Allowed:<p>
                        <textarea name="sessionsnumAllowed" id="sessionsnumAllowed"
                        class="sessionsnumAllowed" rows="1"></textarea>
                        <p>Event ID:<p>
                        <textarea name="eventID" id="eventID"
                        class="eventID" rows="1"></textarea>
                        <br>
                        <br>
                        <label for="sessionStartDate">Start Date & Time</label>
                        <br>
                        <input name="sessionStartDate" id="sessionStartDate" type="datetime-local"/>
                        <br>
                        <br>
                        <label for="sessionEndDate">End Date & Time</label>
                        <br>
                        <input name="sessionEndDate" id="sessionEndDate" type="datetime-local"/>
                        <button type="submit" name="addSessionSubmit" id="addSessionSubmit"
                    class="btn-submit">Add Session</button>
                        </div> 
                    </form>
                </div>
                <input type="button" name="edit" value="Edit" onclick="toggle_visibility('editSessionForm');getSelect('editSession');"/>
                <div id="editSessionForm" style="visibility:hidden; display: none;">
                    <form name="editSession" method="POST" action="">
                        <div id=frmEditSession>
                        <p>Session ID:<p>
                        <textarea name="sessionID" id="sessionID"
                        class="sessionID" rows="1"></textarea>
                        <label for="editSession">Select what to edit:</label>
                        <select id='editSession' name="editSession">
                          <option name= "name" value="name">Name</option>
                          <option name= "numallowed" value="numallowed">Number Allowed</option>
                          <option name= "eventID" value="eventID">Event ID</option>
                          <option name= "startdate" value="startdate">Start Date</option>
                          <option name= "enddate" value="enddate">End Date</option>
                        </select>
                        <div id=editSessionName style="visibility:visible; display: block;"><p>Editing Session Name:<p><textarea name="sessionName"></textarea></div>
                        <div id=editSessionStartDate style="visibility:hidden; display: none;"><label for="eventStartDate">Editing Start Date & Time</label><br><input name="sessionStartDate" id="sessionStartDate" type="datetime-local"/></div>
                        <div id=editSessionEndDate style="visibility:hidden; display: none;"><label for="eventEndDate">Editing End Date & Time</label><br><input name="sessionEndDate" id="sessionEndDate" type="datetime-local"/></div>
                        <div id=editSnumallowed style="visibility:hidden; display: none;"><p>Editing Session Number Allowed:<p><textarea name="sessionNumAllowed"></textarea></div>
                        <div id=editEventID style="visibility:hidden; display: none;"><p>Editing Event, enter Event ID:<p><textarea name="sessionEventID"></textarea></div>
                        <button type="submit" name="editSessionSubmit" id="editSessionSubmit"
                    class="btn-submit">Edit Event</button>
                        </div> 
                    </form>
                </div>
                <input type="button" name="delete" value="Delete" onclick="toggle_visibility('dltSessionForm');"/>
                <div id="dltSessionForm" style="visibility:hidden; display: none;">
                    <form name="dltSession" method="POST" action="">
                        <div id=frmDltEvent>
                        <p>Session ID:<p>
                        <textarea name="sessionID" id="sessionID"
                        class="sessionID" rows="1"></textarea>
                        <button type="submit" name="dltSessionSubmit" id="dltSessionSubmit"
                    class="btn-submit">Delete Session</button>
                        </div> 
                    </form>
                </div>
                <input type="button" name="view" value="View" onclick="viewSessions()"/>
                <?php
                break;
            } else {
                echo "<h1> Access Denied </h1>";
            }

        }
}

?>

<html>
    <head>
        <title><?php print $PAGE_TITLE;?></title>
        <!-- put stylesheets, js files, etc. here -->
    </head>
    <body>
        
        <?php
          // if the register for event submit button is pressed, create the user
          if(isset($_POST['addRESubmit'])) {
            //Init error variables
				    $errorMsg = false;
				    $errorText = "<strong>ERRORS:</strong><br />";

            //grab lowest possible ID for Events and highest possible ID
            $db = new DB();
            $data = $db->getAllEvents();
            $maxVenue = max($data);
            $EventMaxID = $maxVenue['idevent'];

            $lowVenue = min($data);
            $EventLowID = $lowVenue['idevent'];

       			// grab  event ID
				    $eventIDRE = isset($_POST['eventIDRE']) ? sanitizeString($_POST['eventIDRE']) : '';

            // validate role, making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if ($eventIDRE == "" || $eventIDRE > $EventMaxID || $eventIDRE < $EventLowID || (!numbers($eventIDRE) && !integer($eventIDRE))) {
              $errorText = $errorText.'You entered invalid event ID.<br />';
              $errorMsg = true;
            }

            // Display error
				    if ($errorMsg) {
					    echo '<div id="error">'.$errorText.'</div>';
            } else {
              $db->insertAttendeeEvent($eventIDRE, $_SESSION['myID']);
            }

          } //addRESubmit

          if(isset($_POST['editRESubmit'])) {
            //Init error variables
				    $errorMsg = false;
				    $errorText = "<strong>ERRORS:</strong><br />";


            $db = new DB();

            //grab lowest possible ID for Events and highest possible ID
            $data = $db->getAllEvents();
            $maxVenue = max($data);
            $EventMaxID = $maxVenue['idevent'];

            $lowVenue = min($data);
            $EventLowID = $lowVenue['idevent'];

            // grab form
				    $id = isset($_POST['eventIDRE']) ? sanitizeString($_POST['eventIDRE']) : '';
            $editing = isset($_POST['editEventRE']) ? sanitizeString($_POST['editEventRE']) : '';
            $changing='';
            if ($editing == 'eventID') {
              $editEventID = isset($_POST['neweventID']) ? sanitizeString($_POST['neweventID']) : '';
            // validate number allowed , making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if ($editEventID == "" || $editEventID < $EventLowID || $EventMaxID > $EventMaxID || (!numbers($editEventID) && !integer($editEventID))) {
              $errorText = $errorText.'You entered invalid new Event ID.<br />';
              $errorMsg = true;
            } else {
              $changing = $editEventID;
            }
            }

            // validate event ID, making sure it isn't empty. If it is, assign error text and make errorMsg true
            if ($id == "" || $id < $EventLowID || $id > $EventMaxID || (!numbers($id) && !integer($id))) {
              $errorText = $errorText.'You entered invalid Event ID.<br />';
              $errorMsg = true;
            }

            // Display error
				    if ($errorMsg) {
					    echo '<div id="error">'.$errorText.'</div>';
            } else {
              $db->updateAttendeeEvent(array('id'=>$id,'editing'=>$editing,'changing'=>$changing, 'myID'=>$_SESSION['myID']));
              echo "Attendee Event Updated";
            }
          } //editRESubmit

          if(isset($_POST['dltREEventSubmit'])) {

            //Init error variables
				    $errorMsg = false;
				    $errorText = "<strong>ERRORS:</strong><br />";

       			// grab event id
				    $id = isset($_POST['eventID']) ? sanitizeString($_POST['eventID']) : '';

            //grab lowest possible ID and highest possible ID
            $db = new DB();
            $data = $db->getAllEvents();
            $maxEvent = max($data);
            $maxID = $maxEvent['idevent'];

            $lowEvent = min($data);
            $lowID = $lowEvent['idevent'];

            // validate event ID, making sure it isn't empty. If it is, assign error text and make errorMsg true
            if ($id == "" || $id < $lowID || $id > $maxID || (!numbers($id) && !integer($id))) {
              $errorText = $errorText.'You entered invalid Event ID.<br />';
              $errorMsg = true;
            }

            //validate that the event ID is one that you are registered to
              $found = false;
              $ManID = $_SESSION['myID'];
              $data = $db->getAllAttendeeEvents();
              foreach ($data as $arr) {
                $output[$arr['idevent']] = $arr['attendeeID'];
                foreach ($output as $k=>$v) {;
                  if ($k == $id && $v == $ManID) {
                    $found = true;
                  }
                }
              }
              if (!$found) {
                $errorText = $errorText."You entered an event ID that you're not attending.<br />";
                $errorMsg = true;
              }

            // Display error
				    if ($errorMsg) {
					    echo '<div id="error">'.$errorText.'</div>';
            } else {
              $db->deleteAttendeeEvent($id, $ManID);
              echo "Event Deleted";
            }
          } //dltEventSubmit

          // if the add user submit button is pressed, create the user
          if(isset($_POST['addSubmit'])) {
            //Init error variables
				    $errorMsg = false;
				    $errorText = "<strong>ERRORS:</strong><br />";

       			// grab  name, password, and role
				    $name = isset($_POST['userName']) ? sanitizeString($_POST['userName']) : '';
				    $password = isset($_POST['userPwd']) ? sanitizeString($_POST['userPwd']) : '';
				    $role = isset($_POST['userRole']) ? sanitizeString($_POST['userRole']) : '';

            // validate name, making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if($name == "" || (!alphaNumeric($name) || strlen($name) > 25)) {
              $errorText = $errorText.'You must enter a valid name.<br />';
              $errorMsg = true;
            }

            // validate password , making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if($password == "" || (strlen($password) > 20)) {
              $errorText = $errorText.'You must enter a valid password.<br />';
              $errorMsg = true;
            }

            // validate role, making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if ($role == "" || $role > 3 || $role < 1 || (!numbers($role) && !integer($role) || strlen($role) < 1)) {
              $errorText = $errorText.'You entered invalid role.<br />';
              $errorMsg = true;
            }

            // Display error
				    if ($errorMsg) {
					    echo '<div id="error">'.$errorText.'</div>';
            } else {
              $db = new DB();

              $db->insertUser($name, $password, $role);
            }

          } //addSubmit

          if (isset($_POST['editSubmit'])) {
            //Init error variables
				    $errorMsg = false;
				    $errorText = "<strong>ERRORS:</strong><br />";

            //grab lowest possible ID and highest possible ID
            $db = new DB();
            $data = $db->getAllUsers();
            $maxUser = max($data);
            $maxID = $maxUser['id'];

            $lowUser = min($data);
            $lowID = $lowUser['id'];

            // grab form
				    $id = isset($_POST['userID']) ? sanitizeString($_POST['userID']) : '';
            $editing = isset($_POST['editing']) ? sanitizeString($_POST['editing']) : '';
            $changing='';
            if ($editing == 'name') {
              $editname = isset($_POST['userName']) ? sanitizeString($_POST['userName']) : '';
            // validate name, making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if($editname == "" || (!alphaNumeric($editname) || strlen($editname) > 25)) {
              $errorText = $errorText.'You must enter a new valid name.<br />';
              $errorMsg = true;
            } else {
              $changing = $editname;
            }
            }

            if ($editing == 'pwd') {
              $editpwd = isset($_POST['userPwd']) ? sanitizeString($_POST['userPwd']) : '';
            // validate password , making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if($editpwd == "" || (strlen($editpwd) > 20)) {
              $errorText = $errorText.'You must enter a new valid password.<br />';
              $errorMsg = true;
            } else {
              $changing = $editpwd;
            }
            }

            if ($editing == 'role') {
              $editrole = isset($_POST['userRole']) ? sanitizeString($_POST['userRole']) : '';
            // validate role, making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if ($editrole == "" || $editrole > 3 || $editrole < 1 || (!numbers($editrole) && !integer($editrole) || strlen($editrole) < 1)) {
              $errorText = $errorText.'You entered invalid new role.<br />';
              $errorMsg = true;
            } else {
              $changing = $editrole;
            }
            }

            // validate user ID, making sure it isn't empty. If it is, assign error text and make errorMsg true
            if ($id == "" || $id < $lowID+1 || $id > $maxID || (!numbers($id) && !integer($id))) {
              $errorText = $errorText.'You entered invalid ID.<br />';
              $errorMsg = true;
            }

            // Display error
				    if ($errorMsg) {
					    echo '<div id="error">'.$errorText.'</div>';
            } else {
              $db->update(array('id'=>$id,'editing'=>$editing,'changing'=>$changing));
              echo "User Updated";
            }
          } //editSubmit

          // if the delete user submit button is pressed, delete the user
          if(isset($_POST['dltSubmit'])) {

            //Init error variables
				    $errorMsg = false;
				    $errorText = "<strong>ERRORS:</strong><br />";

       			// grab  user id
				    $id = isset($_POST['userID']) ? sanitizeString($_POST['userID']) : '';

            //grab lowest possible ID and highest possible ID
            $db = new DB();
            $data = $db->getAllUsers();
            $maxUser = max($data);
            $maxID = $maxUser['id'];

            $lowUser = min($data);
            $lowID = $lowUser['id'];

            // validate user ID, making sure it isn't empty. If it is, assign error text and make errorMsg true
            if ($id == "" || $id < $lowID+1 || $id > $maxID || (!numbers($id) && !integer($id))) {
              $errorText = $errorText.'You entered invalid ID.<br />';
              $errorMsg = true;
            }

            // Display error
				    if ($errorMsg) {
					    echo '<div id="error">'.$errorText.'</div>';
            } else {
              $errors=array();
              $errors = $db->delete($id);

              if ($errors[0]) {
                echo '<div id="error">'.$errors[1].'</div>';
              } else{
                echo "User Deleted";
              }
            }
          } //dltSubmit

          if(isset($_POST['addVenueSubmit'])) {
            //Init error variables
				    $errorMsg = false;
				    $errorText = "<strong>ERRORS:</strong><br />";

            // grab  name, and capacity
				    $name = isset($_POST['venueName']) ? sanitizeString($_POST['venueName']) : '';
				    $cap = isset($_POST['venueCap']) ? sanitizeString($_POST['venueCap']) : '';

            // validate name, making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if($name == "" || (!alphabeticSpace($name) || strlen($name) > 30)) {
              $errorText = $errorText.'You must enter a valid name.<br />';
              $errorMsg = true;
            }

            // validate cap, making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if($cap == "" || (!numbers($cap) && !integer($cap) || strlen($cap) > 7)) {
              $errorText = $errorText.'You must enter a valid capacity.<br />';
              $errorMsg = true;
            }

                        // Display error
				    if ($errorMsg) {
					    echo '<div id="error">'.$errorText.'</div>';
            } else {
              $db = new DB();

              $db->insertVenue($name, $cap);
            }

          } //addVenueSubmit

          if(isset($_POST['editVenueSubmit'])) {
            //Init error variables
				    $errorMsg = false;
				    $errorText = "<strong>ERRORS:</strong><br />";

            //grab lowest possible ID and highest possible ID
            $db = new DB();
            $data = $db->getAllVenues();
            $maxVenue = max($data);
            $maxID = $maxVenue['idvenue'];

            $lowVenue = min($data);
            $lowID = $lowVenue['idvenue'];

            // grab form
				    $id = isset($_POST['venueID']) ? sanitizeString($_POST['venueID']) : '';
            $editing = isset($_POST['editVenue']) ? sanitizeString($_POST['editVenue']) : '';
            $changing='';
            if ($editing == 'name') {
              $editname = isset($_POST['venueName']) ? sanitizeString($_POST['venueName']) : '';
            // validate name, making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if($editname == "" || (!alphaNumericSpace($editname) || strlen($editname) > 30)) {
              $errorText = $errorText.'You must enter a new valid name.<br />';
              $errorMsg = true;
            } else {
              $changing = $editname;
            }
            }

            if ($editing == 'cap') {
              $editCap = isset($_POST['venueCap']) ? sanitizeString($_POST['venueCap']) : '';
            // validate capacity , making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if($editCap == "" || (!numbers($editCap) && !integer($editCap) || strlen($editCap) > 7)) {
              $errorText = $errorText.'You must enter a new valid capacity.<br />';
              $errorMsg = true;
            } else {
              $changing = $editCap;
            }
            }

            // validate venue ID, making sure it isn't empty. If it is, assign error text and make errorMsg true
            if ($id == "" || $id < $lowID || $id > $maxID || (!numbers($id) && !integer($id))) {
              $errorText = $errorText.'You entered invalid ID.<br />';
              $errorMsg = true;
            }

            // Display error
				    if ($errorMsg) {
					    echo '<div id="error">'.$errorText.'</div>';
            } else {
              $db->updateVenue(array('id'=>$id,'editing'=>$editing,'changing'=>$changing));
              echo "Venue Updated";
            }
          } //editVenueSubmit

          // if the delete venue submit button is pressed, delete the venue
          if(isset($_POST['dltVenueSubmit'])) {

            //Init error variables
				    $errorMsg = false;
				    $errorText = "<strong>ERRORS:</strong><br />";

       			// grab  user id
				    $id = isset($_POST['venueID']) ? sanitizeString($_POST['venueID']) : '';

            //grab lowest possible ID and highest possible ID
            $db = new DB();
            $data = $db->getAllVenues();
            $maxVenue = max($data);
            $maxID = $maxVenue['idvenue'];

            $lowVenue = min($data);
            $lowID = $lowVenue['idvenue'];

            // validate venue ID, making sure it isn't empty. If it is, assign error text and make errorMsg true
            if ($id == "" || $id < $lowID || $id > $maxID || (!numbers($id) && !integer($id))) {
              $errorText = $errorText.'You entered invalid ID.<br />';
              $errorMsg = true;
            }

            // Display error
				    if ($errorMsg) {
					    echo '<div id="error">'.$errorText.'</div>';
            } else {
              $errors=array();
              $errors = $db->deleteVenue($id);

              if ($errors[0]) {
                echo '<div id="error">'.$errors[1].'</div>';
              } else{
                echo "Venue Deleted";
              }
            }
          } //dltVenueSubmit

          // if the add event submit button is pressed, create the event
          if(isset($_POST['addEventSubmit'])) {
            //Init error variables
				    $errorMsg = false;
				    $errorText = "<strong>ERRORS:</strong><br />";

            //grab lowest possible ID for Venues and highest possible ID
            $db = new DB();
            $data = $db->getAllVenues();
            $maxVenue = max($data);
            $maxID = $maxVenue['idvenue'];

            $lowVenue = min($data);
            $lowID = $lowVenue['idvenue'];

            //grab lowest possible ID for Events and highest possible ID
            $data = $db->getAllEvents();
            $maxVenue = max($data);
            $EventMaxID = $maxVenue['idevent'];

            $lowVenue = min($data);
            $EventLowID = $lowVenue['idevent'];

       			// grab  name, password, and role
				    $name = isset($_POST['eventName']) ? sanitizeString($_POST['eventName']) : '';
				    $eventStartDate = isset($_POST['eventStartDate']) ? sanitizeString($_POST['eventStartDate']) : '';
				    $eventEndDate = isset($_POST['eventEndDate']) ? sanitizeString($_POST['eventEndDate']) : '';
            $numAllowed = isset($_POST['numAllowed']) ? sanitizeString($_POST['numAllowed']) : '';
            $venueID = isset($_POST['venueID']) ? sanitizeString($_POST['venueID']) : '';

            // validate name, making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if($name == "" || (!alphabeticSpace($name) || strlen($name) > 25)) {
              $errorText = $errorText.'You must enter a valid name.<br />';
              $errorMsg = true;
            }

            // validate capacity , making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if($numAllowed == "" || (!numbers($numAllowed) && !integer($numAllowed) || strlen($numAllowed) > 7)) {
              $errorText = $errorText.'You must enter a valid capacity.<br />';
              $errorMsg = true;
            }

            // validate Venue ID, making sure it isn't empty. If it is, assign error text and make errorMsg true
            if ($venueID == "" || $venueID < $lowID || $venueID > $maxID || (!numbers($venueID) && !integer($venueID))) {
              $errorText = $errorText.'You entered invalid venue ID.<br />';
              $errorMsg = true;
            }
  
            // validate start date, making sure it isn't empty. If it is, assign error text and make errorMsg true
            if ($eventStartDate == "") {
              $errorText = $errorText.'You entered invalid start date.<br />';
              $errorMsg = true;
            }

            // validate end date, making sure it isn't empty. If it is, assign error text and make errorMsg true
            if ($eventEndDate == "") {
              $errorText = $errorText.'You entered invalid end date.<br />';
              $errorMsg = true;
            }

            // Display error
				    if ($errorMsg) {
					    echo '<div id="error">'.$errorText.'</div>';
            } else {
              if ($_SESSION['role'] == 2) {
                $db->insertManagerEvent($EventMaxID+1, $_SESSION['myID']);
              }
              $db->insertEvent($name, $eventStartDate, $eventEndDate, $numAllowed, $venueID);
            }

          } //addEventSubmit

          if(isset($_POST['editEventSubmit'])) {
            //Init error variables
				    $errorMsg = false;
				    $errorText = "<strong>ERRORS:</strong><br />";

            //grab lowest possible ID and highest possible ID
            $db = new DB();
            $data = $db->getAllVenues();
            $maxVenue = max($data);
            $VenueMaxID = $maxVenue['idvenue'];

            $lowVenue = min($data);
            $VenueLowID = $lowVenue['idvenue'];

            //grab lowest possible ID for Events and highest possible ID
            $data = $db->getAllEvents();
            $maxVenue = max($data);
            $EventMaxID = $maxVenue['idevent'];

            $lowVenue = min($data);
            $EventLowID = $lowVenue['idevent'];

            // grab form
				    $id = isset($_POST['eventID']) ? sanitizeString($_POST['eventID']) : '';
            $editing = isset($_POST['editEvent']) ? sanitizeString($_POST['editEvent']) : '';
            $changing='';
            if ($editing == 'name') {
              $editname = isset($_POST['eventName']) ? sanitizeString($_POST['eventName']) : '';
            // validate name, making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if($editname == "" || (!alphaNumericSpace($editname) || strlen($editname) > 30)) {
              $errorText = $errorText.'You must enter a new valid name.<br />';
              $errorMsg = true;
            } else {
              $changing = $editname;
            }
            }

            if ($editing == 'startdate') {
              $editStartDate = isset($_POST['eventStartDate']) ? sanitizeString($_POST['eventStartDate']) : '';
            // validate start date , making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if($editStartDate == "") {
              $errorText = $errorText.'You must enter a new valid start date.<br />';
              $errorMsg = true;
            } else {
              $changing = $editStartDate;
            }
            }

            if ($editing == 'enddate') {
              $editEndDate = isset($_POST['eventEndDate']) ? sanitizeString($_POST['eventEndDate']) : '';
            // validate start date , making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if($editEndDate == "") {
              $errorText = $errorText.'You must enter a new valid start date.<br />';
              $errorMsg = true;
            } else {
              $changing = $editEndDate;
            }
            }

            if ($editing == 'numallowed') {
              $editNumAllowed = isset($_POST['eventNumAllowed']) ? sanitizeString($_POST['eventNumAllowed']) : '';
            // validate number allowed , making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if($editNumAllowed == "" || (!numbers($editNumAllowed) && !integer($editNumAllowed) || strlen($editNumAllowed) > 7)) {
              $errorText = $errorText.'You must enter a new valid number allowed.<br />';
              $errorMsg = true;
            } else {
              $changing = $editNumAllowed;
            }
            }

            if ($editing == 'venueID') {
              $editVenueID = isset($_POST['eventVenueID']) ? sanitizeString($_POST['eventVenueID']) : '';
            // validate number allowed , making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if ($editVenueID == "" || $editVenueID < $VenueLowID || $editVenueID > $VenueMaxID || (!numbers($editVenueID) && !integer($editVenueID))) {
              $errorText = $errorText.'You entered invalid Venue ID.<br />';
              $errorMsg = true;
            } else {
              $changing = $editVenueID;
            }
            }

            // validate event ID, making sure it isn't empty. If it is, assign error text and make errorMsg true
            if ($id == "" || $id < $EventLowID || $id > $EventMaxID || (!numbers($id) && !integer($id))) {
              $errorText = $errorText.'You entered invalid Event ID.<br />';
              $errorMsg = true;
            }

            //if Manager role, validate that the event ID is one that the manager manages
            if ($_SESSION['role'] == 2) {
              $found = false;
              $ManID = $_SESSION['myID'];
              $data = $db->getAllManagerEvents();
              foreach ($data as $arr) {
                $output[$arr['eventID']] = $arr['managerID'];
                foreach ($output as $k=>$v) {
                  if ($k == $id && $v == $ManID) {
                    $found = true;
                  }
                }
              }
              if (!$found) {
                $errorText = $errorText."You entered an event ID you don't manage.<br />";
                $errorMsg = true;
              }
            }

            // Display error
				    if ($errorMsg) {
					    echo '<div id="error">'.$errorText.'</div>';
            } else {
              $db->updateEvent(array('id'=>$id,'editing'=>$editing,'changing'=>$changing));
              echo "Event Updated";
            }
          } //editEventSubmit

          if(isset($_POST['dltEventSubmit'])) {

            //Init error variables
				    $errorMsg = false;
				    $errorText = "<strong>ERRORS:</strong><br />";

       			// grab  user id
				    $id = isset($_POST['eventID']) ? sanitizeString($_POST['eventID']) : '';

            //grab lowest possible ID and highest possible ID
            $db = new DB();
            $data = $db->getAllEvents();
            $maxEvent = max($data);
            $maxID = $maxEvent['idevent'];

            $lowEvent = min($data);
            $lowID = $lowEvent['idevent'];

            // validate event ID, making sure it isn't empty. If it is, assign error text and make errorMsg true
            if ($id == "" || $id < $lowID || $id > $maxID || (!numbers($id) && !integer($id))) {
              $errorText = $errorText.'You entered invalid ID.<br />';
              $errorMsg = true;
            }

            //if Manager role, validate that the event ID is one that the manager manages
            if ($_SESSION['role'] == 2) {
              $found = false;
              $ManID = $_SESSION['myID'];
              $data = $db->getAllManagerEvents();
              foreach ($data as $arr) {
                $output[$arr['eventID']] = $arr['managerID'];
                foreach ($output as $k=>$v) {
                  if ($k == $id && $v == $ManID) {
                    $found = true;
                  }
                }
              }
              if (!$found) {
                $errorText = $errorText."You entered an event ID you don't manage.<br />";
                $errorMsg = true;
              } else {
                $db-> deleteManagerEvent($id, $ManID);
              }
            }

            // Display error
				    if ($errorMsg) {
					    echo '<div id="error">'.$errorText.'</div>';
            } else {
              $errors=array();
              $errors = $db->deleteEvent($id);

              if ($errors[0]) {
                echo '<div id="error">'.$errors[1].'</div>';
              } else{
                echo "Event Deleted";
              }
            }
          } //dltEventSubmit

          // if the add event submit button is pressed, create the event
          if(isset($_POST['addSessionSubmit'])) {
            //Init error variables
				    $errorMsg = false;
				    $errorText = "<strong>ERRORS:</strong><br />";

            //grab lowest possible ID for Venues and highest possible ID
            $db = new DB();
            $data = $db->getAllEvents();
            $maxEvent = max($data);
            $maxID = $maxEvent['idevent'];

            $lowEvent = min($data);
            $lowID = $lowEvent['idevent'];

       			// grab  name, password, and role
				    $name = isset($_POST['sessionName']) ? sanitizeString($_POST['sessionName']) : '';
            $numAllowed = isset($_POST['sessionsnumAllowed']) ? sanitizeString($_POST['sessionsnumAllowed']) : '';
            $eventID = isset($_POST['eventID']) ? sanitizeString($_POST['eventID']) : '';
				    $sessionStartDate = isset($_POST['sessionStartDate']) ? sanitizeString($_POST['sessionStartDate']) : '';
				    $sessionEndDate = isset($_POST['sessionEndDate']) ? sanitizeString($_POST['sessionEndDate']) : '';

            // validate name, making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if($name == "" || (!alphabeticSpace($name) || strlen($name) > 25)) {
              $errorText = $errorText.'You must enter a valid name.<br />';
              $errorMsg = true;
            }

            // validate capacity , making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if($numAllowed == "" || (!numbers($numAllowed) && !integer($numAllowed) || strlen($numAllowed) > 7)) {
              $errorText = $errorText.'You must enter a valid capacity.<br />';
              $errorMsg = true;
            }

            // validate Venue ID, making sure it isn't empty. If it is, assign error text and make errorMsg true
            if ($eventID == "" || $eventID < $lowID || $eventID > $maxID || (!numbers($eventID) && !integer($eventID))) {
              $errorText = $errorText.'You entered invalid event ID.<br />';
              $errorMsg = true;
            }
  
            // validate start date, making sure it isn't empty. If it is, assign error text and make errorMsg true
            if ($sessionStartDate == "") {
              $errorText = $errorText.'You entered invalid start date.<br />';
              $errorMsg = true;
            }

            // validate end date, making sure it isn't empty. If it is, assign error text and make errorMsg true
            if ($sessionEndDate == "") {
              $errorText = $errorText.'You entered invalid end date.<br />';
              $errorMsg = true;
            }

            //if Manager role, validate that the event ID is one that the manager manages
            if ($_SESSION['role'] == 2) {
              $found = false;
              $ManID = $_SESSION['myID'];
              $data = $db->getAllManagerEvents();
              foreach ($data as $arr) {
                $output[$arr['eventID']] = $arr['managerID'];
                foreach ($output as $k=>$v) {
                  if ($k == $eventID && $v == $ManID) {
                    $found = true;
                  }
                }
              }
              if (!$found) {
                $errorText = $errorText."You entered an event ID you don't manage.<br />";
                $errorMsg = true;
              }
            }

            // Display error
				    if ($errorMsg) {
					    echo '<div id="error">'.$errorText.'</div>';
            } else {
              $db->insertSession($name, $numAllowed, $eventID, $sessionStartDate, $sessionEndDate);
            }

          } //addSessionSubmit

          if(isset($_POST['editSessionSubmit'])) {
            //Init error variables
				    $errorMsg = false;
				    $errorText = "<strong>ERRORS:</strong><br />";

            //grab lowest possible ID and highest possible ID
            $db = new DB();
            $data = $db->getAllSessions();
            $maxSess = max($data);
            $SessionMaxID = $maxSess['id'];

            $lowSess = min($data);
            $SessionLowID = $lowSess['id'];

            //grab lowest possible ID for Events and highest possible ID
            $data = $db->getAllEvents();
            $maxEvent = max($data);
            $EventMaxID = $maxEvent['idevent'];

            $lowEvent = min($data);
            $EventLowID = $lowEvent['idevent'];

            // grab form
				    $id = isset($_POST['sessionID']) ? sanitizeString($_POST['sessionID']) : '';
            $editing = isset($_POST['editSession']) ? sanitizeString($_POST['editSession']) : '';
            $changing='';
            if ($editing == 'name') {
              $editname = isset($_POST['sessionName']) ? sanitizeString($_POST['sessionName']) : '';
            // validate name, making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if($editname == "" || (!alphaNumericSpace($editname) || strlen($editname) > 30)) {
              $errorText = $errorText.'You must enter a new valid name.<br />';
              $errorMsg = true;
            } else {
              $changing = $editname;
            }
            }

            if ($editing == 'numallowed') {
              $editNumAllowed = isset($_POST['sessionNumAllowed']) ? sanitizeString($_POST['sessionNumAllowed']) : '';
            // validate number allowed , making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if($editNumAllowed == "" || (!numbers($editNumAllowed) && !integer($editNumAllowed) || strlen($editNumAllowed) > 7)) {
              $errorText = $errorText.'You must enter a new valid number allowed.<br />';
              $errorMsg = true;
            } else {
              $changing = $editNumAllowed;
            }
            }

            if ($editing == 'eventID') {
              $editEventID = isset($_POST['sessionEventID']) ? sanitizeString($_POST['sessionEventID']) : '';
            // validate number allowed , making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if ($editEventID == "" || $editEventID < $EventLowID || $editEventID > $EventMaxID || (!numbers($editEventID) && !integer($editEventID))) {
              $errorText = $errorText.'You entered invalid Event ID.<br />';
              $errorMsg = true;
            } else {
              $changing = $editEventID;
            }
            //if a manager, then check if new eventID is managed by us
            if ($_SESSION['role'] ==2) {
              $found = false;
              $ManID = $_SESSION['myID'];
              $data = $db->getAllManagerEvents();
              foreach ($data as $arr) {
                $output[$arr['eventID']] = $arr['managerID'];
                foreach ($output as $k=>$v) {
                  if ($k == $editEventID && $v == $ManID) {
                    $found = true;
                  }
                }
              }
              if (!$found) {
                $errorText = $errorText."You entered a new event ID that you don't manage.<br />";
                $errorMsg = true;
              }
            }
            }

            if ($editing == 'startdate') {
              $editStartDate = isset($_POST['sessionStartDate']) ? sanitizeString($_POST['sessionStartDate']) : '';
            // validate start date , making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if($editStartDate == "") {
              $errorText = $errorText.'You must enter a new valid start date.<br />';
              $errorMsg = true;
            } else {
              $changing = $editStartDate;
            }
            }

            if ($editing == 'enddate') {
              $editEndDate = isset($_POST['sessionEndDate']) ? sanitizeString($_POST['sessionEndDate']) : '';
            // validate start date , making sure it isn't empty. If it is, assign error text and make errorMsg true
				    if($editEndDate == "") {
              $errorText = $errorText.'You must enter a new valid start date.<br />';
              $errorMsg = true;
            } else {
              $changing = $editEndDate;
            }
            }

            // validate session ID, making sure it isn't empty. If it is, assign error text and make errorMsg true
            if ($id == "" || $id < $SessionLowID || $id > $SessionMaxID || (!numbers($id) && !integer($id))) {
              $errorText = $errorText.'You entered invalid Session ID.<br />';
              $errorMsg = true;
            }

            //if Manager role, validate that the event ID is one that the manager manages
            if ($_SESSION['role'] == 2) {
              $found = false;
              $ManID = $_SESSION['myID'];
              //we have to get session data to be able to get event ID to check if the session we are editing
              //has an event ID we manage.
              $dSessions = $db->getAllSessions();
              foreach ($dSessions as $arr) {
                $output[$arr['id']] = $arr['event'];
                foreach ($output as $k=>$v) {
                  if ($k == $id) {
                    $seventID = $v;
                  }
                }
              }
              //now we check Manager_Events to see if that session's eventID is under our management.
              $data = $db->getAllManagerEvents();
              foreach ($data as $arr) {
                $output[$arr['eventID']] = $arr['managerID'];
                foreach ($output as $k=>$v) {
                  if ($k == $seventID && $v == $ManID) {
                    $found = true;
                  }
                }
              }
              if (!$found) {
                $errorText = $errorText."You entered a session ID that is part of an event you don't manage.<br />";
                $errorMsg = true;
              }
            }

            // Display error
				    if ($errorMsg) {
					    echo '<div id="error">'.$errorText.'</div>';
            } else {
              $db->updateSession(array('id'=>$id,'editing'=>$editing,'changing'=>$changing));
              echo "Session Updated";
            }
          } //editSessionSubmit

          if(isset($_POST['dltSessionSubmit'])) {

            //Init error variables
				    $errorMsg = false;
				    $errorText = "<strong>ERRORS:</strong><br />";

       			// grab  user id
				    $id = isset($_POST['sessionID']) ? sanitizeString($_POST['sessionID']) : '';

            //grab lowest possible ID and highest possible ID
            $db = new DB();
            $data = $db->getAllSessions();
            $maxSess = max($data);
            $SessionMaxID = $maxSess['id'];

            $lowSess = min($data);
            $SessionLowID = $lowSess['id'];

            // validate venue ID, making sure it isn't empty. If it is, assign error text and make errorMsg true
            if ($id == "" || $id < $SessionLowID || $id > $SessionMaxID || (!numbers($id) && !integer($id))) {
              $errorText = $errorText.'You entered invalid ID.<br />';
              $errorMsg = true;
            }

            //if Manager role, validate that the event ID is one that the manager manages
            if ($_SESSION['role'] == 2) {
              $found = false;
              $ManID = $_SESSION['myID'];

              //we have to get session data to be able to get event ID to check if the session we are editing
              //has an event ID we manage.
              $dSessions = $db->getAllSessions();
              foreach ($dSessions as $arr) {
                $output[$arr['id']] = $arr['event'];
                foreach ($output as $k=>$v) {
                  if ($k == $id) {
                    $seventID = $v;
                  }
                }
              }
              //now we check Manager_Events to see if that session's eventID is under our management.
              $data = $db->getAllManagerEvents();
              foreach ($data as $arr) {
                $output[$arr['eventID']] = $arr['managerID'];
                foreach ($output as $k=>$v) {
                  if ($k == $seventID && $v == $ManID) {
                    $found = true;
                  }
                }
              }
              if (!$found) {
                $errorText = $errorText."You entered a session ID that is part of an event you don't manage.<br />";
                $errorMsg = true;
              } else {
                $db->deleteAttendeeSession($id);
              }
            }

            // Display error
				    if ($errorMsg) {
					    echo '<div id="error">'.$errorText.'</div>';
            } else {
              $errors=array();
              $errors = $db->deleteSession($id);

              if ($errors[0]) {
                echo '<div id="error">'.$errors[1].'</div>';
              } else{
                echo "Session Deleted";
              }
            }
          } //dltSessionSubmit


            if(isset($_GET['page'])) { checkPage($_GET['page']); putPage($_GET['page']);}
            else {echo "<div class='navbar'>
                <a href='?page=signup'>Sign Up!</a> <a href='?page=login'>Login!</a>
                </div>";}

        ?>
        <!-- put a footer here -->
    </body>
</html>
<!---------------------SCRIPT--------------------->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type = "text/javascript">

    function viewUsers() {
        $.ajax( { type : 'POST',
          data : { action: 'viewUsers' },
          url  : 'viewTables.php',              // <=== CALL THE PHP FUNCTION HERE.
          success: function ( data ) {
            document.getElementById("userTable").innerHTML = data;               // <=== VALUE RETURNED FROM FUNCTION.
          },
          error: function ( xhr ) {
            alert( "error" );
          }
        });
    }   //viewUsers

    function viewVenue() {
        $.ajax( { type : 'POST',
          data : { action: 'viewVenue' },
          url  : 'viewTables.php',              // <=== CALL THE PHP FUNCTION HERE.
          success: function ( data ) {
            document.getElementById("venueTable").innerHTML = data;               // <=== VALUE RETURNED FROM FUNCTION.
          },
          error: function ( xhr ) {
            alert( "error" );
          }
        });
    } //viewVenue

    function viewEvent() {
        $.ajax( { type : 'POST',
          data : { action: 'viewEvent' },
          url  : 'viewTables.php',              // <=== CALL THE PHP FUNCTION HERE.
          success: function ( data ) {
            document.getElementById("eventTable").innerHTML = data;               // <=== VALUE RETURNED FROM FUNCTION.
          },
          error: function ( xhr ) {
            alert( "error" );
          }
        });
    } //viewEvent

    function viewSessions() {
        $.ajax( { type : 'POST',
          data : { action: 'viewSessions' },
          url  : 'viewTables.php',              // <=== CALL THE PHP FUNCTION HERE.
          success: function ( data ) {
            document.getElementById("sessionTable").innerHTML = data;               // <=== VALUE RETURNED FROM FUNCTION.
          },
          error: function ( xhr ) {
            alert( "error" );
          }
        });
    } //viewSesions


    function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.visibility == 'visible')
          e.style.visibility = 'hidden';
       else{
        e.style.visibility = 'visible';
       }
       
       if(e.style.display == 'block')
          e.style.display = 'none';
       else{
        e.style.display = 'block';
       }
   }

   function hide(id) {
    var e = document.getElementById(id);
       if(e.style.visibility == 'visible')
          e.style.visibility = 'hidden';
       
       if(e.style.display == 'block')
          e.style.display = 'none';
   }

   function getSelect(id) {

    if (id='editing') {
      document.getElementById(id).addEventListener('change', function handleChange(event) {
    
    if (event.target.value === 'name') {
      toggle_visibility('editname');
      hide('editpass');
      hide('editrole');
    } else if (event.target.value === 'pwd') {
      toggle_visibility('editpass');
      hide('editname');
      hide('editrole');
    }else if (event.target.value === 'role') {
      toggle_visibility('editrole');
      hide('editpass');
      hide('editname');
    }
  });
    }

    if (id="editVenue") {
      document.getElementById(id).addEventListener('change', function handleChange(event) {
        if (event.target.value === 'name') {
          toggle_visibility('editVenueName');
          hide('editcap');
        } else if (event.target.value === 'cap') {
          toggle_visibility('editcap');
          hide('editVenueName');
        }
    });
    }

    if (id="editEvent") {
      document.getElementById(id).addEventListener('change', function handleChange(event) {
        if (event.target.value === 'name') {
          toggle_visibility('editEventName');
          hide('editEventStartDate');
          hide('editEventEndDate');
          hide('editnumallowed');
          hide('editVenueID');
        } else if (event.target.value === 'startdate') {
          toggle_visibility('editEventStartDate');
          hide('editEventName');
          hide('editEventEndDate');
          hide('editnumallowed');
          hide('editVenueID');
        } else if (event.target.value === 'enddate') {
          toggle_visibility('editEventEndDate');
          hide('editEventName');
          hide('editEventStartDate');
          hide('editnumallowed');
          hide('editVenueID');
        }
        else if (event.target.value === 'numallowed') {
          toggle_visibility('editnumallowed');
          hide('editEventStartDate');
          hide('editEventEndDate');
          hide('editEventName');
          hide('editVenueID');
        }
        else if (event.target.value === 'venueID') {
          toggle_visibility('editVenueID');
          hide('editEventStartDate');
          hide('editEventEndDate');
          hide('editnumallowed');
          hide('editEventName');
        }
    });
    }

    if (id="editSession") {
      document.getElementById(id).addEventListener('change', function handleChange(event) {
        if (event.target.value === 'name') {
          toggle_visibility('editSessionName');
          hide('editSessionStartDate');
          hide('editSessionEndDate');
          hide('editSnumallowed');
          hide('editEventID');
        } else if (event.target.value === 'numallowed') {
          toggle_visibility('editSnumallowed');
          hide('editSessionStartDate');
          hide('editSessionEndDate');
          hide('editSessionName');
          hide('editEventID');
        } else if (event.target.value === 'eventID') {
          toggle_visibility('editEventID');
          hide('editSessionStartDate');
          hide('editSessionEndDate');
          hide('editSnumallowed');
          hide('editSessionName');
        } else if (event.target.value === 'startdate') {
          toggle_visibility('editSessionStartDate');
          hide('editSessionName');
          hide('editSessionEndDate');
          hide('editSnumallowed');
          hide('editEventID');
        } else if (event.target.value === 'enddate') {
          toggle_visibility('editSessionEndDate');
          hide('editSessionStartDate');
          hide('editSessionName');
          hide('editSnumallowed');
          hide('editEventID');
        }
    });
    }
  }


</script>