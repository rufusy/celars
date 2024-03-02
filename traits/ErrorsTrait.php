<?php
/**
 * @author Rufusy Idachi <idachirufus@gmail.com>
 * @date: 3/2/2024
 * @time: 2:11 PM
 */

namespace app\traits;

trait ErrorsTrait
{
    private function stringifyModelErrors(array $modelErrors): string
    {
        $errorMsg = '';
        foreach ($modelErrors as $attributeErrors){
            for($i=0; $i < count($attributeErrors); $i++){
                $errorMsg .= ' ' . $attributeErrors[$i];
            }
        }
        return $errorMsg;
    }
}