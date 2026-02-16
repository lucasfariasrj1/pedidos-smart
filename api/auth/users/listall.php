<?php
require_once __DIR__ . '/../../client.php';

// Proxy /api/auth/users/listall -> https://.../auth/users/listall
forward_request('/auth/users/listall');
