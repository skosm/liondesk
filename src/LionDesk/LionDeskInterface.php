<?php

declare(strict_types = 1);

namespace Skosm\LionDesk;

/**
 * Interface LionDeskInterface
 * @package LionDesk
 */
interface LionDeskInterface
{
    public function echo(string $msg = '') : string;

    public function getUsers() : array;

    public function newSubmission(array $data) : int;

    public function newComment(array $data) : int;

    public function newActivity(array $data) : int;
}
