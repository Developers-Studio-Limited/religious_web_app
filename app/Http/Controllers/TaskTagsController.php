<?php

namespace App\Http\Controllers;

use App\Models\TaskTags;
use Illuminate\Http\Request;

class TaskTagsController extends Controller
{
    public function index()
    {
       $array = array('Black', 'White', 'Red', 'Blue', 'Green', 'Orange');
        return $array;
    }
}
