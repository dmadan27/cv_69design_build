<?php
	Defined("BASE_PATH") or die("Dilarang Mengakses File Secara Langsung");

	/**
	* Abstract untuk Crud standar
	*/
	abstract class CrudAbstract extends Controller{
		
		/**
		* protected function list()
		*/
		abstract protected function list();

		/**
		* public function get_list()
		*/
		abstract protected function get_list();

		/**
		* public function form()
		*/
		abstract protected function form($id);

		/**
		* protected function add()
		*/
		abstract protected function add();

		/**
		* public function action_add()
		*/
		abstract protected function action_add();

		/**
		* protected function edit()
		*/
		abstract protected function edit($id);

		/**
		* public function action_edit()
		*/
		abstract protected function action_edit();

		/**
		* public function detail()
		*/
		abstract protected function detail($id);

		/**
		* public function delete()
		*/
		abstract protected function delete($id);

		/**
		* public function export()
		*/
		abstract protected function export();
	}