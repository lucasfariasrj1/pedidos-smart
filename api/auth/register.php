<?php
require_once __DIR__ . '/../client.php';

// Proxy /api/auth/register -> https://.../auth/register
forward_request('/auth/register');
