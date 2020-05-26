<?php

namespace App\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

//autoconfigure option in services.yaml is set to true - allow symfony automatically to add tags to classes that implement certain interfaces
//php bin/console debug:container App\Security\ExampleVoter
class ExampleVoter implements VoterInterface
{

    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        return false;
    }
}