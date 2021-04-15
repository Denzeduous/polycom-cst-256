<?php
namespace App\Service;

use App\Model\User;
use App\Model\JobExperience;
use App\Service\Generic\DBConnector;

use Carbon\Exceptions\Exception;
use Illuminate\Support\Facades\Log;

use DateTime;
use App\Model\Group;
use App\Model\GroupUser;

class UserDAO {

    /**
     * Gets all users in an array. This is meant for listing.
     * @return null|\App\Model\User[] An array of users found, or null if none were found or if there was an error.
     */
    public static function AllUsers ()
    {
        $conn = DBConnector::GetConnection();

        if ($conn->connect_error) {
            Log::error ("Connection failed: " . $conn->connect_error);
            return null;
        }

        try {
            $query = "SELECT username, first_name, last_name, email, is_admin, is_business FROM user";
            $result = $conn->query($query);

            $user = array();

            while ($row = $result->fetch_assoc()) {
            	$first_name  = $row['first_name' ];
            	$last_name   = $row['last_name'  ];
                $username    = $row['username'   ];
                $email       = $row['email'      ];
                $is_admin    = $row['is_admin'   ];
                $is_business = $row['is_business'];

                $user[] = User::ForListing ($username, $first_name, $last_name, $email, $is_admin, $is_business);
            }

            mysqli_free_result($result);

            DBConnector::CloseConnection($conn);

            return $user;
        }
        
        catch (Exception $e) {
            Log::error ($e->getMessage());
            return null;
        }
    }
    
    /**
     * @param string $username user's username.
     * @param int $end_date The amount of days to suspend for.
     * @return bool Whether the suspension was successful.
     */
    public static function SuspendUser (string $username, int $end_date): bool {
    	if (!UserDAO::UserExists ($username)) return false;
    	
    	$conn = DBConnector::GetConnection ();
    	
    	if ($conn->connect_error) {
    		Log::error ("Connection failed: " . $conn->connect_error);
    		return false;
    	}
    	
    	try {
    		$query = "INSERT INTO suspension (user_id, end_date) VALUES ((SELECT user_id FROM user WHERE username=?), date_add(now(),interval ? day))";
    		$stmt = $conn->prepare($query);

    		$stmt->bind_param('si', $username, $end_date);

    		$success = $stmt->execute();
    		
    		$stmt->close();
    		
    		DBConnector::CloseConnection($conn);
    		
    		return $success;
    	}
    	
    	catch (Exception $e) {
    		Log::error ($e->getMessage());
    		return false;
    	}
    }
    
    /**
     * Gets all users in an array. This is meant for listing.
     * @return null|\App\Model\User[] An array of users found, or null if none were found or if there was an error.
     */
    public static function SearchUsers (string $username)
    {
    	$conn = DBConnector::GetConnection();
    	
    	if ($conn->connect_error) {
    		Log::error ("Connection failed: " . $conn->connect_error);
    		return null;
    	}
    	
    	try {
    		$query = "SELECT username, first_name, last_name, is_admin, is_business FROM user WHERE first_name LIKE CONCAT('%',?,'%') OR last_name LIKE CONCAT('%',?,'%') OR username LIKE CONCAT('%',?,'%') OR bio LIKE CONCAT('%',?,'%') OR contact LIKE CONCAT('%',?,'%') OR skills LIKE CONCAT('%',?,'%') OR education LIKE CONCAT('%',?,'%')";
    		$stmt = $conn->prepare($query);
    		$stmt->bind_param('sssssss', $username, $username, $username, $username, $username, $username, $username);
    		
    		$stmt->execute();
    		$result = $stmt->get_result();
    		
    		$users = array();
    		
    		while ($row = $result->fetch_assoc()) {
    			$first_name  = $row['first_name' ];
    			$last_name   = $row['last_name'  ];
    			$username    = $row['username'   ];
    			$is_admin    = $row['is_admin'   ];
    			$is_business = $row['is_business'];
    			
    			$user = User::ForListing ($first_name, $last_name, $username, $is_admin, $is_business);
    			
    			Log::info('first_name: ' . $user->GetFirstName());
    			Log::info('last_name: ' . $user->GetLastName());
    			Log::info('username: ' . $user->GetUsername());
    			Log::info('is_admin: ' . ($user->IsAdmin() ? 'true' : 'false'));
    			Log::info('is_business: ' . ($user->IsBusiness() ? 'true' : 'false'));
    			
    			$users[] = $user;
    		}
    		
    		mysqli_free_result($result);

    		$stmt->close();
    		DBConnector::CloseConnection($conn);
    		
    		return $users;
    	}
    	
    	catch (Exception $e) {
    		Log::error ($e->getMessage());
    		return null;
    	}
    }
    
    /**
     * Gets all jobs from a query.
     * @param string $query The query to search for.
     * @return array An array of jobs found.
     */
    public static function SearchJobs (string $job): array {
    	$conn = DBConnector::GetConnection();
    	
    	if ($conn->connect_error) {
    		Log::error ("Connection failed: " . $conn->connect_error);
    		return array();
    	}
    	
    	try {
    		$query = "SELECT experience_id, title, last_name AS company_name FROM jobexperience NATURAL JOIN user WHERE user.is_business AND (title LIKE CONCAT('%',?,'%') OR responsibilities LIKE CONCAT('%',?,'%') OR projects LIKE CONCAT('%',?,'%'))";
    		$stmt = $conn->prepare($query);
    		$stmt->bind_param('sss', $job, $job, $job);
    		
    		Log::info('query: ' . $job);
    		
    		$stmt->execute();
    		$result = $stmt->get_result();
    		
    		$jobs = array();
    		
    		while ($row = $result->fetch_assoc()) {
    			$experience_id = $row['experience_id'];
    			$title         = $row['title'        ];
    			$company       = $row['company_name' ];
    			
    			Log::info('experience_id: ' . $experience_id);
    			Log::info('title: ' . $title);
    			Log::info('company: ' . $company);
    			
    			$jobs[] = new JobExperience($experience_id, $title, $company, new DateTime(), new Datetime(), true, '', '');
    		}
    		
    		mysqli_free_result($result);
    		
    		$stmt->close();
    		DBConnector::CloseConnection($conn);
    		
    		return $jobs;
    	}
    	
    	catch (Exception $e) {
    		Log::error ($e->getMessage());
    		return array();
    	}
    }
    
    /**
     * Searches groups and returns an array of found groups.
     * @param string $group_name The name to search for (can be a section of a name).
     * @return array An array of groups.
     */
    public static function SearchGroups (string $group_name): array {
    	$conn = DBConnector::GetConnection();
    	
    	if ($conn->connect_error) {
    		Log::error ("Connection failed: " . $conn->connect_error);
    		return array();
    	}
    	
    	try {
    		$query = "SELECT affinitygroup.group_id, affinitygroup.group_name, affinitygroup.owner_id, COUNT(groupmember.user_id) + 1 AS member_count FROM affinitygroup LEFT OUTER JOIN groupmember ON affinitygroup.group_id = groupmember.group_id WHERE affinitygroup.group_name LIKE CONCAT('%',?,'%') GROUP BY affinitygroup.group_id";
    		$stmt = $conn->prepare($query);
    		$stmt->bind_param('s', $group_name);
    		
    		$stmt->execute();
    		$result = $stmt->get_result();
    		
    		$groups = array();
    		
    		while ($row = $result->fetch_assoc()) {
    			$group_id     = $row['group_id'    ];
    			$owner_id     = $row['owner_id'    ];
    			$group_name   = $row['group_name'  ];
    			$member_count = $row['member_count'];
    			
    			$groups[] = new Group($group_id, $group_name, UserDAO::FromID($owner_id), $member_count);
    		}
    		
    		mysqli_free_result($result);
    		
    		$stmt->close();
    		DBConnector::CloseConnection($conn);
    		
    		return $groups;
    	}
    	
    	catch (Exception $e) {
    		Log::error ($e->getMessage());
    		return array();
    	}
    }

    /**
     * Gets a user from their ID.
     * @param int $id user's ID.
     * @return null|\App\Model\User The user found, or null if none was found.
     */
    public static function FromID (int $id) {
        $conn = DBConnector::GetConnection();

        if ($conn->connect_error) {
            Log::error ("Connection failed: " . $conn->connect_error);
            return null;
        }

        try {
            $query = "SELECT first_name, last_name, username, email, is_admin, is_business FROM user WHERE user_id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $id);

            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($first_name, $last_name, $username, $email, $is_admin, $is_business);
            $stmt->fetch();

            $user = User::ForHeader ($id, $first_name, $last_name, $username, $email, $is_admin, $is_business);

            $stmt->close();
            DBConnector::CloseConnection($conn);
            
            return $user;
        }
        
        catch (Exception $e) {
            Log::error ($e->getMessage());
            return null;
        }
    }
    
    /**
     * Gets a user from their username.
     * @param string $username user's username.
     * @return NULL|\App\Model\User The user found, or null if none was found.
     */
    public static function FromUsername (string $username) {
    	Log::info ('Getting user ' . $username . '.');
    	
    	$conn = DBConnector::GetConnection();
    	
    	if ($conn->connect_error) {
    		Log::error ("Connection failed: " . $conn->connect_error);
    		return null;
    	}
    	
    	try {
    		$query = "SELECT user_id, first_name, last_name, email, is_admin, is_business FROM user WHERE username=?";
    		$stmt = $conn->prepare($query);
    		$stmt->bind_param('s', $username);
    		
    		$stmt->execute();
    		$stmt->store_result();
    		$stmt->bind_result($user_id, $first_name, $last_name, $email, $is_admin, $is_business);
    		$stmt->fetch();
    		
    		$user = User::ForHeader ($user_id, $first_name, $last_name, $username, $email, $is_admin, $is_business);

    		$stmt->close();
    		DBConnector::CloseConnection($conn);
    		
    		return $user;
    	}
    	
    	catch (Exception $e) {
    		Log::error ($e->getMessage());
    		return null;
    	}
    }
    
    /**
     * Gets a user from their username for profile viewing.
     * @param string $username user's username.
     * @return NULL|\App\Model\User The user found, or null if none was found.
     */
    public static function FromUsernameForProfile (string $username) {
    	$conn = DBConnector::GetConnection();
    	
    	if ($conn->connect_error) {
    		Log::error ("Connection failed: " . $conn->connect_error);
    		return null;
    	}
    	
    	try {
    		$query = "SELECT user_id, first_name, last_name, email, bio, contact, skills, education, date_joined, is_admin, is_business FROM user WHERE username=?";
    		$stmt = $conn->prepare($query);
    		$stmt->bind_param('s', $username);
    		
    		$stmt->execute();
    		$stmt->store_result();
    		$stmt->bind_result($user_id, $first_name, $last_name, $email, $bio, $contact, $skills, $education, $date_joined, $is_admin, $is_business);
    		$stmt->fetch();
    		
    		// Make sure none of these are null.
    		$bio       = ($bio       === null) ? '' : $bio;
    		$contact   = ($contact   === null) ? '' : $contact;
    		$skills    = ($skills    === null) ? '' : $skills;
    		$education = ($education === null) ? '' : $education;

    		// Generate the correct DateTime from the string.
			$date_joined = DateTime::createFromFormat ('Y-m-d H:i:s', $date_joined);

			$stmt->close();
			
			$query = "SELECT experience_id, title, company, start_date, end_date, is_current, responsibilities, projects FROM jobexperience WHERE user_id=? ORDER BY start_date DESC";
			$stmt  = $conn->prepare($query);
			$stmt->bind_param('i', $user_id);
			
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($id, $title, $company, $start_date, $end_date, $is_current, $responsibilities, $projects);
			
			$experience = array ();
			
			while ($stmt->fetch()) {
				$job = new JobExperience(
					$id,
					$title,
					$company,
					DateTime::createFromFormat ('Y-m-d', $start_date),
					
					$end_date !== null ? DateTime::createFromFormat ('Y-m-d', $start_date) : new DateTime(),
					
					$is_current,
					$responsibilities,
					$projects,
				);

				array_push($experience, $job);
			}
    		
    		$user = User::ForProfile($user_id, $first_name, $last_name, $username, $email, $bio, $contact, $skills, $education, $experience, $date_joined, $is_admin, $is_business);
    		
    		$stmt->close();
    		DBConnector::CloseConnection($conn);
    		
    		return $user;
    	}
    	
    	catch (Exception $e) {
    		Log::error ($e->getMessage());
    		return null;
    	}
    }
    
    /**
     * Gets job experience from ID.
     * @param int $id The job's ID.
     * @return JobExperience The job found.
     */
    public static function GetJobExperience (int $id) {
    	$conn = DBConnector::GetConnection();
    	
    	if ($conn->connect_error) {
    		Log::error ("Connection failed: " . $conn->connect_error);
    		return null;
    	}
    	
    	try {
    		$query = "SELECT title, company, start_date, end_date, is_current, responsibilities, projects FROM jobexperience WHERE experience_id=?";
    		$stmt  = $conn->prepare($query);
    		$stmt->bind_param('i', $id);
    		
    		$stmt->execute();
    		$stmt->store_result();
    		$stmt->bind_result($title, $company, $start_date, $end_date, $is_current, $responsibilities, $projects);
    		
    		$job = null;
    		
    		if ($stmt->fetch()) {
    			$job = new JobExperience(
    					$id,
    					$title,
    					$company,
    					DateTime::createFromFormat ('Y-m-d', $start_date),
    					
    					$end_date !== null ? DateTime::createFromFormat ('Y-m-d', $start_date) : new DateTime(),
    					
    					$is_current,
    					$responsibilities,
    					$projects,
    			);
    		}
    		
    		$stmt->close();
    		DBConnector::CloseConnection($conn);
    		
    		return $job;
    	}
    	
    	catch (Exception $e) {
    		Log::error ($e->getMessage());
    		return null;
    	}
    }
    
    /**
     * Adds job experience for the user.
     * @param JobExperience $job The job experience to be added.
     * @param int $user_id user's ID.
     * @return bool Whether the insertion was successful.
     */
    public static function AddJobExperience (JobExperience $job, int $user_id) {
    	$conn = DBConnector::GetConnection();
    	
    	if ($conn->connect_error) {
    		Log::error ("Connection failed: " . $conn->connect_error);
    		return false;
    	}
    	
    	try {
    		$query = "INSERT jobexperience (user_id, title, company, start_date, end_date, is_current, responsibilities, projects) VALUES (?,?,?,?,?,?,?,?)";
    		$stmt = $conn->prepare($query);
    		
    		$title            = $job->GetTitle();
    		$company          = $job->GetCompany();
    		$start_date       = $job->GetStartDate()->format('Y-m-d');
    		$end_date         = $job->GetEndDate() !== null ? $job->GetEndDate()->format('Y-m-d') : '';
    		$is_current       = $job->IsCurrent();
    		$responsibilities = $job->GetResponsibilities();
    		$projects         = $job->GetProjects();
    		
    		Log::info ('User ID: ' . $user_id);
    		Log::info ('Title: ' . $title);
    		Log::info ('Company: ' . $company);
    		Log::info ('Start Date: ' . $start_date);
    		Log::info ('End Date: ' . $end_date);
    		Log::info ('Is Current: ' . $is_current === true ? 'true' : 'false');
    		Log::info ('Responsibilities: ' . $responsibilities);
    		Log::info ('Projects: ' . $projects);
    		
    		$stmt->bind_param('issssiss', $user_id, $title, $company, $start_date, $end_date, $is_current, $responsibilities, $projects);
    		
    		$success = $stmt->execute();

    		$stmt->close();
    		DBConnector::CloseConnection($conn);
    		
    		return $success;
    	}
    	
    	catch (Exception $e) {
    		Log::error ($e->getMessage());
    		return false;
    	}
    }
    
    /**
     * Updates specified job experience based on the ID.
     * @param JobExperience $job The updated job experience.
     * @return boolean Whether or not the update was successful.
     */
    public static function EditJobExperience (JobExperience $job) {
    	Log::info ('Updating Job Experience ID ' . $job->GetID() . '.');
    	
    	// Pull out the necessary variables.
    	$id               = $job->GetID();
    	$title            = $job->GetTitle();
    	$company          = $job->GetCompany();
    	$responsibilities = $job->GetResponsibilities();
    	$projects         = $job->GetProjects();
    	
    	Log::info ('id: ' . $id);
    	Log::info ('title: ' . $title);
    	Log::info ('company: ' . $company);
    	Log::info ('responsibilities: ' . $responsibilities);
    	Log::info ('projects: ' . $projects);
    	
    	$conn = DBConnector::GetConnection();
    	
    	if ($conn->connect_error) {
    		Log::error ("Connection failed: " . $conn->connect_error);
    		return False;
    	}
    	
    	try {
    		$query = "UPDATE jobexperience SET title=?, company=?, responsibilities=?, projects=? WHERE experience_id=?";
    		$stmt = $conn->prepare($query);
    		$stmt->bind_param('ssssi', $title, $company, $responsibilities, $projects, $id);
    		
    		$success = $stmt->execute();
    		$stmt->close();
    		
    		DBConnector::CloseConnection($conn);
    		return $success;
    	}
    	
    	catch (Exception $e) {
    		Log::error ($e->getMessage());
    		return False;
    	}
    }
    
    /**
     * Creates an affinity group with the given owner_id and name.
     * @param int $owner_id The user_id of the owner.
     * @param string $name The name of the group.
     * @return int The affinity group's ID.
     */
    public static function CreateUserGroup (int $owner_id, string $name): int {
    	$conn = DBConnector::GetConnection();
    	
    	if ($conn->connect_error) {
    		Log::error ("Connection failed: " . $conn->connect_error);
    		return 0;
    	}
    	
    	try {
    		$query = "INSERT INTO affinitygroup (owner_id, group_name) VALUES (?, ?)";
    		$stmt = $conn->prepare($query);
    		$stmt->bind_param('is', $owner_id, $name);
    		
    		$stmt->execute();
    		
    		$stmt->close();
    		
    		$id = $conn->insert_id;
    		
    		DBConnector::CloseConnection($conn);
    		
    		return $id;
    	}
    	
    	catch (Exception $e) {
    		Log::error ($e->getMessage());
    		return 0;
    	}
    }
    
    /**
     * Adds a user to an affinity group.
     * @param int $group_id The group's ID.
     * @param int $user_id The user's ID.
     * @return bool Whether the insertion was successful.
     */
    public static function AddUserToGroup (int $group_id, int $user_id): bool {
    	$conn = DBConnector::GetConnection();
    	
    	if ($conn->connect_error) {
    		Log::error ("Connection failed: " . $conn->connect_error);
    		return false;
    	}
    	
    	try {
    		$query = "INSERT INTO groupmember (group_id, user_id) VALUES (?, ?)";
    		$stmt = $conn->prepare($query);
    		$stmt->bind_param('ii', $group_id, $user_id);
    		
    		$success = $stmt->execute();
    		
    		$stmt->close();
    		
    		DBConnector::CloseConnection($conn);
    		
    		return $success;
    	}
    	
    	catch (Exception $e) {
    		Log::error ($e->getMessage());
    		return false;
    	}
    }
    
    /**
     * Removes a user from a given group.
     * @param int $group_id The group's ID.
     * @param int $user_id The user's ID.
     * @return bool Whether the deletion was successful.
     */
    public static function RemoveUserFromGroup (int $group_id, int $user_id): bool {
    	$conn = DBConnector::GetConnection();
    	
    	if ($conn->connect_error) {
    		Log::error ("Connection failed: " . $conn->connect_error);
    		return false;
    	}
    	
    	try {
    		$query = "DELETE FROM groupmember WHERE group_id=? AND user_id=?";
    		$stmt = $conn->prepare($query);
    		$stmt->bind_param('ii', $group_id, $user_id);
    		
    		$success = $stmt->execute();
    		
    		$stmt->close();

    		DBConnector::CloseConnection($conn);
    		
    		return $success;
    	}
    	
    	catch (Exception $e) {
    		Log::error ($e->getMessage());
    		return false;
    	}
    }
    
    /**
     * Changes the owner of a group.
     * @param int $group_id The group's ID.
     * @param int $user_id The user's ID.
     * @return bool Whether the update was successful.
     */
    public static function ChangeGroupOwner (int $group_id, int $user_id): bool {
    	$conn = DBConnector::GetConnection();
    	
    	if ($conn->connect_error) {
    		Log::error ("Connection failed: " . $conn->connect_error);
    		return false;
    	}
    	
    	try {
    		$query = "UPDATE affinitygroup SET owner_id=? WHERE group_id=?";
    		$stmt = $conn->prepare($query);
    		$stmt->bind_param('ii', $user_id, $group_id);
    		
    		$success = $stmt->execute();
    		
    		$stmt->close();
    		
    		DBConnector::CloseConnection($conn);
    		
    		return $success;
    	}
    	
    	catch (Exception $e) {
    		Log::error ($e->getMessage());
    		return false;
    	}
    }
    
    /**
     * Gets groups for a user.
     * @param int $user_id The user's ID
     * @return array|null An array of the user's groups.
     */
    public static function GetGroupsForUser (int $user_id) {
    	$conn = DBConnector::GetConnection();
    	
    	if ($conn->connect_error) {
    		Log::error ("Connection failed: " . $conn->connect_error);
    		return null;
    	}
    	
    	try {
    		$query = "SELECT affinitygroup.group_id, affinitygroup.owner_id, affinitygroup.group_name FROM groupmember LEFT OUTER JOIN affinitygroup ON (affinitygroup.group_id) WHERE groupmember.user_id=? OR affinitygroup.owner_id=?";
    		$stmt = $conn->prepare($query);

    		$stmt->bind_param('ii', $user_id, $user_id);
    		
    		$stmt->execute();
    		$result = $stmt->get_result();
    		
    		$groups = array();
    		
    		Log::info ('user_id: ' . $user_id);
    		
    		while ($row = $result->fetch_assoc()) {
    			$group_id = $row['group_id'  ];
    			$owner_id = $row['owner_id'  ];
    			$name     = $row['group_name'];
    			
    			Log::info ('group_id: ' . $group_id);
    			Log::info ('name: ' . $name);
    			
    			$groups[] = new Group($group_id, $name, userDAO::FromID ($owner_id));
    		}
    		
    		Log::info ('count: ' . count($groups));
    		
    		mysqli_free_result($result);
    		
    		$stmt->close();
    		DBConnector::CloseConnection($conn);
    		
    		return $groups;
    	}
    	
    	catch (Exception $e) {
    		Log::error ($e->getMessage());
    		return null;
    	}
    }
    
    /**
     * Gets a group by its name.
     * @param string $name Name of the group.
     * @return Group|null The group found, or null if none were found.
     */
    public static function GetGroupByName (string $name) {
    	$conn = DBConnector::GetConnection();
    	
    	if ($conn->connect_error) {
    		Log::error ("Connection failed: " . $conn->connect_error);
    		return null;
    	}
    	
    	try {
    		$query = "SELECT * FROM affinitygroup WHERE group_name=?";
    		$stmt = $conn->prepare($query);
    		$stmt->bind_param('s', $name);
    		
    		$stmt->execute();
    		$result = $stmt->get_result();
    		
    		$group = null;
    		
    		if ($row = $result->fetch_assoc()) {
	    		$group_id = $row['group_id'  ];
	    		$owner_id = $row['owner_id'  ];
	    		$name     = $row['group_name'];
	    		
	    		$group = new Group($group_id, $name, userDAO::FromID ($owner_id));
    		}
    		
    		mysqli_free_result($result);
    		
    		$stmt->close();
    		DBConnector::CloseConnection($conn);
    		
    		return $group;
    	}
    	
    	catch (Exception $e) {
    		Log::error ($e->getMessage());
    		return null;
    	}
    }
    
    /**
     * Gets a GroupUser from a group.
     * @param int $group_id The group's ID.
     * @return array The group users found.
     */
    public static function GetUsersForGroup (int $group_id): array {
    	$conn = DBConnector::GetConnection();
    	
    	if ($conn->connect_error) {
    		Log::error ("Connection failed: " . $conn->connect_error);
    		return null;
    	}
    	
    	try {
    		$query = "SELECT * FROM groupmember WHERE group_id=?";
    		$stmt = $conn->prepare($query);
    		$stmt->bind_param('i', $group_id);
    		
    		$stmt->execute();
    		$result = $stmt->get_result();

    		$users = array();

    		while ($row = $result->fetch_assoc()) {
    			$user_id     = $row['user_id'  ];
    			$date_joined = $row['date_joined'];

    			$date_joined = DateTime::createFromFormat ('Y-m-d H:i:s', $date_joined);

    			$users[] = new GroupUser($user_id, $date_joined, userDAO::FromID ($user_id));
    		}
    		
    		mysqli_free_result($result);
    		
    		$stmt->close();
    		DBConnector::CloseConnection($conn);
    		
    		return $users;
    		
    		mysqli_free_result($result);
    		
    		$stmt->close();
    		DBConnector::CloseConnection($conn);
    		
    		return $users;
    	}
    	
    	catch (Exception $e) {
    		Log::error ($e->getMessage());
    		return null;
    	}
    }
    
    /**
     * Deletes a group by its ID.
     * @param int $id The group's ID.
     * @return bool Whether the deletion was successful.
     */
    public static function DeleteGroup (int $id): bool {
    	$conn = DBConnector::GetConnection();
    	
    	if ($conn->connect_error) {
    		Log::error ("Connection failed: " . $conn->connect_error);
    		return false;
    	}
    	
    	try {
    		$query = "DELETE FROM affinitygroup WHERE group_id=?";
    		$stmt = $conn->prepare($query);
    		$stmt->bind_param('i', $id);
    		
    		$success = $stmt->execute();
    		
    		$stmt->close();
    		
    		$query = "DELETE FROM groupmember WHERE group_id=?";
    		$stmt = $conn->prepare($query);
    		$stmt->bind_param('i', $id);
    		
    		$success = $success && $stmt->execute();

    		$stmt->close();
    		DBConnector::CloseConnection($conn);

    		return $success;
    	}
    	
    	catch (Exception $e) {
    		Log::error ($e->getMessage());
    		return false;
    	}
    }
    
    /**
     * Deletes a job experience from the ID.
     * @param int $id The ID to delete.
     * @return bool Whether the deletion was successful.
     */
    public static function DeleteJobExperienceFromID (int $id): bool {
    	$conn = DBConnector::GetConnection();
    	
    	if ($conn->connect_error) {
    		Log::error ("Connection failed: " . $conn->connect_error);
    		return null;
    	}
    	
    	try {
    		$query = "DELETE FROM jobexperience WHERE experience_id=?";
    		$stmt = $conn->prepare($query);
    		$stmt->bind_param('i', $id);
    		
    		$success = $stmt->execute();
    		
    		$stmt->close();
    		DBConnector::CloseConnection($conn);
    		
    		return $success;
    	}
    	
    	catch (Exception $e) {
    		Log::error ($e->getMessage());
    		return null;
    	}
    }

    /**
     * Deletes a user from their username.
     * @param string $username The user to delete.
     * @return boolean Whether the deletion was successful.
     */
    public static function DeleteFromUsername (string $username) {
        $conn = DBConnector::GetConnection();

        if ($conn->connect_error) {
            Log::error ("Connection failed: " . $conn->connect_error);
            return null;
        }

        try {
            $query = "DELETE FROM user WHERE username=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $username);

            $success = $stmt->execute();

            $stmt->close();
            DBConnector::CloseConnection($conn);

            return $success;
        }
        
        catch (Exception $e) {
            Log::error ($e->getMessage());
            return null;
        }
    }

    /**
     * Verifies a user, making sure their username and password are correct.
     * @param string $username The username to pull the password from.
     * @param string $password The password to check.
     * @return boolean Whether the verification was successful.
     */
    public static function VerifyUser (string $username, string $password) : bool {
        $conn = DBConnector::GetConnection();

        if ($conn->connect_error) {
            Log::error ("Connection failed: " . $conn->connect_error);
            return False;
        }

        try {
            $query = "SELECT password FROM user WHERE username=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('s', $username);

            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($stored_password);
            $stmt->fetch();

            $success = password_verify($password, $stored_password);

            $stmt->close();
            DBConnector::CloseConnection($conn);

            return $success;
        }
        
        catch (Exception $e) {
            Log::error ($e->getMessage());
            return False;
        }
    }
    
    public static function VerifyAdmin (string $username, string $password) {
    	$conn = DBConnector::GetConnection();
    	
    	if ($conn->connect_error) {
    		Log::error ("Connection failed: " . $conn->connect_error);
    		return False;
    	}
    	
    	try {
    		$query = "SELECT password, is_admin FROM user WHERE username=?";
    		$stmt = $conn->prepare($query);
    		$stmt->bind_param('s', $username);
    		
    		$stmt->execute();
    		$stmt->store_result();
    		$stmt->bind_result($stored_password, $is_admin);
    		$stmt->fetch();
    		
    		$success = password_verify($password, $stored_password) && $is_admin;
    		
    		$stmt->close();
    		DBConnector::CloseConnection($conn);
    		
    		return $success;
    	}
    	
    	catch (Exception $e) {
    		Log::error ($e->getMessage());
    		return False;
    	}
    }

    /**
     * Checks to see if a user with a given username exists.
     * @param string $username username to check.
     * @return boolean Whether the user exists.
     */
    public static function UserExists (string $username): bool {
        $conn = DBConnector::GetConnection();

        if ($conn->connect_error) {
            Log::error ("Connection failed: " . $conn->connect_error);
            return False;
        }

        try {
            $query = "SELECT COUNT(1) FROM user WHERE username=?";
            $stmt = $conn->prepare($query);

            $stmt->bind_param('s', $username);

            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($user_exists);
            $stmt->fetch();
            $stmt->close();

            DBConnector::CloseConnection($conn);

            return $user_exists;
        }
        
        catch (Exception $e) {
            Log::error ($e->getMessage());
            return False;
        }
    }

    /**
     * Creates a user in the database.
     * @param string $username user's username.
     * @param string $password user's password.
     * @param string $email user's email.
     * @return boolean Whether the insertion was successful.
     */
    public static function CreateUser (User $user, string $password) {
		
    	// Pull out the necessary variables.
    	$first_name  = $user->GetFirstName();
		$last_name   = $user->GetLastName ();
    	$username    = $user->GetUsername ();
		$email       = $user->GetEmail    ();
		$is_business = $user->IsBusiness  ();
		
		// Hash the password for security.
		// 
		// We don't want to be passing around the password
		// through the application.
		$password = password_hash ($password, PASSWORD_DEFAULT);

    	$conn = DBConnector::GetConnection();

        if ($conn->connect_error) {
            Log::error ("Connection failed: " . $conn->connect_error);
            return false;
        }

        try {
            $query = "INSERT INTO user (first_name, last_name, username, password, email, is_business) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);

            $stmt->bind_param('sssssi', $first_name, $last_name, $username, $password, $email, $is_business);

            $success = $stmt->execute();

            $stmt->close();
            
            DBConnector::CloseConnection($conn);

            return $success;
        }
        
        catch (Exception $e) {
            Log::error ($e->getMessage());
            return false;
        }
    }
    
    /**
     * Updates a user's profile.
     * @param User $user user to update.
     * @return bool Whether the update was successful.
     */
    public static function UpdateUserProfile (User $user) : bool {
    	Log::info ('Updating user Profile for ' . $user->GetUsername() . '.');
    	
    	// Pull out the necessary variables.
    	$user_id   = $user->GetUserID    ();
    	$bio       = $user->GetBio       ();
    	$contact   = $user->GetContact   ();
    	$skills    = $user->GetSkills    ();
    	$education = $user->GetEducation ();
    	
    	Log::info ('user_id: ' . $user_id);
    	Log::info ('bio: ' . $bio);
    	Log::info ('contact: ' . $contact);
    	Log::info ('skills: ' . $skills);
    	
    	$conn = DBConnector::GetConnection();
    	
    	if ($conn->connect_error) {
    		Log::error ("Connection failed: " . $conn->connect_error);
    		return False;
    	}
    	
    	try {
    		$query = "UPDATE user SET bio=?, contact=?, skills=?, education=? WHERE user_id=?";
    		$stmt = $conn->prepare($query);
    		$stmt->bind_param('ssssi', $bio, $contact, $skills, $education, $user_id);
    		Log::info ('Got here in update!');
    		$success = $stmt->execute();
    		
    		$stmt->close();

    		DBConnector::CloseConnection($conn);

    		return $success;
    	}
    	
    	catch (Exception $e) {
    		Log::error ($e->getMessage());
    		return False;
    	}
    }
    
    /**
     * Updates a user's information.
     * @param User $user The user to update.
     * @return boolean Whether the update was successful.
     */
    public static function UpdateUser (User $user) {
    	
    	// Pull out the necessary variables.
    	$user_id    = $user->GetUserID   ();
    	$first_name = $user->GetFirstName();
    	$last_name  = $user->GetLastName ();
    	$username   = $user->GetUsername ();
    	$email      = $user->GetEmail    ();
    	
    	$bio       = $user->GetBio      ();
    	$contact   = $user->GetContact  ();
    	$skills    = $user->GetSkills   ();
    	$education = $user->GetEducation();

    	$conn = DBConnector::GetConnection();
    	
    	if ($conn->connect_error) {
    		Log::error ("Connection failed: " . $conn->connect_error);
    		return False;
    	}
    	
    	try {
 
    		// Check to make sure the username isn't taken.
    		// 
    		// This should be done in the controller as
    		// a validation step, but this is a failsafe.
    		if (UserDAO::UserExists ($username)) return false;
    		
    		$query = "UPDATE user SET first_name=?, last_name=?, username=?, email=?, bio=?, contact=?, skills=?, education=? WHERE user_id=?";
    		$stmt = $conn->prepare($query);
    		$stmt->bind_param('ssssssssi', $first_name, $last_name, $username, $email, $bio, $contact, $skills, $education, $user_id);
    		
    		$success = $stmt->execute();
    		
    		$stmt->close();
    		DBConnector::CloseConnection($conn);
    		
    		return $success;
    	}
    	
    	catch (Exception $e) {
    		Log::error ($e->getMessage());
    		return False;
    	}
    }
    
    public static function IsMember (int $user_id, int $group_id) {
    	$conn = DBConnector::GetConnection();
    	
    	if ($conn->connect_error) {
    		Log::error ("Connection failed: " . $conn->connect_error);
    		return False;
    	}
    	
    	try {
    		$query = "SELECT COUNT(1) FROM groupmember WHERE user_id=? AND group_id=?";
    		$stmt = $conn->prepare($query);
    		$stmt->bind_param('ii', $user_id, $group_id);
    		
    		$stmt->execute();
    		$stmt->store_result();
    		$stmt->bind_result($user_exists);
    		$stmt->fetch();
    		$stmt->close();
    		
    		DBConnector::CloseConnection($conn);
    		
    		return $user_exists;
    	}
    	
    	catch (Exception $e) {
    		Log::error ($e->getMessage());
    		return False;
    	}
    }
}

