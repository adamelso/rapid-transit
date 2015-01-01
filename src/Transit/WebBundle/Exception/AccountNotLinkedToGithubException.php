<?php

namespace Transit\WebBundle\Exception;

/**
 * If a user wants to use data fetched from GitHub, but has not linked
 * their account to GitHub, this exception should be thrown and caught.
 *
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class AccountNotLinkedToGithubException extends \UnexpectedValueException
{
}
