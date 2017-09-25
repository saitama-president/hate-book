<?php


function short_hash($source){    
    return base_convert(crc32($source),10,34);
}
function hash2int($hash){
    return base_convert($hash,34,10);
}

function aaa(){
    
    $hashes=[];
    $days=100;
    
    $now= \Carbon\Carbon::now();
    for($i=0;$i<86400*$days;$i++){
        $hash= short_hash($now->format("YmdHis"));
        $now=$now->addSecond();
        if(!key_exists($hash, $hashes)){
            $hashes[$hash]=0;
        }
        else{
            echo "カブったぞ[$hash]";
        }
        
        $hashes[$hash]++;
    }
    
    //arsort($hashes);
    
    
    //var_dump($hashes);
}