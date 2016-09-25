<?php

namespace spec\DMS\Raml\PathValidator;

use DMS\Raml\PathValidator\Path;
use PhpSpec\ObjectBehavior;

class PathSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->beConstructedWith('string');
        $this->shouldHaveType(Path::class);
    }

    public function it_should_strip_preceeding_slashes()
    {
        $this->beConstructedWith('/v2/user/account');
        $this->getPath()->shouldReturn('v2/user/account');
    }

    public function it_can_separate_on_the_right_parts()
    {
        $this->beConstructedWith('/v2/user/account');
        $this->getParts()->shouldReturn([
            'v2',
            'user',
            'account'
        ]);
    }

    public function it_can_retrieve_the_correct_full_path()
    {
        $this->beConstructedWith('/v2/user/account');
        $this->getFullPathFromPart('user')->shouldReturn('/v2/user');
    }

    public function it_can_return_remainder_of_a_path()
    {
        $this->beConstructedWith('/v2/user/account');
        $this->getPathAfterPart('/v2')->shouldReturn('/user/account');
    }
}
