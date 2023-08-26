<?php

function objectToArray($object) {
    //先编码成json字符串，再解码成数组
    return json_decode(json_encode($object), true);
}

function test() {
    echo 222;
}




