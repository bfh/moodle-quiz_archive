@mod @mod_quiz @quiz @quiz_archive @javascript
Feature: Using display options to customize the report
    In order to change the appearance of my report
    As a teacher
    I need to use the available options

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email                | idnumber |
      | teacher1 | T1        | Teacher1 | teacher1@example.com | T1000    |
      | student1 | S1        | Student1 | student1@example.com | S1000    |
      | student2 | S2        | Student2 | student2@example.com | S2000    |
      | student3 | S3        | Student3 | student3@example.com | S3000    |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | 0        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |
      | student2 | C1     | student        |
      | student3 | C1     | student        |
    And the following "question categories" exist:
      | contextlevel | reference | name           |
      | Course       | C1        | Test questions |
    And the following "activities" exist:
      | activity | name   | intro              | course | idnumber |
      | quiz     | Quiz 1 | Quiz 1 description | C1     | quiz1    |
    And the following "questions" exist:
      | questioncategory | qtype     | name | questiontext    |
      | Test questions   | truefalse | TF1  | First question  |
      | Test questions   | truefalse | TF2  | Second question |
    And quiz "Quiz 1" contains the following questions:
      | question | page | maxmark |
      | TF1      | 1    |         |
      | TF2      | 1    | 3.0     |

    # Add some attempts
    And I log in as "student1"
    And I am on "Course 1" course homepage
    And I follow "Quiz 1"
    And I press "Attempt quiz"
    And I click on "True" "radio" in the "First question" "question"
    And I click on "False" "radio" in the "Second question" "question"
    And I press "Finish attempt ..."
    And I press "Submit all and finish"
    And I log out

    And I log in as "student2"
    And I am on "Course 1" course homepage
    And I follow "Quiz 1"
    And I press "Attempt quiz"
    And I click on "True" "radio" in the "First question" "question"
    And I click on "True" "radio" in the "Second question" "question"
    And I press "Finish attempt ..."
    And I press "Submit all and finish"
    And I confirm the quiz submission in the modal dialog
    And I log out
    And I am on the "Quiz 1" "quiz_archive > Archive" page logged in as "teacher1"

  Scenario: Enabling and disabling history
    # Checking with the default setting
    Then I should see "Response history"

    # Disabling the history and checking again
    When I set "id_showhistory" to "0"
    And I press "Show report"
    Then I should not see "Response history"

  Scenario: Enabling and disabling correct answer
    # Checking with the default setting
    Then I should see "The correct answer is"

    # Disabling the history and checking again
    When I set "id_showright" to "0"
    And I press "Show report"
    Then I should see "The correct answer is"
