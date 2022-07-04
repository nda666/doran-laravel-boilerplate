<?php


test('api/home should return 200', function () {
    /** @var \Tests\TestCase $this */
    $response = $this->get('api/home');
    $response->assertStatus(200);
});

test('api/home?name=doran should return {message: "Hello, doran"}', function () {
     /** @var \Tests\TestCase $this */
    $response = $this->get('api/home?name=doran');
    $response->assertJson(['message' => 'Hello, doran']);
});
