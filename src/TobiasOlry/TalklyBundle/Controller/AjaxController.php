<?php
/*
 * @author Tobias Olry <tobias.olry@gmail.com>
 */

namespace TobiasOlry\TalklyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AjaxController extends Controller
{
    /**
     * @Route("/ajax/markdown/")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function markdownAction(Request $request)
    {
        $html = $this->get('ciconia')->render($request->get('text'));

        return new JsonResponse(["html" => $html]);
    }
}
