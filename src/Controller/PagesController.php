<?php
declare(strict_types=1);

namespace JelmerD\Bootstrap\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;
use Cake\Http\Response;

class PagesController extends Controller
{
    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);
    }

    public function index(): Response
    {
        return $this->render();
    }

    public function tableHelper(): Response
    {
        return $this->render();
    }

    public function htmlHelper(): Response
    {
        return $this->render();
    }

    public function paginatorHelper(): Response
    {
        return $this->render();
    }

    public function formHelper(): Response
    {
        return $this->render();
    }
}
