<?php

namespace PlanMyLife\FrontBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use PlanMyLife\FrontBundle\DependencyInjection\PlanMyLifeFrontExtension;

class PlanMyLifeFrontBundle extends Bundle
{
    public function getContainerExtension()
    {
        // return the right extension instead of "auto-registering" it. Now the
        // alias can be pml_notification instead of plan_my_life_notification..
        if (null === $this->extension) {
            return new PlanMyLifeFrontExtension();
        }

        return $this->extension;
    }
}
