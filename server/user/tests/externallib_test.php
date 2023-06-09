<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * User external PHPunit tests
 *
 * @package    core_user
 * @category   external
 * @copyright  2012 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.4
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/webservice/tests/helpers.php');
require_once($CFG->dirroot . '/user/externallib.php');
require_once($CFG->dirroot . '/files/externallib.php');

class core_user_externallib_testcase extends externallib_advanced_testcase {

    /**
     * Test get_users
     */
    public function test_get_users() {
        global $USER, $CFG;


        $course = self::getDataGenerator()->create_course();

        $user1 = array(
            'username' => 'usernametest1',
            'idnumber' => 'idnumbertest1',
            'firstname' => 'First Name User Test 1',
            'lastname' => 'Last Name User Test 1',
            'email' => 'usertest1@example.com',
            'address' => '2 Test Street Perth 6000 WA',
            'phone1' => '01010101010',
            'phone2' => '02020203',
            'skype' => 'testuser1',
            'department' => 'Department of user 1',
            'institution' => 'Institution of user 1',
            'description' => 'This is a description for user 1',
            'descriptionformat' => FORMAT_MOODLE,
            'city' => 'Perth',
            'url' => 'http://moodle.org',
            'country' => 'AU'
            );

        $user1 = self::getDataGenerator()->create_user($user1);
        set_config('usetags', 1);
        require_once($CFG->dirroot . '/user/editlib.php');
        $user1->interests = array('Cinema', 'Tennis', 'Dance', 'Guitar', 'Cooking');
        useredit_update_interests($user1, $user1->interests);

        $user2 = self::getDataGenerator()->create_user(
                array('username' => 'usernametest2', 'idnumber' => 'idnumbertest2'));

        $generatedusers = array();
        $generatedusers[$user1->id] = $user1;
        $generatedusers[$user2->id] = $user2;

        $context = context_course::instance($course->id);
        $roleid = $this->assignUserCapability('moodle/user:viewdetails', $context->id);

        // Enrol the users in the course.
        $this->getDataGenerator()->enrol_user($user1->id, $course->id, $roleid);
        $this->getDataGenerator()->enrol_user($user2->id, $course->id, $roleid);
        $this->getDataGenerator()->enrol_user($USER->id, $course->id, $roleid);

        // call as admin and receive all possible fields.
        $this->setAdminUser();

        $searchparams = array(
            array('key' => 'invalidkey', 'value' => 'invalidkey'),
            array('key' => 'email', 'value' => $user1->email),
            array('key' => 'firstname', 'value' => $user1->firstname));

        // Call the external function.
        $result = core_user_external::get_users($searchparams);

        // We need to execute the return values cleaning process to simulate the web service server
        $result = external_api::clean_returnvalue(core_user_external::get_users_returns(), $result);

        // Check we retrieve the good total number of enrolled users + no error on capability.
        $expectedreturnedusers = 1;
        $returnedusers = $result['users'];
        $this->assertEquals($expectedreturnedusers, count($returnedusers));

        foreach($returnedusers as $returneduser) {
            $generateduser = ($returneduser['id'] == $USER->id) ?
                                $USER : $generatedusers[$returneduser['id']];
            $this->assertEquals($generateduser->username, $returneduser['username']);
            if (!empty($generateduser->idnumber)) {
                $this->assertEquals($generateduser->idnumber, $returneduser['idnumber']);
            }
            $this->assertEquals($generateduser->firstname, $returneduser['firstname']);
            $this->assertEquals($generateduser->lastname, $returneduser['lastname']);
            if ($generateduser->email != $USER->email) { // Don't check the tmp modified $USER email.
                $this->assertEquals($generateduser->email, $returneduser['email']);
            }
            if (!empty($generateduser->address)) {
                $this->assertEquals($generateduser->address, $returneduser['address']);
            }
            if (!empty($generateduser->phone1)) {
                $this->assertEquals($generateduser->phone1, $returneduser['phone1']);
            }
            if (!empty($generateduser->phone2)) {
                $this->assertEquals($generateduser->phone2, $returneduser['phone2']);
            }
            if (!empty($generateduser->skype)) {
                $this->assertEquals($generateduser->skype, $returneduser['skype']);
            }
            if (!empty($generateduser->department)) {
                $this->assertEquals($generateduser->department, $returneduser['department']);
            }
            if (!empty($generateduser->institution)) {
                $this->assertEquals($generateduser->institution, $returneduser['institution']);
            }
            if (!empty($generateduser->description)) {
                $this->assertEquals($generateduser->description, $returneduser['description']);
            }
            if (!empty($generateduser->descriptionformat)) {
                $this->assertEquals(FORMAT_HTML, $returneduser['descriptionformat']);
            }
            if (!empty($generateduser->city)) {
                $this->assertEquals($generateduser->city, $returneduser['city']);
            }
            if (!empty($generateduser->country)) {
                $this->assertEquals($generateduser->country, $returneduser['country']);
            }
            if (!empty($generateduser->url)) {
                $this->assertEquals($generateduser->url, $returneduser['url']);
            }
            if (!empty($CFG->usetags) and !empty($generateduser->interests)) {
                $this->assertEquals(implode(', ', $generateduser->interests), $returneduser['interests']);
            }
        }

        // Test the invalid key warning.
        $warnings = $result['warnings'];
        $this->assertEquals(count($warnings), 1);
        $warning = array_pop($warnings);
        $this->assertEquals($warning['item'], 'invalidkey');
        $this->assertEquals($warning['warningcode'], 'invalidfieldparameter');

        // Test sending twice the same search field.
        try {
            $searchparams = array(
            array('key' => 'firstname', 'value' => 'Canard'),
            array('key' => 'email', 'value' => $user1->email),
            array('key' => 'firstname', 'value' => $user1->firstname));

            // Call the external function.
            $result = core_user_external::get_users($searchparams);
            $this->fail('Expecting \'keyalreadyset\' moodle_exception to be thrown.');
        } catch (moodle_exception $e) {
            $this->assertEquals('keyalreadyset', $e->errorcode);
        }
    }

    /**
     * Test get_users_by_field
     */
    public function test_get_users_by_field() {
        global $USER, $CFG;


        $course = self::getDataGenerator()->create_course();
        $user1 = array(
            'username' => 'usernametest1',
            'idnumber' => 'idnumbertest1',
            'firstname' => 'First Name User Test 1',
            'lastname' => 'Last Name User Test 1',
            'email' => 'usertest1@example.com',
            'address' => '2 Test Street Perth 6000 WA',
            'phone1' => '01010101010',
            'phone2' => '02020203',
            'skype' => 'testuser1',
            'department' => 'Department of user 1',
            'institution' => 'Institution of user 1',
            'description' => 'This is a description for user 1',
            'descriptionformat' => FORMAT_MOODLE,
            'city' => 'Perth',
            'url' => 'http://moodle.org',
            'country' => 'AU',
            'lang' => 'kkl',
            'theme' => 'kkt',
        );
        $user1 = self::getDataGenerator()->create_user($user1);
        if (!empty($CFG->usetags)) {
            require_once($CFG->dirroot . '/user/editlib.php');
            $user1->interests = array('Cinema', 'Tennis', 'Dance', 'Guitar', 'Cooking');
            useredit_update_interests($user1, $user1->interests);
        }
        $user2 = self::getDataGenerator()->create_user(
                array('username' => 'usernametest2', 'idnumber' => 'idnumbertest2'));

        $generatedusers = array();
        $generatedusers[$user1->id] = $user1;
        $generatedusers[$user2->id] = $user2;

        $context = context_course::instance($course->id);
        $roleid = $this->assignUserCapability('moodle/user:viewdetails', $context->id);

        // Enrol the users in the course.
        $this->getDataGenerator()->enrol_user($user1->id, $course->id, $roleid, 'manual');
        $this->getDataGenerator()->enrol_user($user2->id, $course->id, $roleid, 'manual');
        $this->getDataGenerator()->enrol_user($USER->id, $course->id, $roleid, 'manual');

        // call as admin and receive all possible fields.
        $this->setAdminUser();

        $fieldstosearch = array('id', 'idnumber', 'username', 'email');

        foreach ($fieldstosearch as $fieldtosearch) {

            // Call the external function.
            $returnedusers = core_user_external::get_users_by_field($fieldtosearch,
                        array($USER->{$fieldtosearch}, $user1->{$fieldtosearch}, $user2->{$fieldtosearch}));
            $returnedusers = external_api::clean_returnvalue(core_user_external::get_users_by_field_returns(), $returnedusers);

            // Expected result differ following the searched field
            // Admin user in the PHPunit framework doesn't have an idnumber.
            if ($fieldtosearch == 'idnumber') {
                $expectedreturnedusers = 2;
            } else {
                $expectedreturnedusers = 3;
            }

            // Check we retrieve the good total number of enrolled users + no error on capability.
            $this->assertEquals($expectedreturnedusers, count($returnedusers));

            foreach($returnedusers as $returneduser) {
                $generateduser = ($returneduser['id'] == $USER->id) ?
                                    $USER : $generatedusers[$returneduser['id']];
                $this->assertEquals($generateduser->username, $returneduser['username']);
                if (!empty($generateduser->idnumber)) {
                    $this->assertEquals($generateduser->idnumber, $returneduser['idnumber']);
                }
                $this->assertEquals($generateduser->firstname, $returneduser['firstname']);
                $this->assertEquals($generateduser->lastname, $returneduser['lastname']);
                if ($generateduser->email != $USER->email) { //don't check the tmp modified $USER email
                    $this->assertEquals($generateduser->email, $returneduser['email']);
                }
                if (!empty($generateduser->address)) {
                    $this->assertEquals($generateduser->address, $returneduser['address']);
                }
                if (!empty($generateduser->phone1)) {
                    $this->assertEquals($generateduser->phone1, $returneduser['phone1']);
                }
                if (!empty($generateduser->phone2)) {
                    $this->assertEquals($generateduser->phone2, $returneduser['phone2']);
                }
                if (!empty($generateduser->skype)) {
                    $this->assertEquals($generateduser->skype, $returneduser['skype']);
                }
                if (!empty($generateduser->department)) {
                    $this->assertEquals($generateduser->department, $returneduser['department']);
                }
                if (!empty($generateduser->institution)) {
                    $this->assertEquals($generateduser->institution, $returneduser['institution']);
                }
                if (!empty($generateduser->description)) {
                    $this->assertEquals($generateduser->description, $returneduser['description']);
                }
                if (!empty($generateduser->descriptionformat) and isset($returneduser['descriptionformat'])) {
                    $this->assertEquals($generateduser->descriptionformat, $returneduser['descriptionformat']);
                }
                if (!empty($generateduser->city)) {
                    $this->assertEquals($generateduser->city, $returneduser['city']);
                }
                if (!empty($generateduser->country)) {
                    $this->assertEquals($generateduser->country, $returneduser['country']);
                }
                if (!empty($generateduser->url)) {
                    $this->assertEquals($generateduser->url, $returneduser['url']);
                }
                if (!empty($CFG->usetags) and !empty($generateduser->interests)) {
                    $this->assertEquals(implode(', ', $generateduser->interests), $returneduser['interests']);
                }
                // Check empty since incorrect values were used when creating the user.
                if ($returneduser['id'] == $user1->id) {
                    $this->assertEmpty($returneduser['lang']);
                    $this->assertEmpty($returneduser['theme']);
                }
            }
        }

        // Test that no result are returned for search by username if we are not admin
        $this->setGuestUser();

        // Call the external function.
        $returnedusers = core_user_external::get_users_by_field('username',
                    array($USER->username, $user1->username, $user2->username));
        $returnedusers = external_api::clean_returnvalue(core_user_external::get_users_by_field_returns(), $returnedusers);

        // Only the own $USER username should be returned
        $this->assertEquals(1, count($returnedusers));

        // And finally test as one of the enrolled users.
        $this->setUser($user1);

        // Call the external function.
        $returnedusers = core_user_external::get_users_by_field('username',
            array($USER->username, $user1->username, $user2->username));
        $returnedusers = external_api::clean_returnvalue(core_user_external::get_users_by_field_returns(), $returnedusers);

        // Only the own $USER username should be returned still.
        $this->assertEquals(1, count($returnedusers));
    }

    public function get_course_user_profiles_setup($capability) {
        global $USER, $CFG;


        $return = new stdClass();

        // Create the course and fetch its context.
        $return->course = self::getDataGenerator()->create_course();
        $return->user1 = array(
            'username' => 'usernametest1',
            'idnumber' => 'idnumbertest1',
            'firstname' => 'First Name User Test 1',
            'lastname' => 'Last Name User Test 1',
            'email' => 'usertest1@example.com',
            'address' => '2 Test Street Perth 6000 WA',
            'phone1' => '01010101010',
            'phone2' => '02020203',
            'skype' => 'testuser1',
            'department' => 'Department of user 1',
            'institution' => 'Institution of user 1',
            'description' => 'This is a description for user 1',
            'descriptionformat' => FORMAT_MOODLE,
            'city' => 'Perth',
            'url' => 'http://moodle.org',
            'country' => 'AU'
        );
        $return->user1 = self::getDataGenerator()->create_user($return->user1);
        if (!empty($CFG->usetags)) {
            require_once($CFG->dirroot . '/user/editlib.php');
            $return->user1->interests = array('Cinema', 'Tennis', 'Dance', 'Guitar', 'Cooking');
            useredit_update_interests($return->user1, $return->user1->interests);
        }
        $return->user2 = self::getDataGenerator()->create_user();

        $context = context_course::instance($return->course->id);
        $return->roleid = $this->assignUserCapability($capability, $context->id);

        // Enrol the users in the course.
        $this->getDataGenerator()->enrol_user($return->user1->id, $return->course->id, $return->roleid, 'manual');
        $this->getDataGenerator()->enrol_user($return->user2->id, $return->course->id, $return->roleid, 'manual');
        $this->getDataGenerator()->enrol_user($USER->id, $return->course->id, $return->roleid, 'manual');

        return $return;
    }

    /**
     * Test get_course_user_profiles
     */
    public function test_get_course_user_profiles() {
        global $USER, $CFG;


        $data = $this->get_course_user_profiles_setup('moodle/user:viewdetails');

        // Call the external function.
        $enrolledusers = core_user_external::get_course_user_profiles(array(
                    array('userid' => $USER->id, 'courseid' => $data->course->id)));

        // We need to execute the return values cleaning process to simulate the web service server.
        $enrolledusers = external_api::clean_returnvalue(core_user_external::get_course_user_profiles_returns(), $enrolledusers);

        // Check we retrieve the good total number of enrolled users + no error on capability.
        $this->assertEquals(1, count($enrolledusers));
    }

    public function test_get_user_course_profile_as_admin() {
        global $USER, $CFG;

        global $USER, $CFG;


        $data = $this->get_course_user_profiles_setup('moodle/user:viewdetails');

        // Do the same call as admin to receive all possible fields.
        $this->setAdminUser();
        $USER->email = "admin@example.com";

        // Call the external function.
        $enrolledusers = core_user_external::get_course_user_profiles(array(
            array('userid' => $data->user1->id, 'courseid' => $data->course->id)));

        // We need to execute the return values cleaning process to simulate the web service server.
        $enrolledusers = external_api::clean_returnvalue(core_user_external::get_course_user_profiles_returns(), $enrolledusers);

        foreach($enrolledusers as $enrolleduser) {
            if ($enrolleduser['username'] == $data->user1->username) {
                $this->assertEquals($data->user1->idnumber, $enrolleduser['idnumber']);
                $this->assertEquals($data->user1->firstname, $enrolleduser['firstname']);
                $this->assertEquals($data->user1->lastname, $enrolleduser['lastname']);
                $this->assertEquals($data->user1->email, $enrolleduser['email']);
                $this->assertEquals($data->user1->address, $enrolleduser['address']);
                $this->assertEquals($data->user1->phone1, $enrolleduser['phone1']);
                $this->assertEquals($data->user1->phone2, $enrolleduser['phone2']);
                $this->assertEquals($data->user1->skype, $enrolleduser['skype']);
                $this->assertEquals($data->user1->department, $enrolleduser['department']);
                $this->assertEquals($data->user1->institution, $enrolleduser['institution']);
                $this->assertEquals($data->user1->description, $enrolleduser['description']);
                $this->assertEquals(FORMAT_HTML, $enrolleduser['descriptionformat']);
                $this->assertEquals($data->user1->city, $enrolleduser['city']);
                $this->assertEquals($data->user1->country, $enrolleduser['country']);
                $this->assertEquals($data->user1->url, $enrolleduser['url']);
                if (!empty($CFG->usetags)) {
                    $this->assertEquals(implode(', ', $data->user1->interests), $enrolleduser['interests']);
                }
            }
        }
    }

    /**
     * Test create_users
     */
    public function test_create_users() {
         global $DB;


        $user1 = array(
            'username' => 'usernametest1',
            'password' => 'Moodle2012!',
            'idnumber' => 'idnumbertest1',
            'firstname' => 'First Name User Test 1',
            'lastname' => 'Last Name User Test 1',
            'middlename' => 'Middle Name User Test 1',
            'lastnamephonetic' => '最後のお名前のテスト一号',
            'firstnamephonetic' => 'お名前のテスト一号',
            'alternatename' => 'Alternate Name User Test 1',
            'email' => 'usertest1@example.com',
            'description' => 'This is a description for user 1',
            'city' => 'Perth',
            'country' => 'AU',
            'preferences' => [[
                    'type' => 'htmleditor',
                    'value' => 'atto'
                ], [
                    'type' => 'invalidpreference',
                'value' => 'abcd'
            ]
            ],
            'department' => 'College of Science',
            'institution' => 'National Institute of Physics',
            'phone1' => '01 2345 6789',
            'maildisplay' => 1,
            'interests' => 'badminton, basketball, cooking,  '
        );

        // User with an authentication method done externally.
        $user2 = array(
            'username' => 'usernametest2',
            'firstname' => 'First Name User Test 2',
            'lastname' => 'Last Name User Test 2',
            'email' => 'usertest2@example.com',
            'auth' => 'oauth2'
        );

        $context = context_system::instance();
        $roleid = $this->assignUserCapability('moodle/user:create', $context->id);
        $this->assignUserCapability('moodle/user:editprofile', $context->id, $roleid);

        // Call the external function.
        $createdusers = core_user_external::create_users([$user1, $user2]);

        // We need to execute the return values cleaning process to simulate the web service server.
        $createdusers = external_api::clean_returnvalue(core_user_external::create_users_returns(), $createdusers);

        // Check we retrieve the good total number of created users + no error on capability.
        $this->assertCount(2, $createdusers);

        foreach($createdusers as $createduser) {
            $dbuser = $DB->get_record('user', array('id' => $createduser['id']));

            if ($createduser['username'] === $user1['username']) {
                $usertotest = $user1;
                $this->assertEquals('atto', get_user_preferences('htmleditor', null, $dbuser));
                $this->assertEquals(null, get_user_preferences('invalidpreference', null, $dbuser));
                // Confirm user interests have been saved.
                $interests = core_tag_tag::get_item_tags_array('core', 'user', $createduser['id'],
                    core_tag_tag::BOTH_STANDARD_AND_NOT, 0, false);
                // There should be 3 user interests.
                $this->assertCount(3, $interests);

            } else if ($createduser['username'] === $user2['username']) {
                $usertotest = $user2;
            }

            foreach ($dbuser as $property => $value) {
                if ($property === 'password') {
                    if ($usertotest === $user2) {
                        // External auth mechanisms don't store password in the user table.
                        $this->assertEquals(AUTH_PASSWORD_NOT_CACHED, $value);
                    } else {
                        // Skip hashed passwords.
                        continue;
                    }
                }
                // Confirm that the values match.
                if (isset($usertotest[$property])) {
                    $this->assertEquals($usertotest[$property], $value);
                }
            }
        }

        // Call without required capability
        $this->unassignUserCapability('moodle/user:create', $context->id, $roleid);
        $this->expectException('required_capability_exception');
        core_user_external::create_users(array($user1));
    }

    /**
     * Test create_users with password and createpassword parameter not set.
     */
    public function test_create_users_empty_password() {
        $this->resetAfterTest();
        $this->setAdminUser();

        $user = [
            'username' => 'usernametest1',
            'firstname' => 'First Name User Test 1',
            'lastname' => 'Last Name User Test 1',
            'email' => 'usertest1@example.com',
        ];

        // This should throw an exception because either password or createpassword param must be passed for auth_manual.
        $this->expectException(invalid_parameter_exception::class);
        core_user_external::create_users([$user]);
    }

    /**
     * Test create_users with invalid parameters
     *
     * @dataProvider data_create_users_invalid_parameter
     * @param array $data User data to attempt to register.
     * @param string $expectmessage Expected exception message.
     */
    public function test_create_users_invalid_parameter(array $data, $expectmessage) {
        global $USER, $CFG, $DB;

        $this->assignUserCapability('moodle/user:create', SYSCONTEXTID);

        $this->expectException('invalid_parameter_exception');
        $this->expectExceptionMessage($expectmessage);

        core_user_external::create_users(array($data));
    }

    /**
     * Data provider for {@link self::test_create_users_invalid_parameter()}.
     *
     * @return array
     */
    public function data_create_users_invalid_parameter() {
        return [
            'blank_username' => [
                'data' => [
                    'username' => '',
                    'firstname' => 'Foo',
                    'lastname' => 'Bar',
                    'email' => 'foobar@example.com',
                    'createpassword' => 1,
                ],
                'expectmessage' => 'The field username cannot be blank',
            ],
            'blank_firtname' => [
                'data' => [
                    'username' => 'foobar',
                    'firstname' => "\t \n",
                    'lastname' => 'Bar',
                    'email' => 'foobar@example.com',
                    'createpassword' => 1,
                ],
                'expectmessage' => 'The field firstname cannot be blank',
            ],
            'blank_lastname' => [
                'data' => [
                    'username' => 'foobar',
                    'firstname' => '0',
                    'lastname' => '   ',
                    'email' => 'foobar@example.com',
                    'createpassword' => 1,
                ],
                'expectmessage' => 'The field lastname cannot be blank',
            ],
            'invalid_email' => [
                'data' => [
                    'username' => 'foobar',
                    'firstname' => 'Foo',
                    'lastname' => 'Bar',
                    'email' => '@foobar',
                    'createpassword' => 1,
                ],
                'expectmessage' => 'Email address is invalid',
            ],
            'missing_password' => [
                'data' => [
                    'username' => 'foobar',
                    'firstname' => 'Foo',
                    'lastname' => 'Bar',
                    'email' => 'foobar@example.com',
                ],
                'expectmessage' => 'Invalid password: you must provide a password, or set createpassword',
            ],
        ];
    }

    /**
     * Test delete_users
     */
    public function test_delete_users() {
        global $USER, $CFG, $DB;


        $user1 = self::getDataGenerator()->create_user();
        $user2 = self::getDataGenerator()->create_user();

        // Check the users were correctly created.
        $this->assertEquals(2, $DB->count_records_select('user', 'deleted = 0 AND (id = :userid1 OR id = :userid2)',
                array('userid1' => $user1->id, 'userid2' => $user2->id)));

        $context = context_system::instance();
        $roleid = $this->assignUserCapability('moodle/user:delete', $context->id);

        // Call the external function.
        core_user_external::delete_users(array($user1->id, $user2->id));

        // Check we retrieve no users + no error on capability.
        $this->assertEquals(0, $DB->count_records_select('user', 'deleted = 0 AND (id = :userid1 OR id = :userid2)',
                array('userid1' => $user1->id, 'userid2' => $user2->id)));

        // Call without required capability.
        $this->unassignUserCapability('moodle/user:delete', $context->id, $roleid);
        $this->expectException('required_capability_exception');
        core_user_external::delete_users(array($user1->id, $user2->id));
    }

    /**
     * Test update_users
     */
    public function test_update_users() {
        global $USER, $CFG, $DB;


        $wsuser = self::getDataGenerator()->create_user();
        self::setUser($wsuser);

        $context = context_user::instance($USER->id);
        $contextid = $context->id;
        $filename = "reddot.png";
        $filecontent = "iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAHElEQVQI12P4//8/w38"
            . "GIAXDIBKE0DHxgljNBAAO9TXL0Y4OHwAAAABJRU5ErkJggg==";

        // Call the files api to create a file.
        $draftfile = core_files_external::upload($contextid, 'user', 'draft', 0, '/',
                $filename, $filecontent, null, null);
        $draftfile = external_api::clean_returnvalue(core_files_external::upload_returns(), $draftfile);

        $draftid = $draftfile['itemid'];

        $user1 = self::getDataGenerator()->create_user();

        $user1 = array(
            'id' => $user1->id,
            'username' => 'usernametest1',
            'password' => 'Moodle2012!',
            'idnumber' => 'idnumbertest1',
            'firstname' => 'First Name User Test 1',
            'lastname' => 'Last Name User Test 1',
            'middlename' => 'Middle Name User Test 1',
            'lastnamephonetic' => '最後のお名前のテスト一号',
            'firstnamephonetic' => 'お名前のテスト一号',
            'alternatename' => 'Alternate Name User Test 1',
            'email' => 'usertest1@example.com',
            'description' => 'This is a description for user 1',
            'city' => 'Perth',
            'userpicture' => $draftid,
            'country' => 'AU',
            'preferences' => [[
                    'type' => 'htmleditor',
                    'value' => 'atto'
                ], [
                    'type' => 'invialidpreference',
                    'value' => 'abcd'
                ]]
            );

        $context = context_system::instance();
        $roleid = $this->assignUserCapability('moodle/user:update', $context->id);
        $this->assignUserCapability('moodle/user:editprofile', $context->id, $roleid);

        // Check we can't update deleted users, guest users, site admin.
        $user2 = $user3 = $user4 = $user1;
        $user2['id'] = $CFG->siteguest;

        $siteadmins = explode(',', $CFG->siteadmins);
        $user3['id'] = array_shift($siteadmins);

        $userdeleted = self::getDataGenerator()->create_user();
        $user4['id'] = $userdeleted->id;
        user_delete_user($userdeleted);

        // Check we can't update deleted users, guest users, site admin.
        $user2 = $user3 = $user4 = $user1;
        $user2['id'] = $CFG->siteguest;

        $siteadmins = explode(',', $CFG->siteadmins);
        $user3['id'] = array_shift($siteadmins);

        $userdeleted = self::getDataGenerator()->create_user();
        $user4['id'] = $userdeleted->id;
        user_delete_user($userdeleted);

        // Call the external function.
        core_user_external::update_users(array($user1, $user2, $user3, $user4));

        $dbuser2 = $DB->get_record('user', array('id' => $user2['id']));
        $this->assertNotEquals($dbuser2->username, $user2['username']);
        $dbuser3 = $DB->get_record('user', array('id' => $user3['id']));
        $this->assertNotEquals($dbuser3->username, $user3['username']);
        $dbuser4 = $DB->get_record('user', array('id' => $user4['id']));
        $this->assertNotEquals($dbuser4->username, $user4['username']);

        $dbuser = $DB->get_record('user', array('id' => $user1['id']));
        $this->assertEquals($dbuser->username, $user1['username']);
        $this->assertEquals($dbuser->idnumber, $user1['idnumber']);
        $this->assertEquals($dbuser->firstname, $user1['firstname']);
        $this->assertEquals($dbuser->lastname, $user1['lastname']);
        $this->assertEquals($dbuser->email, $user1['email']);
        $this->assertEquals($dbuser->description, $user1['description']);
        $this->assertEquals($dbuser->city, $user1['city']);
        $this->assertEquals($dbuser->country, $user1['country']);
        $this->assertNotEquals(0, $dbuser->picture, 'Picture must be set to the new icon itemid for this user');
        $this->assertEquals('atto', get_user_preferences('htmleditor', null, $dbuser));
        $this->assertEquals(null, get_user_preferences('invalidpreference', null, $dbuser));

        // Confirm no picture change when parameter is not supplied.
        unset($user1['userpicture']);
        core_user_external::update_users(array($user1));
        $dbusernopic = $DB->get_record('user', array('id' => $user1['id']));
        $this->assertEquals($dbuser->picture, $dbusernopic->picture, 'Picture not change without the parameter.');

        // Confirm delete of picture deletes the picture from the user record.
        $user1['userpicture'] = 0;
        core_user_external::update_users(array($user1));
        $dbuserdelpic = $DB->get_record('user', array('id' => $user1['id']));
        $this->assertEquals(0, $dbuserdelpic->picture, 'Picture must be deleted when sent as 0.');

        // Call without required capability.
        $this->unassignUserCapability('moodle/user:update', $context->id, $roleid);
        $this->expectException('required_capability_exception');
        core_user_external::update_users(array($user1));
    }

    /**
     * Totara: test user suspending
     */
    public function test_update_users_suspend() {
        global $DB;

        $this->setAdminUser();
        $user = self::getDataGenerator()->create_user();

        $sink = $this->redirectEvents();

        // Note that user field validation is busted bool properties.
        $request1 = array(
            'id' => $user->id,
            'username' => 'newusername',
            'suspended' => 1,
        );
        core_user_external::update_users(array($request1));
        $newuser = $DB->get_record('user', array('id' => $user->id));
        $this->assertSame('1', $newuser->suspended);
        $events = $sink->get_events();
        $this->assertCount(2, $events);
        $this->assertInstanceOf('core\event\user_updated', $events[0]);
        $this->assertInstanceOf('totara_core\event\user_suspended', $events[1]);
    }

    /**
     * Test update_users using duplicated email.
     */
    public function test_update_users_duplicated_email() {
        global $DB, $CFG;

        $this->setAdminUser();

        $user1 = self::getDataGenerator()->create_user();
        $user2 = self::getDataGenerator()->create_user();
        $user2toupdate = array(
            'id' => $user2->id,
            'email' => $user1->email,
        );
        // E-mail duplicated not allowed.
        $CFG->allowaccountssameemail = 0;
        core_user_external::update_users(array($user2toupdate));
        $this->assertNotEquals($user1->email, $DB->get_field('user', 'email', array('id' => $user2->id)));
        // E-mail duplicated allowed.
        $CFG->allowaccountssameemail = 1;
        core_user_external::update_users(array($user2toupdate));
        $this->assertEquals($user1->email, $DB->get_field('user', 'email', array('id' => $user2->id)));
    }

    /**
     * Test add_user_private_files
     */
    public function test_add_user_private_files() {
        global $USER, $CFG, $DB;


        $context = context_system::instance();
        $roleid = $this->assignUserCapability('moodle/user:manageownfiles', $context->id);

        $context = context_user::instance($USER->id);
        $contextid = $context->id;
        $component = "user";
        $filearea = "draft";
        $itemid = 0;
        $filepath = "/";
        $filename = "Simple.txt";
        $filecontent = base64_encode("Let us create a nice simple file");
        $contextlevel = null;
        $instanceid = null;
        $browser = get_file_browser();

        // Call the files api to create a file.
        $draftfile = core_files_external::upload($contextid, $component, $filearea, $itemid, $filepath,
                                                 $filename, $filecontent, $contextlevel, $instanceid);
        $draftfile = external_api::clean_returnvalue(core_files_external::upload_returns(), $draftfile);

        $draftid = $draftfile['itemid'];
        // Make sure the file was created.
        $file = $browser->get_file_info($context, $component, $filearea, $draftid, $filepath, $filename);
        $this->assertNotEmpty($file);

        // Make sure the file does not exist in the user private files.
        $file = $browser->get_file_info($context, $component, 'private', 0, $filepath, $filename);
        $this->assertEmpty($file);

        // Call the external function.
        core_user_external::add_user_private_files($draftid);

        // Make sure the file was added to the user private files.
        $file = $browser->get_file_info($context, $component, 'private', 0, $filepath, $filename);
        $this->assertNotEmpty($file);
    }

    /**
     * Test add user device
     */
    public function test_add_user_device() {
        global $USER, $CFG, $DB;


        $device = array(
                'appid' => 'com.moodle.moodlemobile',
                'name' => 'occam',
                'model' => 'Nexus 4',
                'platform' => 'Android',
                'version' => '4.2.2',
                'pushid' => 'apushdkasdfj4835',
                'uuid' => 'asdnfl348qlksfaasef859'
                );

        // Call the external function.
        core_user_external::add_user_device($device['appid'], $device['name'], $device['model'], $device['platform'],
                                            $device['version'], $device['pushid'], $device['uuid']);

        $created = $DB->get_record('user_devices', array('pushid' => $device['pushid']));
        $created = (array) $created;

        $this->assertEquals($device, array_intersect_key((array)$created, $device));

        // Test reuse the same pushid value.
        $warnings = core_user_external::add_user_device($device['appid'], $device['name'], $device['model'], $device['platform'],
                                                        $device['version'], $device['pushid'], $device['uuid']);
        // We need to execute the return values cleaning process to simulate the web service server.
        $warnings = external_api::clean_returnvalue(core_user_external::add_user_device_returns(), $warnings);
        $this->assertCount(1, $warnings);

        // Test update an existing device.
        $device['pushid'] = 'different than before';
        $warnings = core_user_external::add_user_device($device['appid'], $device['name'], $device['model'], $device['platform'],
                                                        $device['version'], $device['pushid'], $device['uuid']);
        $warnings = external_api::clean_returnvalue(core_user_external::add_user_device_returns(), $warnings);

        $this->assertEquals(1, $DB->count_records('user_devices'));
        $updated = $DB->get_record('user_devices', array('pushid' => $device['pushid']));
        $this->assertEquals($device, array_intersect_key((array)$updated, $device));

        // Test creating a new device just changing the uuid.
        $device['uuid'] = 'newuidforthesameuser';
        $device['pushid'] = 'new different than before';
        $warnings = core_user_external::add_user_device($device['appid'], $device['name'], $device['model'], $device['platform'],
                                                        $device['version'], $device['pushid'], $device['uuid']);
        $warnings = external_api::clean_returnvalue(core_user_external::add_user_device_returns(), $warnings);
        $this->assertEquals(2, $DB->count_records('user_devices'));
    }

    /**
     * Test remove user device
     */
    public function test_remove_user_device() {
        global $USER, $CFG, $DB;


        $device = array(
                'appid' => 'com.moodle.moodlemobile',
                'name' => 'occam',
                'model' => 'Nexus 4',
                'platform' => 'Android',
                'version' => '4.2.2',
                'pushid' => 'apushdkasdfj4835',
                'uuid' => 'ABCDE3723ksdfhasfaasef859'
                );

        // A device with the same properties except the appid and pushid.
        $device2 = $device;
        $device2['pushid'] = "0987654321";
        $device2['appid'] = "other.app.com";

        $this->setAdminUser();
        // Create a user device using the external API function.
        core_user_external::add_user_device($device['appid'], $device['name'], $device['model'], $device['platform'],
                                            $device['version'], $device['pushid'], $device['uuid']);

        // Create the same device but for a different app.
        core_user_external::add_user_device($device2['appid'], $device2['name'], $device2['model'], $device2['platform'],
                                            $device2['version'], $device2['pushid'], $device2['uuid']);

        // Try to remove a device that does not exist.
        $result = core_user_external::remove_user_device('1234567890');
        $result = external_api::clean_returnvalue(core_user_external::remove_user_device_returns(), $result);
        $this->assertFalse($result['removed']);
        $this->assertCount(1, $result['warnings']);

        // Try to remove a device that does not exist for an existing app.
        $result = core_user_external::remove_user_device('1234567890', $device['appid']);
        $result = external_api::clean_returnvalue(core_user_external::remove_user_device_returns(), $result);
        $this->assertFalse($result['removed']);
        $this->assertCount(1, $result['warnings']);

        // Remove an existing device for an existing app. This will remove one of the two devices.
        $result = core_user_external::remove_user_device($device['uuid'], $device['appid']);
        $result = external_api::clean_returnvalue(core_user_external::remove_user_device_returns(), $result);
        $this->assertTrue($result['removed']);

        // Remove all the devices. This must remove the remaining device.
        $result = core_user_external::remove_user_device($device['uuid']);
        $result = external_api::clean_returnvalue(core_user_external::remove_user_device_returns(), $result);
        $this->assertTrue($result['removed']);
    }

    /**
     * Test update_picture
     */
    public function test_update_picture() {
        global $DB, $USER;


        $user = self::getDataGenerator()->create_user();
        self::setUser($user);

        $context = context_user::instance($USER->id);
        $contextid = $context->id;
        $filename = "reddot.png";
        $filecontent = "iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAHElEQVQI12P4//8/w38"
            . "GIAXDIBKE0DHxgljNBAAO9TXL0Y4OHwAAAABJRU5ErkJggg==";

        // Call the files api to create a file.
        $draftfile = core_files_external::upload($contextid, 'user', 'draft', 0, '/', $filename, $filecontent, null, null);
        $draftid = $draftfile['itemid'];

        // Change user profile image.
        $result = core_user_external::update_picture($draftid);
        $result = external_api::clean_returnvalue(core_user_external::update_picture_returns(), $result);
        $picture = $DB->get_field('user', 'picture', array('id' => $user->id));
        // The new revision is in the url for the user.
        $this->assertStringContainsString($picture, $result['profileimageurl']);
        // Check expected URL for serving the image.
        $this->assertStringContainsString("/$contextid/user/icon", $result['profileimageurl']);

        // Delete image.
        $result = core_user_external::update_picture(0, true);
        $result = external_api::clean_returnvalue(core_user_external::update_picture_returns(), $result);
        $picture = $DB->get_field('user', 'picture', array('id' => $user->id));
        // No picture.
        $this->assertEquals(0, $picture);

        // Add again the user profile image (as admin).
        $this->setAdminUser();

        $context = context_user::instance($USER->id);
        $admincontextid = $context->id;
        $draftfile = core_files_external::upload($admincontextid, 'user', 'draft', 0, '/', $filename, $filecontent, null, null);
        $draftid = $draftfile['itemid'];

        $result = core_user_external::update_picture($draftid, false, $user->id);
        $result = external_api::clean_returnvalue(core_user_external::update_picture_returns(), $result);
        // The new revision is in the url for the user.
        $picture = $DB->get_field('user', 'picture', array('id' => $user->id));
        $this->assertStringContainsString($picture, $result['profileimageurl']);
        $this->assertStringContainsString("/$contextid/user/icon", $result['profileimageurl']);
    }

    /**
     * Test update_picture disabled
     */
    public function test_update_picture_disabled() {
        global $CFG;
        $CFG->disableuserimages = true;

        $this->setAdminUser();
        $this->expectException('moodle_exception');
        core_user_external::update_picture(0);
    }

    /**
     * Test agree_site_policy
     */
    public function test_agree_site_policy() {
        global $CFG, $DB, $USER;

        $user = self::getDataGenerator()->create_user();
        $this->setUser($user);

        // Site policy not set.
        $result = core_user_external::agree_site_policy();
        $result = external_api::clean_returnvalue(core_user_external::agree_site_policy_returns(), $result);
        $this->assertFalse($result['status']);
        $this->assertCount(1, $result['warnings']);
        $this->assertEquals('nositepolicy', $result['warnings'][0]['warningcode']);

        // Set a policy issue.
        $CFG->sitepolicy = 'https://moodle.org';
        $this->assertEquals(0, $USER->policyagreed);

        $result = core_user_external::agree_site_policy();
        $result = external_api::clean_returnvalue(core_user_external::agree_site_policy_returns(), $result);
        $this->assertTrue($result['status']);
        $this->assertCount(0, $result['warnings']);
        $this->assertEquals(1, $USER->policyagreed);
        $this->assertEquals(1, $DB->get_field('user', 'policyagreed', array('id' => $USER->id)));

        // Try again, we should get a warning.
        $result = core_user_external::agree_site_policy();
        $result = external_api::clean_returnvalue(core_user_external::agree_site_policy_returns(), $result);
        $this->assertFalse($result['status']);
        $this->assertCount(1, $result['warnings']);
        $this->assertEquals('alreadyagreed', $result['warnings'][0]['warningcode']);

        // Set something to make require_login throws an exception.
        $otheruser = self::getDataGenerator()->create_user();
        $this->setUser($otheruser);

        $DB->set_field('user', 'lastname', '', array('id' => $USER->id));
        $USER->lastname = '';
        try {
            $result = core_user_external::agree_site_policy();
            $this->fail('Expecting \'usernotfullysetup\' moodle_exception to be thrown');
        } catch (moodle_exception $e) {
            $this->assertEquals('usernotfullysetup', $e->errorcode);
        } catch (Exception $e) {
            $this->fail('Expecting \'usernotfullysetup\' moodle_exception to be thrown.');
        }

    }
}
