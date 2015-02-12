<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\Talkly\Controller;

use Ciconia\Ciconia;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AjaxController
{
    public function __construct(Ciconia $markdown)
    {
        $this->markdown = $markdown;
    }

    public function markdownAction(Request $request)
    {
        $html = $this->markdown->render($request->get('text'));

        return new JsonResponse(["html" => $html]);
    }
}
