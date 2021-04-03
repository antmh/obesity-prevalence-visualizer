<?php

namespace controllers;

class AboutController extends Controller {
	public function index() {
		return $this->render('about');
	}
}