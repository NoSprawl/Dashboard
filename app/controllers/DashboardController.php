<?php

class DashboardController extends Controller {

	public function __construct() {

	}

	public function getDashboard() {

		return View::make('dashboard.main');

	}

}