<?php

namespace App\Core\Infrastructure\Ui\Controllers;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController
{
    /**
     * @Operation(
     *     tags={"[INCOMPLETE ] GET User"},
     *     consumes={"application/vnd.api+json"},
     *     produces={"application/vnd.api+json"},
     *     @SWG\Response(
     *         response=201,
     *         description="Should return the complete user resource",
     *         @Model(type="App\Core\Domain\Entity\User")
     *    ),
     *)
     */
    public function get(Request $request)
    {
        return new Response($request->get('userId'),Response::HTTP_OK);
    }
}