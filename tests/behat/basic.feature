@mod @mod_quiz @quiz @quiz_archive
Feature: Basic use of the Archive report
  In order to easily get an archive of quiz attempts
  As a teacher
  I need to use the Archive report

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
      | activity   | name   | intro              | course | idnumber |
      | quiz       | Quiz 1 | Quiz 1 description | C1     | quiz1    |
    And the following "questions" exist:
      | questioncategory | qtype       | name  | questiontext    |
      | Test questions   | truefalse   | TF1   | First question  |
      | Test questions   | truefalse   | TF2   | Second question |
    And quiz "Quiz 1" contains the following questions:
      | question | page | maxmark |
      | TF1      | 1    | 1.0     |
      | TF2      | 1    | 1.0     |

  @javascript
  Scenario: Using the Archive report
    # Add some attempts
    Given I log in as "student1"
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
    And I confirm the quiz submission in the modal dialog for the quiz_archive plugin
    And I log out

    # Basic check of the Archive report
    When I am on the "Quiz 1" "quiz_archive > Archive" page logged in as "teacher1"
    Then I should see "Quiz 1"
    # Check student1's attempt
    And I should see "S1 Student1"
    # And student2's attempt
    And I should see "S2 Student2"
    # Check student1's attempt when showuserpicture is set to "Small image"
    And I am on the "Quiz 1" "quiz activity editing" page logged in as "teacher1"
    And I expand all fieldsets
    And I set the field "Show the user's picture" to "Small image"
    And I press "Save and return to course"
    And I am on the "Quiz 1" "quiz_archive > Archive" page logged in as "teacher1"
    And I should see "S1 Student1"
    # Check student1's attempt when showuserpicture is set to "Large image"
    And I am on the "Quiz 1" "quiz activity editing" page logged in as "teacher1"
    And I expand all fieldsets
    And I set the field "Show the user's picture" to "Large image"
    And I press "Save and return to course"
    And I am on the "Quiz 1" "quiz_archive > Archive" page logged in as "teacher1"
    And I should see "S1 Student1"

  @javascript
  Scenario: Using the Archive report with teacher grade override for Moodle 3.9
    # Add an attempt
    Given the site is running Moodle version 3.9 or higher
    And the site is running Moodle version 3.9 or lower
    And I log in as "student1"
    And I am on "Course 1" course homepage
    And I follow "Quiz 1"
    And I press "Attempt quiz"
    And I click on "True" "radio" in the "First question" "question"
    And I click on "False" "radio" in the "Second question" "question"
    And I press "Finish attempt ..."
    And I press "Submit all and finish"
    And I confirm the quiz submission in the modal dialog for the quiz_archive plugin
    And I log out
    And I am on the "Quiz 1" "mod_quiz > Manual grading report" page logged in as "teacher1"
    And I follow "Also show questions that have been graded automatically"
    And I click on "update grades" "link" in the "TF1" "table_row"
    And I set the field "Comment" to "I have adjusted your mark to 0.5"
    And I set the field "Mark" to "0.5"
    And I press "Save and show next"
    And I follow "Results"
    When I am on the "Quiz 1" "quiz_archive > Archive" page logged in as "teacher1"
    Then I should see "T1 Teacher1" in the "I have adjusted your mark to 0.5" "table_row"

  @javascript
  Scenario: Using the Archive report with teacher grade override for Moodle 3.11
    # Add an attempt
    Given the site is running Moodle version 3.11 or higher
    And the site is running Moodle version 3.11 or lower
    And I log in as "student1"
    And I am on "Course 1" course homepage
    And I follow "Quiz 1"
    And I press "Attempt quiz"
    And I click on "True" "radio" in the "First question" "question"
    And I click on "False" "radio" in the "Second question" "question"
    And I press "Finish attempt ..."
    And I press "Submit all and finish"
    And I confirm the quiz submission in the modal dialog for the quiz_archive plugin
    And I log out
    And I am on the "Quiz 1" "mod_quiz > Manual grading report" page logged in as "teacher1"
    And I follow "Also show questions that have been graded automatically"
    And I click on "update grades" "link" in the "TF1" "table_row"
    And I set the field "Comment" to "I have adjusted your mark to 0.5"
    And I set the field "Mark" to "0.5"
    And I press "Save and go to next page"
    And I follow "Results"
    When I am on the "Quiz 1" "quiz_archive > Archive" page logged in as "teacher1"
    Then I should see "T1 Teacher1" in the "I have adjusted your mark to 0.5" "table_row"

  @javascript
  Scenario: Using the Archive report with teacher grade override for Moodle ≥ 4.0
    # Add an attempt
    Given the site is running Moodle version 4.0 or higher
    And I log in as "student1"
    And I am on "Course 1" course homepage
    And I follow "Quiz 1"
    And I press "Attempt quiz"
    And I click on "True" "radio" in the "First question" "question"
    And I click on "False" "radio" in the "Second question" "question"
    And I press "Finish attempt ..."
    And I press "Submit all and finish"
    And I confirm the quiz submission in the modal dialog for the quiz_archive plugin
    And I log out
    And I am on the "Quiz 1" "mod_quiz > Manual grading report" page logged in as "teacher1"
    And I follow "Also show questions that have been graded automatically"
    And I click on "update grades" "link" in the "TF1" "table_row"
    And I set the field "Comment" to "I have adjusted your mark to 0.5"
    And I set the field "Mark" to "0.5"
    And I press "Save and show next"
    And I follow "Results"
    When I am on the "Quiz 1" "quiz_archive > Archive" page logged in as "teacher1"
    Then I should see "T1 Teacher1" in the "I have adjusted your mark to 0.5" "table_row"
