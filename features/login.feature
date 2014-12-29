@users
Feature: Sign in to the app
  In order to use the app
  As a user
  I need to be able to log in

  Background:
    Given there are following users:
      | email            | password   | enabled |
      | adam@example.com | tR4n5it    | yes     |

  Scenario: Log in with username and password
    Given I am on the "Login" page
    When I fill in the following:
      | Username | adam@example.com |
      | Password | tR4n5it      |
    And I press "_submit"
    Then I should be on the localized homepage
    And I should see "Logout"

  Scenario: Log in with bad credentials
    Given I am on the "Login" page
    When I fill in the following:
      | Username | adam@example.com |
      | Password | wrongpassword    |
    And I press "_submit"
    Then I should be on the Login page
    And I should see "Invalid credentials."

  Scenario: Trying to login without credentials
    Given I am on the "Login" page
    When I press "_submit"
    Then I should be on the Login page

  Scenario: Trying to login as non existing user
    Given I am on the "Login" page
    When I fill in the following:
      | Username | nobody@example.com |
      | Password | tR4n5it            |
    And I press "Login"
    Then I should be on the Login page
    And I should see "Invalid credentials."
