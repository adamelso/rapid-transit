<?php

namespace Transit\Context;

use Behat\Gherkin\Node\TableNode;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Routing\RouterInterface;

class LoginContext extends SymfonyContext
{
    private $userCredentials = [];

    /**
     * @Given /^I am not logged in$/
     */
    public function iAmNotLoggedIn()
    {
        $this->getSession()->restart();
    }

    /**
     * @Given there are following users:
     */
    public function thereAreFollowingUsers(TableNode $table)
    {
        foreach ($table->getHash() as $data) {
            $this->thereIsUser(
                $data['email'],
                isset($data['password']) ? $data['password'] : $this->faker->word(),
                isset($data['admin']) && $data['admin'] === 'yes' ? 'ROLE_ADMIN' : 'ROLE_USER',
                isset($data['enabled']) ? $data['enabled'] : true
            );
        }

        $this->getManager()->flush();
    }

    public function thereIsUser($email, $password, $role = 'ROLE_USER')
    {
        $this->userCredentials[$email] = $password;

        /** @var UserManagerInterface $userManager */
        $userManager = $this->getRepository('user');

        if (null === $user = $userManager->findUserBy(['email' => $email])) {
            $user = $userManager->createUser();

            $username = $this->faker->userName;

            $user->setUsername($username);
            $user->setEmail($email);
            $user->setPlainPassword($password);
            $user->setEnabled(true);

            $user->addRole($role);

            //$user->setSuperAdmin(false);

            $userManager->updateUser($user, true);
        }

        return $user;
    }

    /**
     * Get screenshot
     *
     * @param string $filename
     *
     * @When /^(?:|I )capture the screen to "([^"]+)"$/
     */
    public function captureScreen($filename)
    {
        $image = $this->getSession()->getDriver()->getScreenshot();

        if ($image) {
            file_put_contents(__DIR__.'/'.$filename, base64_decode($image));
        }
    }


    /**
     * @When /^wait (\d+) seconds?$/
     */
    public function waitSeconds($seconds)
    {
        $this->getSession()->wait(1000*$seconds);
    }


    /**
     * @Then I should be on the Login page
     */
    public function assertLoginPage()
    {
        $this->assertSession()->addressMatches($this->fixStepArgument('~\/([a-z]{2})\/login~'));
    }

    /**
     * @Given I am on the :page page
     */
    public function iAmOnThePage($page)
    {
        $paths = [
            'Login' => 'fos_user_security_login',
            'Registration' => 'fos_user_registration_register',
        ];

        $this->getSession()->visit($this->locatePath($this->getRouter()->generate($paths[$page])));
    }

    /**
     * @Then I should be on the Registration page
     */
    public function iShouldBeOnRegistrationPage()
    {
        $this->assertSession()->addressMatches($this->fixStepArgument('~\/([a-z]{2})\/register~'));
    }

    /**
     * @return RouterInterface
     */
    private function getRouter()
    {
        return $this->getService('router');
    }

    /**
     * Checks, that element with specified CSS exists on page.
     *
     * @Then /^(?:|I )should see an? "(?P<element>[^"]*)" image$/
     */
    public function assertImageOnPage($element)
    {
        $this->assertSession()->elementExists('css', sprintf('img.%s', $element));
    }

    /**
     * @Given I am logged in as :email
     */
    public function iAmLoggedInAs($email)
    {
        $this->getSession()->visit($this->locatePath($this->generateUrl('fos_user_security_login')));
        $this->fillField('Username', $email);
        $this->fillField('Password', $this->userCredentials[$email]);
        $this->pressButton('Login');
    }

    /**
     * @Then I should see the connect with :connect button
     */
    public function iShouldSeeTheConnectWithButton($connect)
    {
        $this->assertSession()->elementExists('css', sprintf('.oauth-login-%s', strtolower($connect)));
    }

    /**
     * Override Mink context for i18n routing.
     *
     * Checks, that current page is the homepage.
     *
     * @Then I should be on the localized homepage
     */
    public function assertHomepage()
    {
        $this->assertSession()->addressEquals($this->locatePath($this->generateUrl('transit_web_homepage')));
    }
}
