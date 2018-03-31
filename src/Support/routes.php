<?php
/**
 * Project: support
 *
 * Author: Farhan Wazir
 * Email: farhan.wazir@gmail.com, seejee1@gmail.com
 * Date: 3/26/2017
 * Time: 5:36 AM
 *
 *
 *
 * This project file is not redistributeable without permissions.
 * For more details about redistribution and reselling, contact to provided author details.
 */

//Load assets for template
$router->group(['prefix' => 'template/assets'], function ($route){
    $route->get('/{path}', ['as' => 'builtin.template.assets', 'uses' => function(\Illuminate\Http\Request $request){
        try{
            $file = realpath(__DIR__.'/../../template/src/assets/'.implode('/', array_slice($request->segments(), 2)));
            return response()->make(\Illuminate\Support\Facades\File::get($file))
                ->header('Content-Type', (\Illuminate\Support\Facades\File::extension($file) == 'css'? 'text/css':'text/javascript'));
        }catch (Exception $e){
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('File not found');
        }
    }])->where('path','.+');
});