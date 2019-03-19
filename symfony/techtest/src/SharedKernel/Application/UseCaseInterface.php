<?php

namespace App\SharedKernel\Application;

interface UseCaseInterface
{
    public function handle(Request $request): Response;
}