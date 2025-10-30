<?php

it('returns a successful response on homepage', function () {
    $this->get('/')->assertStatus(200);
});