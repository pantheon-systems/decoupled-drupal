Feature: Confirm JSON:API is enabled 
  In order to ensure decoupled apps can source data from Drupal
  As an anonymous user
  I want to verify I can visit the api root on Pantheon

  Scenario: Verify api root
    When I go to "jsonapi"
    Then the response status code should be 200
