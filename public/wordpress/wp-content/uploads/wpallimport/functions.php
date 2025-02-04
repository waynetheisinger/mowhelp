<?php
function is_draft($value){
return (int)$value == 1 ? "draft": "publish";
}
function is_comment_disabled($value){
return $value ? "closed": "open";
}
?>

