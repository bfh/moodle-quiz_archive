@mod @mod_quiz @quiz @quiz_archive @javascript
Feature: Showing a message if there are no attempts to show
    If there are no attempts to show
    As a teacher
    I need to see a notification

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email                | idnumber |
      | teacher1 | T1        | Teacher1 | teacher1@example.com | T1000    |
      | student1 | S1        | Student1 | student1@example.com | S1000    |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | 0        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |
    And the following "question categories" exist:
      | contextlevel | reference | name           |
      | Course       | C1        | Test questions |
    And the following "activities" exist:
      | activity | name   | intro              | course | idnumber |
      | quiz     | Quiz 1 | Quiz 1 description | C1     | quiz1    |
    And the following "questions" exist:
      | questioncategory | qtype     | name | questiontext   |
      | Test questions   | truefalse | TF1  | First question |
    And quiz "Quiz 1" contains the following questions:
      | question | page | maxmark |
      | TF1      | 1    |         |

    And I am on the "Quiz 1" "quiz_archive > Archive" page logged in as "teacher1"

  Scenario: Check handling of no attempts situation
    # Check if no attempt
    Then I should see "Nothing to display"

    # Add an attempt
    And I log in as "student1"
    And I am on "Course 1" course homepage
    And I follow "Quiz 1"
    And I press "Attempt quiz"
    And I click on "True" "radio" in the "First question" "question"
    And I press "Finish attempt ..."
    And I press "Submit all and finish"
    And I confirm the quiz submission in the modal dialog for the quiz_archive plugin
    And I log out
    And I am on the "Quiz 1" "quiz_archive > Archive" page logged in as "teacher1"

    # Check that the message is gone
    Then I should not see "Nothing to display"
