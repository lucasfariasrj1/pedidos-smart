<?php
require_once __DIR__ . '/../client.php';

// Proxy /api/auth/login -> https://.../auth/login
forward_request('/auth/login');
