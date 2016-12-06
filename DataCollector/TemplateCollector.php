<?php

namespace PlanMyLife\FrontBundle\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class TemplateCollector extends DataCollector {

    /**
     * Collects data for the given Request and Response.
     *
     * @param Request $request A Request instance
     * @param Response $response A Response instance
     * @param \Exception $exception An Exception instance
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        // TODO: Implement collect() method.
        var_dump($request); die();
    }

    public function getMethod()
    {
        return $this->data['method'];
    }

    public function getAcceptableContentTypes()
    {
        return $this->data['acceptable_content_types'];
    }

    /**
     * Returns the name of the collector.
     *
     * @return string The collector name
     */
    public function getName()
    {
        // TODO: Implement getName() method.
        return 'planmylife.template_collector';
    }
}