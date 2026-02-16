<?php
require_once __DIR__ . '/../client.php';

// Proxy /api/auth/logout -> https://.../auth/logout
forward_request('/auth/logout');
