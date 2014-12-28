@users
Feature: Sign in to the app via OAuth
  In order to view my projects
  As a user with an OAuth account
  I need to be able to log in to the app using my OAuth account

  Scenario Outline: Get to the OAuth login page
    Given I am on the homepage
    When I follow "Login"
    Then I should see the connect with "<oauth>" button

  Examples:
    | oauth  |
    | GitHub |
