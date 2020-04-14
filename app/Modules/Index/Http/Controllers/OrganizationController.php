<?php
namespace App\Modules\Index\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function __construct()
    {
        
	}

    public function index()
    {
        return view('index::organization.index');
    }
}
