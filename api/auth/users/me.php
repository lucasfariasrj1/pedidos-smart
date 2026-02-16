<?php
require_once __DIR__ . '/../../client.php';

// Proxy /api/auth/users/me -> https://.../auth/users/me
forward_request('/auth/users/me');
