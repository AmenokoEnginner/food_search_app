<?php
function h($value) {
  echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
