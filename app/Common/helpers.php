<?php

function short_hash($source){    
    return base_convert(crc32($source),10,34);
}
function hash2int($hash){
    return base_convert($hash,34,10);
}