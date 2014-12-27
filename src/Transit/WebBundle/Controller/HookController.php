<?php

namespace Transit\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Transit\WebBundle\Hook\PayloadSigner;

class HookController extends Controller
{
    public function indexAction()
    {
        return $this->render('TransitWebBundle:Home:index.html.twig');
    }

    public function githubAction(Request $request)
    {
        $payloadJson = $request->getContent();

        if (!$this->isVerifiedSignature($request, $payloadJson)) {
            return new JsonResponse(['message' => 'Forbidden'], 403);
        }

        $payload = json_decode($payloadJson, true);

        $event = $request->headers->get('X-GitHub-Event');

        switch ($event) {
            case 'ping':
                return new JsonResponse(['message' => 'Payload received', 'payload' => $payload]);

            case 'pull_request':
            if ($payload['action'] == "closed" && $payload['pull_request']['merged']) {
                $this->startDeployment($payload['pull_request']);
            }
            break;

            case 'deployment':
            case 'deployment_status':
                return new JsonResponse(['message' => 'Payload received', 'payload' => $payload]);
        }

        return new JsonResponse(['message' => 'Payload received', 'payload' => $payload]);
    }

    /**
     * @param Request $request
     * @param string  $payloadJson
     *
     * @return bool
     */
    private function isVerifiedSignature(Request $request, $payloadJson)
    {
        return 'sha1=' . $this->getPayloadSigner()->sign($payloadJson) === $request->headers->get('X-Hub-Signature');
    }

    /**
     * @return PayloadSigner
     */
    private function getPayloadSigner()
    {
        return $this->container->get('transit.hook.payload_signer');
    }

    private function startDeployment($pullRequest)
    {
        $user = $pullRequest['user']['login'];
        $payload = json_encode(['environment' => 'production', 'deploy_user' => $user]);
        $this->createDeployment(
            $pullRequest['head']['repo']['full_name'],
            $pullRequest['head']['sha'],
            ['payload' => $payload, 'description' => "Deploying my sweet branch"]
        );
    }

    private function createDeployment($fullName, $sha, $c)
    {
    }
}
