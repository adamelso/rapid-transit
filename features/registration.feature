@users
Feature: User registration
  In order to contact buyers and sellers
  As a visitor
  I need to be able to create an account in the marketplace

  Background:
    Given there are following users:
      | email       | password  |
      | bar@bar.com | foo       |

  Scenario: Successfully creating account in the marketplace
    Given I am on the "Login" page
    And I follow "Sign Up"
    When I fill in the following:
      | Username      | jdoe        |
      | Email         | foo@bar.com |
      | Password      | bar         |
      | Verification  | bar         |
    And I press "Register"
    Then I should see "The user has been created successfully"
    And I should see "your account is now activated"
    And I should see "Logout"

  Scenario: Trying to register with non verified password
    Given I am on the "Login" page
    And I follow "Sign Up"
    When I fill in the following:
      | Email         | foo@bar.com |
      | Username      | jdoe        |
      | Password      | bar         |
      | Verification  | foo         |
    And I press "Register"
    Then I should be on the Registration page
    And I should see "The entered passwords don't match"

  Scenario: Trying to register with a too short password
    Given I am on the "Login" page
    And I follow "Sign Up"
    When I fill in the following:
      | Email         | bar@bar.com |
      | Username      | jdoe        |
      | Password      | x           |
      | Verification  | x           |
    And I press "Register"
    Then I should be on the Registration page
    And I should see "The password is too short"

  Scenario: Trying to register with an already existing email address
    Given I am on the "Login" page
    And I follow "Sign Up"
    When I fill in the following:
      | Email         | Bar@bar.com |
      | Username      | Jdoe        |
      | Password      | bar         |
      | Verification  | bar         |
    And I press "Register"
    Then I should be on the Registration page
    And I should see "The email is already used"
