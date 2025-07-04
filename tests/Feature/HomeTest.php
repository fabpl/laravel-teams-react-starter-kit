<?php

declare(strict_types=1);

test('users can visit the homepage', function () {
    $this->get('/')->assertOk();
});
